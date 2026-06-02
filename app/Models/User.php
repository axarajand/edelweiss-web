<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Storage;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    public const STATUS_PENDING = 'pending';
    public const STATUS_APPROVED = 'approved';
    public const STATUS_REJECTED = 'rejected';

    protected $fillable = [
        'name',
        'email',
        'password',
        'avatar',
        'status',
        'approved_at',
        'approved_by',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'approved_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    // ==================== Relationships ====================

    public function approver(): BelongsTo
    {
        return $this->belongsTo(User::class, 'approved_by');
    }

    // ==================== Status helpers ====================

    public function isApproved(): bool
    {
        return $this->status === self::STATUS_APPROVED;
    }

    public function isPending(): bool
    {
        return $this->status === self::STATUS_PENDING;
    }

    public function isRejected(): bool
    {
        return $this->status === self::STATUS_REJECTED;
    }

    /**
     * Super admin diidentifikasi dari email tetap.
     * Hanya super admin yang boleh menghapus user.
     */
    public function isSuperAdmin(): bool
    {
        return $this->email === 'admin@edelweiss.local';
    }

    // ==================== Avatar helpers ====================

    /**
     * URL avatar — file upload kalau ada, generate initial kalau tidak.
     */
    public function getAvatarUrlAttribute(): string
    {
        if ($this->avatar && Storage::disk('public')->exists($this->avatar)) {
            return Storage::disk('public')->url($this->avatar);
        }

        // Fallback: generate initial avatar via UI Avatars (eksternal, free)
        // atau pakai data URI inline (lebih robust, tidak butuh network)
        return $this->getInitialAvatarDataUri();
    }

    /**
     * Generate inline SVG initial avatar dengan warna konsisten dari nama.
     */
    public function getInitialAvatarDataUri(): string
    {
        $initial = strtoupper(mb_substr($this->name, 0, 1));

        // 8 warna emerald/green palette yang konsisten dengan tema
        $colors = [
            ['#10b981', '#059669'], // emerald
            ['#22c55e', '#16a34a'], // green
            ['#84cc16', '#65a30d'], // lime
            ['#14b8a6', '#0d9488'], // teal
            ['#06b6d4', '#0891b2'], // cyan
            ['#3b82f6', '#2563eb'], // blue
            ['#8b5cf6', '#7c3aed'], // violet
            ['#f59e0b', '#d97706'], // amber
        ];

        // Hash sederhana dari nama untuk pilih warna
        $hash = abs(crc32($this->name)) % count($colors);
        [$bg1, $bg2] = $colors[$hash];

        $svg = <<<SVG
<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 64 64">
    <defs>
        <linearGradient id="g" x1="0" y1="0" x2="64" y2="64" gradientUnits="userSpaceOnUse">
            <stop offset="0%" stop-color="$bg1"/>
            <stop offset="100%" stop-color="$bg2"/>
        </linearGradient>
    </defs>
    <rect width="64" height="64" fill="url(#g)"/>
    <text x="32" y="32" text-anchor="middle" dominant-baseline="central"
          font-family="system-ui, sans-serif" font-size="28" font-weight="600" fill="white">$initial</text>
</svg>
SVG;

        return 'data:image/svg+xml;base64,' . base64_encode($svg);
    }
}
