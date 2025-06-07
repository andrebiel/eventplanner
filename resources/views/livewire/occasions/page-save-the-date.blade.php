<?php

use Livewire\Volt\Component;
use App\Models\Occasion;
use Illuminate\Support\Collection;
use Livewire\Attributes\On;
use App\Models\GuestGroup;

new class extends Component {
    public Occasion $occasion;
    public GuestGroup $guestGroup;

    public function mount()
    {
        $this->guestGroup = $this->occasion->guestGroups()->wherePivot('thats_us', true)->first()
            ?? $this->occasion->guestGroups()->first();
    }
}; ?>

<div>
    <x-page.page-header title="{{ __('Save the Date') }}" description="{{ __('Manage your save the date for this event.') }}">
        <flux:button icon-trailing="eye" variant="primary" size="sm" 
            href="{{ route('save-the-date.detail', ['occasion' => $occasion, 'token' => $guestGroup->pivot->invite_token]) }}" target="_blank">
            {{ __('See full page') }}
        </flux:button>
    </x-page.page-header>

    <flux:separator class="my-8"/>


    <flux:heading size="xl">
        {{ __('Configuration') }}
    </flux:heading>

    <flux:description>
        {{ __('Preview the save the date page for this event.') }}
    </flux:description>

    <div class="mt-8">
            ... todo ...
    </div>
</div>

