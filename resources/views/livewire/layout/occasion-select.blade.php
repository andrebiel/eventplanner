<?php

use App\Models\Occasion;
use Livewire\Volt\Component;

new class extends Component {
    public function setOccasion(Occasion $occasion)
    {
        if (auth()->user()->current_occasion_id === $occasion->id) {
            return;
        }

        auth()->user()->current_occasion_id = $occasion->id;
        auth()->user()->save();
        $this->redirect(route('dashboard'));
    }
}; ?>

<div>
    <flux:dropdown>
        <button class="w-full flex text-left justify-between hover:bg-white items-center gap-2 border border-transparent hover:border-zinc-200
         p-2 rounded-md hover:cursor-pointer transition-colors duration-200">
            <div class="flex flex-col gap-1">
                <flux:text class="text-xs">{{ __('Current event') }}</flux:text>
                <flux:text class="font-bold truncate text-black">{{ auth()->user()->currentOccasion->name }}</flux:text>
            </div>

            <flux:icon name="chevron-down" class="text-gray-500" variant="micro" />
        </button>

        <flux:menu>
            @if (auth()->user()->occasions->count() > 1)
                @foreach (auth()->user()->occasions as $occasion)
                    <flux:menu.item wire:click="setOccasion({{ $occasion }})">{{ $occasion->name }}</flux:menu.item>
                @endforeach
            @else
                <flux:menu.item icon="plus" href="#">{{ __('TODO: New event') }}</flux:menu.item>
            @endif
        </flux:menu>
    </flux:dropdown>
</div>
