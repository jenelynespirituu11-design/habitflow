<?php

namespace App\Http\Controllers;

use App\Models\Habit;
use App\Models\HabitLog;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;

class ProgressController extends Controller
{
    public function show()
    {
        $userId  = Auth::id();
        $today   = Carbon::today();
        $user    = Auth::user();

        $habits     = Habit::where('user_id', $userId)->get();
        $habitIds   = $habits->pluck('id')->toArray();
        $activeCount = $habits->where('status', 'active')->count();

        // ── Summary stats ─────────────────────────────────────────────────────
        $totalLogs = HabitLog::whereIn('habit_id', $habitIds)
            ->where('completed', true)
            ->count();

        $bestStreak = $habits->map(fn (Habit $h) => $h->longestStreak())->max() ?? 0;

        // Overall completion rate (last 30 days, active habits only)
        $activeIds  = $habits->where('status', 'active')->pluck('id');
        $last30Done = HabitLog::whereIn('habit_id', $activeIds)
            ->whereBetween('logged_date', [$today->copy()->subDays(29), $today])
            ->where('completed', true)
            ->count();
        $last30Possible  = max(1, $activeCount * 30);
        $overallRate     = round(($last30Done / $last30Possible) * 100, 1);

        // Current global streak (any day with ≥1 completed log)
        $currentStreak = 0;
        $checkDate     = $today->copy();
        while (true) {
            $has = HabitLog::whereIn('habit_id', $habitIds)
                ->where('logged_date', $checkDate->toDateString())
                ->where('completed', true)
                ->exists();
            if (! $has) {
                if ($currentStreak === 0 && $checkDate->isToday()) {
                    $checkDate->subDay();
                    continue;
                }
                break;
            }
            $currentStreak++;
            $checkDate->subDay();
        }

        // ── 30-day heatmap ────────────────────────────────────────────────────
        $logCountsByDate = HabitLog::whereIn('habit_id', $habitIds)
            ->whereBetween('logged_date', [$today->copy()->subDays(29), $today])
            ->where('completed', true)
            ->selectRaw('logged_date, COUNT(*) as cnt')
            ->groupBy('logged_date')
            ->pluck('cnt', 'logged_date')
            ->toArray();

        $heatmap = [];
        for ($i = 29; $i >= 0; $i--) {
            $d            = $today->copy()->subDays($i);
            $dateStr      = $d->toDateString();
            $heatmap[]    = [
                'date'    => $dateStr,
                'label'   => $d->format('D, M j'),
                'count'   => $logCountsByDate[$dateStr] ?? 0,
                'isToday' => $d->isToday(),
            ];
        }

        // ── Last 6 months bar chart ───────────────────────────────────────────
        $monthlyLabels = [];
        $monthlyRates  = [];

        for ($i = 5; $i >= 0; $i--) {
            $mStart = $today->copy()->subMonths($i)->startOfMonth();
            $mEnd   = $today->copy()->subMonths($i)->endOfMonth();
            $cap    = $mEnd->gt($today) ? $today : $mEnd;

            $done = HabitLog::whereIn('habit_id', $activeIds)
                ->whereBetween('logged_date', [$mStart, $cap])
                ->where('completed', true)
                ->count();

            $days     = $mStart->diffInDays($cap) + 1;
            $possible = max(1, $activeCount * $days);

            $monthlyLabels[] = $mStart->format('M y');
            $monthlyRates[]  = round(($done / $possible) * 100);
        }

        // ── Best day of week ──────────────────────────────────────────────────
        $dowRow = HabitLog::whereIn('habit_id', $habitIds)
            ->where('completed', true)
            ->selectRaw('DAYOFWEEK(logged_date) as dow, COUNT(*) as cnt')
            ->groupBy('dow')
            ->orderByDesc('cnt')
            ->first();

        $dowNames   = [1 => 'Sunday', 2 => 'Monday', 3 => 'Tuesday', 4 => 'Wednesday', 5 => 'Thursday', 6 => 'Friday', 7 => 'Saturday'];
        $bestDayStr = $dowRow ? ($dowNames[$dowRow->dow] ?? '—') : '—';

        // ── Per-habit breakdown ───────────────────────────────────────────────
        $habitStats = $habits
            ->map(fn (Habit $h) => [
                'habit'   => $h,
                'done'    => $h->completedCount(),
                'streak'  => $h->currentStreak(),
                'longest' => $h->longestStreak(),
                'rate'    => $h->completionRate(),
                'days'    => $h->daysSinceStart(),
            ])
            ->sortByDesc('rate')
            ->values();

        return view('progress.index', compact(
            'totalLogs',
            'bestStreak',
            'overallRate',
            'currentStreak',
            'heatmap',
            'monthlyLabels',
            'monthlyRates',
            'habitStats',
            'bestDayStr',
            'today',
            'activeCount',
        ));
    }
}
