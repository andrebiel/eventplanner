<?php

use Livewire\Volt\Component;
use App\Models\Occasion;
use Illuminate\Support\Collection;
use Livewire\Attributes\On;
use App\Models\Attendee;

new class extends Component {
    public Occasion $occasion;
    public Collection $guestGroups;
    public Collection $groupAttendees;

    public function mount()
    {
        $this->fetchGuestGroups();
        $this->groupAttendees = $this->occasion->attendees()
            ->with('guest', 'guestGroup')
            ->get()
            ->reduce(function (Collection $carry, Attendee $attendee) {
                if(!$carry->has($attendee->guest_group_id)) {
                    $carry->put($attendee->guest_group_id, [
                        'male' => 0,
                        'female' => 0,
                        'child' => 0,
                    ]);
                }

                $groupStats = $carry->get($attendee->guest_group_id);
                $groupStats[$attendee->guest->type]++;
                $carry->put($attendee->guest_group_id, $groupStats);

                return $carry;
            }, collect());
    }

    #[On('guest-group-updated')]
    public function handleGuestGroupUpdated()
    {
        $this->fetchGuestGroups();
        Flux::modals()->close();
    }

    public function deleteGuestGroup($guestGroupId)
    {
        $this->occasion->guestGroups()->find($guestGroupId)->delete();
        $this->fetchGuestGroups();
    }

    private function fetchGuestGroups()
    {
        $this->guestGroups = $this->occasion->guestGroups()
            ->with('guests')
            ->orderBy('name')
            ->get();
    }
}; ?>

<div>
    <x-page.page-header title="{{ __('Guestlist') }}" description="{{ __('Manage your guestlist for this event.') }}">
        <flux:modal.trigger name="add-guests">
            <flux:button icon="plus" variant="primary" size="sm">
                {{ __('Add Guests') }}
            </flux:button>
        </flux:modal.trigger>
    </x-page.page-header>

    <flux:table>
        <flux:table.columns>
            <flux:table.column>{{ __('Name') }}</flux:table.column>
            <flux:table.column>{{ __('Status') }}</flux:table.column>
            <flux:table.column></flux:table.column>
        </flux:table.columns>

        <flux:table.rows>
            @foreach ($guestGroups as $guestGroup)
                @php 
                    $attendees = $groupAttendees->get($guestGroup->id); 
                    $joinStr = $attendees ? collect([
                        $attendees['female'] > 0 ? trans_choice('WithFemales', $attendees['female']) : null,
                        $attendees['male'] > 0 ? trans_choice('WithMales', $attendees['male']) : null,
                        $attendees['child'] > 0 ? trans_choice('WithChildren', $attendees['child']) : null,
                    ])->filter(fn($item) => !empty($item))->implode(', ') : '';
                @endphp

                <flux:table.row wire:key="table-row-{{ $guestGroup->id }}">
                    <flux:table.cell>
                        <flux:modal.trigger name="edit-guest-group-{{ $guestGroup->id }}">
                            <button class="hover:cursor-pointer outline-none">
                                <flux:text>{{ $guestGroup->name }}</flux:text>
                            </button>
                        </flux:modal.trigger>
                    </flux:table.cell>

                    <flux:table.cell>
                        @if($attendees)
                            <flux:badge color="lime">
                                {{ $joinStr }}
                            </flux:badge>
                        @endif
                    </flux:table.cell>

                    <flux:table.cell class="text-right">
                        <flux:button icon="trash" variant="filled" size="sm" class="hover:cursor-pointer" wire:click="deleteGuestGroup({{ $guestGroup->id }})">
                            {{ __('Delete') }}
                        </flux:button>
                    </flux:table.cell>
                </flux:table.row>

                <flux:modal name="edit-guest-group-{{ $guestGroup->id }}" wire:key="edit-guest-group-{{ $guestGroup->id }}">
                    <livewire:occasions.guests-add-form 
                        :occasion="$occasion" 
                        :guestGroup="$guestGroup" 
                        wire:key="edit-guest-group-form-{{ $guestGroup->id }}" 
                    />
                </flux:modal>
            @endforeach
        </flux:table.rows>
    </flux:table>

    <flux:modal name="add-guests">
        <livewire:occasions.guests-add-form :occasion="$occasion" wire:key="create-guests-form" />
    </flux:modal>
</div>
