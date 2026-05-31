<?php

namespace App\Http\Controllers;

use App\Models\Habit;
use App\Models\HabitLog;
use Illuminate\Support\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        $userId = auth()->id();

        // Get today's completed habits
        $todayCompleted = HabitLog::where('user_id', $userId)
            ->whereDate('logged_date', today())
            ->where('completed', true)
            ->count();

        // Get all active habits
        $activeHabits = Habit::where('user_id', $userId)
            ->where('status', 'active')
            ->count();

        // Get total completed this week
        $weekCompleted = HabitLog::where('user_id', $userId)
            ->whereBetween('logged_date', [now()->startOfWeek(), now()->endOfWeek()])
            ->where('completed', true)
            ->count();

        // Calculate current streak
        $currentStreak = $this->calculateStreak($userId);

        // Get recent active habits for table
        $recentHabits = Habit::where('user_id', $userId)
            ->where('status', 'active')
            ->limit(5)
            ->get();

        // Prepare last-7-days chart data
        $chartData = $this->getWeeklyChartData($userId);

        return view('dashboard.index', [
            'todayCompleted' => $todayCompleted,
            'activeHabits'   => $activeHabits,
            'weekCompleted'  => $weekCompleted,
            'currentStreak'  => $currentStreak,
            'recentHabits'   => $recentHabits,
            'chartData'      => $chartData,
        ]);
    }

    // Calculate consecutive days with at least one completion
    private function calculateStreak(int $userId): int
    {
        $streak = 0;
        $date   = now();

        while (HabitLog::where('user_id', $userId)
            ->whereDate('logged_date', $date)
            ->where('completed', true)
            ->exists()) {
            $streak++;
            $date->subDay();
        }

        return $streak;
    }

    // Get completion count for each of the last 7 days
    private function getWeeklyChartData(int $userId): array
    {
        $days      = [];
        $completed = [];

        for ($i = 6; $i >= 0; $i--) {
            $date        = now()->subDays($i);
            $days[]      = $date->format('D');
            $completed[] = HabitLog::where('user_id', $userId)
                ->whereDate('logged_date', $date)
                ->where('completed', true)
                ->count();
        }

        return ['days' => $days, 'completed' => $completed];
    }
}
