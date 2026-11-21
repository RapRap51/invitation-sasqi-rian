<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Guest extends Model
{
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