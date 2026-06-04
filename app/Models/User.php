<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    protected $fillable = [
        'full_name',
        'name',          // alias – stored as full_name
        'email',
        'password',
        'bio',
        'profile_picture',
        'is_admin',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    // ─── Relationships ────────────────────────────────────────────────────────

    /**
     * One user has many expenses.
     * (Eloquent relationship – Separation of Concerns)
     */
    public function expenses()
    {
        return $this->hasMany(Expense::class);
    }

    /**
     * One user has many habits.
     */
    public function habits()
    {
        return $this->hasMany(Habit::class);
    }

    public function habitLogs()
    {
        return $this->hasMany(HabitLog::class);
    }

    // Map professor's 'name' field → database 'full_name' column
    public function getNameAttribute(): string
    {
        return $this->attributes['full_name'] ?? '';
    }

    public function setNameAttribute(string $value): void
    {
        $this->attributes['full_name'] = $value;
    }

    public function getProfilePictureUrlAttribute(): string
    {
        $value = $this->getAttribute('profile_picture');

        if (!$value) {
            return '/images/default-avatar.png';
        }

        // Already a base64 data URL — return as-is
        if (str_starts_with($value, 'data:')) {
            return $value;
        }

        // Legacy storage path
        return '/storage/' . $value;
    }
}
