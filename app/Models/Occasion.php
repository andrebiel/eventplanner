<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Occasion extends Model
{
    /** @use HasFactory<\Database\Factories\OccasionFactory> */
    use HasFactory;

    protected $guarded = [];

    protected $casts = [
        'date' => 'date',
    ];

    public function guestGroups()
    {
        return $this->belongsToMany(GuestGroup::class)->withPivot('importance', 'invite_token');
    }

    public function attendees()
    {
        return $this->hasMany(Attendee::class);
    }
}
