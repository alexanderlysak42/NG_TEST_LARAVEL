<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Random\RandomException;

class Registration extends Model
{
    protected $fillable = [
        'username',
        'phone_number',
        'token',
        'is_active',
        'expires_at',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'expires_at' => 'datetime',
    ];

    /**
     * @return HasMany<GameResult, $this>
     */
    public function gameResults(): HasMany
    {
        return $this->hasMany(GameResult::class);
    }

    public function isValid(): bool
    {
        return $this->is_active && $this->expires_at->isFuture();
    }

    /**
     * @throws RandomException
     */
    public static function generateToken(): string
    {
        return bin2hex(random_bytes(32));
    }
}
