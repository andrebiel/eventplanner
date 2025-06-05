<?php

use Livewire\Volt\Component;
use App\Models\Occasion;
use Illuminate\Support\Collection;
use Livewire\Attributes\On;

new class extends Component {
    public Occasion $occasion;
    public Collection $guestGroups;

    public function mount()
    {
        $this->guestGroups = $this->occasion->guestGroups()
            ->with('guests')->get();
    }
}; ?>

<div>
    <x-page.page-header title="{{ __('Save the Date') }}" description="{{ __('Manage your save the date for this event.') }}">
        <flux:button icon-trailing="eye" variant="primary" size="sm" 
            href="{{ route('save-the-date.detail', ['occasion' => $occasion, 'token' => $guestGroups->first()->pivot->invite_token]) }}" target="_blank">
            {{ __('See full page') }}
        </flux:button>
    </x-page.page-header>

    <flux:separator class="my-8"/>


    <flux:heading size="xl">
        {{ __('Preview') }}
    </flux:heading>

    <flux:description>
        {{ __('Preview the save the date page for this event.') }}
    </flux:description>

    <div class="flex items-start justify-between gap-x-8 mt-8">
        <div class="border p-4 rounded-md w-[480px] h-[900px]">
            <iframe src="{{ route('save-the-date.detail', ['occasion' => $occasion, 'token' => $guestGroups->first()->pivot->invite_token]) }}" class="w-full h-full rounded-md"></iframe>
        </div>

        <div class="border p-4 rounded-md w-[480px] h-[900px]">
            <iframe src="{{ route('save-the-date.join', ['occasion' => $occasion, 'token' => $guestGroups->first()->pivot->invite_token]) }}" class="w-full h-full rounded-md"></iframe>
        </div>

        <div class="flex-1">
            <flux:heading>
                {{ __('Configuration') }}
            </flux:heading>

            ... todo ...
        </div>

    </div>
</div>

