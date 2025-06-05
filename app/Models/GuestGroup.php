<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Enums\GuestType;

class GuestGroup extends Model
{
    /** @use HasFactory<\Database\Factories\GuestGroupFactory> */
    use HasFactory;

    protected $guarded = [];

    public function occasions()
    {
        return $this->belongsToMany(GuestGroup::class)->withPivot('importance');
    }

    public function guests()
    {
        return $this->hasMany(Guest::class);
    }

    public function adultGuests()
    {
        return $this->guests->whereIn('type', GuestType::adultTypes());
    }

    public function childGuests()
    {
        return $this->guests->whereIn('type', GuestType::childTypes());
    }
}
