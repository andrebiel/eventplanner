<?php

use Livewire\Volt\Component;
use Livewire\Attributes\Validate;
use App\Models\Occasion;

new class extends Component {
    public Occasion | null $occasion = null;

    #[Validate('required')]
    public $name;

    #[Validate('required')]
    public $date;

    public function mount()
    {
        if ($this->occasion) {
            $this->name = $this->occasion->name;
            $this->date = $this->occasion->date;
        }
    }

    public function updateOccasion()
    {
        $this->validate();

        if ($this->occasion) {
            $this->occasion->update([
                'name' => $this->name,
                'date' => $this->date,
            ]);

        $this->dispatch('occasion-updated');
        } else {
            Occasion::create([
                'name' => $this->name,
                'date' => $this->date,
            ]);

            $this->dispatch('occasion-created');
        }
    }
}; ?>

<div>
    <form wire:submit="updateOccasion()">
        <flux:heading size="lg">{{ __('Update Occasion') }}</flux:heading>
        <flux:text class="mt-2">{{ __('Update the details of the occasion.') }}</flux:text>

        <div class="my-8 flex flex-col gap-4">
            <flux:input name="name" label="Name" wire:model="name" />
            <flux:date-picker name="date" label="Date" wire:model="date" />
        </div>

        <div class="flex justify-end">
            <flux:button type="submit" variant="primary">{{ __('Update') }}</flux:button>
        </div>
    </form>
</div>
