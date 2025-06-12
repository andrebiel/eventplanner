<?php

use Livewire\Volt\Component;
use App\Models\Occasion;
use Illuminate\Support\Collection;
use Livewire\Attributes\On;
use App\Models\Attendee;

new class extends Component {
    public Occasion $occasion;
    public Collection $attendees;

    public function mount()
    {
        $this->attendees = $this->occasion->attendees()->with('guest', 'guestGroup')->get();
    }

}; ?>

<div>
    <x-page.page-header title="{{ __('Attendees') }}" description="{{ __('All guests that confirmed their attendance.') }}"></x-page.page-header>

    <flux:table>
        <flux:table.columns>
            <flux:table.column>{{ __('Group') }}</flux:table.column>
            <flux:table.column>{{ __('Name') }}</flux:table.column>
            <flux:table.column>{{ __('Accepted at') }}</flux:table.column>
        </flux:table.columns>

        <flux:table.rows>
            @foreach ($attendees as $attendee)
                <flux:table.row>
                    <flux:table.cell>
                        <flux:text>{{ $attendee->guestGroup->name }}</flux:text>
                    </flux:table.cell>

                    <flux:table.cell>
                        <flux:text>{{ $attendee->guest->name }}</flux:text>
                    </flux:table.cell>

                    <flux:table.cell>
                        <flux:text>{{ $attendee->created_at->format('d.m.Y H:i') }}</flux:text>
                    </flux:table.cell>
                </flux:table.row>
            @endforeach
        </flux:table.rows>
    </flux:table>
</div>
