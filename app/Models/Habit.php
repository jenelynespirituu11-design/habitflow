<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;

/**
 * @property int                       $id
 * @property int                       $user_id
 * @property string                    $name
 * @property string|null               $description
 * @property string                    $color
 * @property string                    $icon
 * @property string                    $frequency
 * @property int                       $target_days
 * @property string                    $category
 * @property string                    $status
 * @property \Illuminate\Support\Carbon $start_date
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 */
class Habit extends Model
{
    use HasFactory;

    // Runtime-computed fields set by HabitController::enrichHabit()
    public bool $today_done   = false;
    public ?int $today_log_id = null;
    public int  $week_done    = 0;
    public int  $streak       = 0;
    public int  $week_pct     = 0;

    protected $fillable = [
        'user_id',
        'name',
        'description',
        'color',
        'icon',
        'frequency',
        'target_days',
        'category',
        'status',
        'start_date',
    ];

    protected $casts = [
        'target_days' => 'integer',
    ];

    public function getStartDateAttribute(mixed $value): Carbon
    {
        return Carbon::parse($value)->startOfDay();
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // One habit has many logs (alias used in professor's spec)
    public function logs()
    {
        return $this->hasMany(HabitLog::class);
    }

    public function habitLogs()
    {
        return $this->hasMany(HabitLog::class);
    }

    public function scopeActive(Builder $query): Builder
    {
        return $query->where('status', 'active');
    }

    public function scopeCompleted(Builder $query): Builder
    {
        return $query->where('status', 'completed');
    }

    public function scopePaused(Builder $query): Builder
    {
        return $query->where('status', 'paused');
    }

    // ── Stat methods ───────────────────────────────────────────────────────────

    public function completedCount(): int
    {
        return $this->habitLogs()->where('completed', true)->count();
    }

    public function currentStreak(): int
    {
        $streak    = 0;
        $checkDate = Carbon::today();

        while (true) {
            $has = $this->habitLogs()
                ->where('logged_date', $checkDate->toDateString())
                ->where('completed', true)
                ->exists();

            if (! $has) {
                if ($streak === 0 && $checkDate->isToday()) {
                    $checkDate->subDay();
                    continue;
                }
                break;
            }
            $streak++;
            $checkDate->subDay();
        }

        return $streak;
    }

    public function longestStreak(): int
    {
        $dates = $this->habitLogs()
            ->where('completed', true)
            ->orderBy('logged_date')
            ->pluck('logged_date')
            ->map(fn ($d) => Carbon::parse($d)->toDateString())
            ->values()
            ->toArray();

        if (empty($dates)) {
            return 0;
        }

        $longest = 1;
        $current = 1;

        for ($i = 1; $i < \count($dates); $i++) {
            $prev = Carbon::parse($dates[$i - 1]);
            $curr = Carbon::parse($dates[$i]);

            if ($prev->copy()->addDay()->toDateString() === $curr->toDateString()) {
                $current++;
                if ($current > $longest) {
                    $longest = $current;
                }
            } else {
                $current = 1;
            }
        }

        return $longest;
    }

    public function completionRate(): float
    {
        $daysSinceStart = max(1, (int) $this->start_date->diffInDays(Carbon::today()) + 1);
        $completed      = $this->completedCount();

        return round(($completed / $daysSinceStart) * 100, 1);
    }

    public function daysSinceStart(): int
    {
        return max(1, (int) $this->start_date->diffInDays(Carbon::today()) + 1);
    }
}
