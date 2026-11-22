<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Str;

class Guest extends Model
{
    /**
     * Awalan kode undangan (NT = NodeTone).
     * Kode lengkap: NT-XXXXXX, kode pendek di URL: XXXXXX.
     */
    public const CODE_PREFIX = 'NT-';

    /**
     * Ubah kode pendek (dari URL atau QR) menjadi kode lengkap di database.
     */
    public static function fullCode(string $shortCode): string
    {
        return self::CODE_PREFIX . strtoupper(trim($shortCode));
    }

    /**
     * Kode pendek tanpa awalan, dipakai di URL undangan dan QR.
     */
    public function getShortCodeAttribute(): string
    {
        return Str::after($this->unique_code, self::CODE_PREFIX);
    }

    protected $fillable = [
        'name',
        'phone',
        'invitation_count',
        'unique_code',
        'qr_token',
        'rsvp_status',
        'checkin_status',
    ];

    public function rsvp(): HasOne
    {
        return $this->hasOne(Rsvp::class);
    }

    public function checkin(): HasOne
    {
        return $this->hasOne(Checkin::class);
    }

    public function wishes(): HasMany
    {
    return $this->hasMany(Wish::class);
    }
}