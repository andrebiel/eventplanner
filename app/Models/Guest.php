<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Guest extends Model
{
    /** @use HasFactory<\Database\Factories\GuestFactory> */
    use HasFactory;

    protected $guarded = [];

    public function group()
    {
        return $this->belongsTo(GuestGroup::class);
    }

    public function attendances(): HasManyThrough
    {
        return $this->hasManyThrough(Occasion::class, Attendee::class);
    }

    public function plusOnes(): HasMany
    {
        return $this->hasMany(Guest::class, 'parent_id');
    }

    public function plusOne(): HasOne
    {
        return $this->hasOne(Guest::class, 'parent_id');
    }
}
