<?php

use Livewire\Volt\Component;
use Illuminate\Support\Collection;
use function Livewire\Volt\{computed};
use App\Enums\GuestType;
use App\Models\Guest;

$group_ids = auth()->user()->currentOccasion
    ->guestGroups()
    ->get()
    ->pluck('id');

$guests = computed(function () use ($group_ids) {
    return Guest::whereIn('guest_group_id', $group_ids)->get()->groupBy('type');
});



 ?>

<div>
    <p>By Type</p>
    @foreach($this->guests as $type => $guests)
        <div>
            {{ GuestType::from($type)->getLabel() }}: {{ $guests->count() }}
        </div>
    @endforeach

    <p>Sum: {{ $this->guests->map(fn($guests) => $guests->count())->sum() }}</p>
</div>
