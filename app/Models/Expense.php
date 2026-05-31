<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Expense extends Model
{
    use HasFactory;

    /**
     * The fields that can be mass-assigned.
     * (Separation of Concerns: Model controls which columns are writable)
     */
    protected $fillable = [
        'user_id',
        'category',
        'amount',
        'date',
        'description',
    ];

    /**
     * Cast columns to the correct PHP types automatically.
     */
    protected $casts = [
        'date'   => 'date',
        'amount' => 'decimal:2',
    ];

    // ─── Relationships ────────────────────────────────────────────────────────

    /**
     * Each expense belongs to one user.
     * (Eloquent relationship – no raw SQL needed)
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
