<?php

use Livewire\Volt\Component;
use App\Models\Occasion;
use Illuminate\Support\Collection;
use Livewire\Attributes\On;
use App\Models\Attendee;
use Livewire\WithClipboard;

new class extends Component {
    public Occasion $occasion;
    public Collection $guestGroups;

    public function mount()
    {
        $this->fetchGuestGroups();
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
            ->orderBy('thats_us', 'desc')
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
            <flux:table.column>{{ __('Plus One Allowed') }}</flux:table.column>
            <flux:table.column>{{ __('Importance') }}</flux:table.column>
            <flux:table.column></flux:table.column>
        </flux:table.columns>

        <flux:table.rows>
            @foreach ($guestGroups as $guestGroup)
                <flux:table.row wire:key="table-row-{{ $guestGroup->id }}">
                    <flux:table.cell>
                        <flux:modal.trigger name="edit-guest-group-{{ $guestGroup->id }}">
                            <button class="hover:cursor-pointer outline-none">
                                <flux:text class="{{ $guestGroup->pivot->thats_us ? 'font-bold' : '' }}">{{ $guestGroup->name }}</flux:text>
                            </button>
                        </flux:modal.trigger>
                    </flux:table.cell>

                    <flux:table.cell>
                        <flux:text>
                            {{ $guestGroup->pivot->plus_one_allowed ? __('Yes') : __('No') }}
                        </flux:text>
                    </flux:table.cell>

                    <flux:table.cell>
                        <x-star-rating :rating="$guestGroup->pivot->importance" />
                    </flux:table.cell>

                    <flux:table.cell class="text-right">
                        <flux:button icon="document-duplicate" size="sm" class="hover:cursor-pointer" 
                            wire:click="$js.copyLink('{{ route('save-the-date.detail', ['occasion' => $occasion, 'token' => $guestGroup->pivot->invite_token]) }}')">
                            {{ __('Copy Link') }}
                        </flux:button>

                        <flux:button icon="trash" variant="danger" size="sm" class="hover:cursor-pointer" wire:click="deleteGuestGroup({{ $guestGroup->id }})">
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

@script
<script>
    $js('copyLink', (link) => {
        navigator.clipboard.writeText(link);
    })
</script>
@endscript