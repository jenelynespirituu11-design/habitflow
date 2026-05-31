<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @property int                       $id
 * @property int                       $habit_id
 * @property int                       $user_id
 * @property \Illuminate\Support\Carbon $logged_date
 * @property bool                      $completed
 * @property string|null               $notes
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 */
class HabitLog extends Model
{
    use HasFactory;

    protected $fillable = [
        'habit_id',
        'user_id',
        'logged_date',
        'completed',
        'notes',
    ];

    protected $casts = [
        'logged_date' => 'date',
        'completed' => 'boolean',
    ];

    public function habit()
    {
        return $this->belongsTo(Habit::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
