<?php

use Livewire\Volt\Component;
use App\Models\Occasion;
use App\Enums\GuestType;
use App\Models\GuestGroup;
use Illuminate\Validation\Rule;
use App\Enums\GuestOccasionImportance;

const DEFAULT_GUEST = [
    'name' => '',
    'type' => \App\Enums\GuestType::Female->value,
];

new class extends Component {
    public Occasion $occasion;
    public ?GuestGroup $guestGroup = null;
    public ?string $email = null;
    public string $groupName = '';
    public array $guests = [];
    public float $importance = 0.5; // GuestOccasionImportance::HIGH->getValue();

    protected function rules()
    {
        return [
            'groupName' => ['required'],
            'email' => ['nullable', 'email'],
            'guests' => ['required', 'array'],
            'guests.*.name' => ['required', 'string'],
            'guests.*.type' => ['required', 'string', 'in:' . implode(',', GuestType::values())],
        ];
    }

    public function mount()
    {
        $this->groupName = $this->guestGroup?->name ?? '';
        $this->email = $this->guestGroup?->email ?? '';
        $this->importance = $this->guestGroup?->pivot->importance ?? GuestOccasionImportance::MEDIUM->getValue();

        $this->guests = $this->guestGroup?->guests->map(fn ($guest) => [    
            'name' => $guest->name,
            'type' => $guest->type,
        ])->toArray() ?? [DEFAULT_GUEST];
    }

    public function save()
    {
        $this->validate();

        if(empty($this->guestGroup)) {
            $group = GuestGroup::create([
                'name' => count($this->guests) === 1 
                    ? $this->groupName . '-' . $this->guests[0]['name'] 
                    : $this->groupName,
                'email' => $this->email ,
            ]);

            $this->occasion->guestGroups()->attach($group, ['importance' => $this->importance]);
        } else {
            $this->guestGroup->update([
                'name' => $this->groupName,
                'email' => $this->email ,
            ]);

            $this->occasion->guestGroups()->updateExistingPivot($this->guestGroup, [
                'importance' => $this->importance,
                'invite_token' => Str::random(32),
            ]);

            $group = $this->guestGroup;
        }

        $group->guests()->delete();
        foreach ($this->guests as $guest) {
            $group->guests()->create([
                'name' => $guest['name'],
                'type' => $guest['type'],
            ]);
        }

        $this->dispatch('guest-group-updated');

        // hack to reset the form after the modal is closed
        $this->js("setTimeout(() => { window.livewire.find('" . $this->getId() . "').resetForm() }, 500)");
    }

    private function resetForm()
    {
        $this->groupName = '';
        $this->email = '';
        $this->guests = [DEFAULT_GUEST];
        $this->importance = 0.5;
        $this->guestGroup = null;
    }

    public function addGuest()
    {
        $type = GuestType::Female->value;
        if(count($this->guests) === 1 && $this->guests[0]['type'] === GuestType::Female->value) {
            $type = GuestType::Male->value;
        } else if(count($this->guests) === 1 && $this->guests[0]['type'] === GuestType::Male->value) {
            $type = GuestType::Female->value;
        } else {
            $type = GuestType::Child->value;
        }

        $this->guests[] = [
            'name' => '',
            'type' => $type
        ];
    }


    public function removeGuest($index)
    {
        unset($this->guests[$index]);
    }

}; ?>

<div>
    <form wire:submit="save">
        <div>
            <flux:input label="{{ __('GroupNameLabel') }}" placeholder="{{ __('GroupNamePlaceholder') }}" 
                description="{{ __('GroupNameDescription') }}" wire:model="groupName" />
        </div>

        <div class="my-4">
            <flux:input type="email" label="{{ __('EmailLabel') }}" placeholder="{{ __('EmailPlaceholder') }}" 
                description="{{ __('EmailDescription') }}" wire:model="email" />
        </div>

        <div class="my-4">
            <flux:radio.group wire:model="importance" label="{{ __('ImportanceLabel') }}" variant="segmented">
                @foreach (GuestOccasionImportance::cases() as $importance)
                    <flux:radio label="{{ $importance->getLabel() }}" value="{{ $importance->getValue() }}" />
                @endforeach
            </flux:radio.group>
        </div>

        <flux:separator text="{{ __('Persons') }}" />

        <div class="flex flex-col gap-y-4 mt-4">
            @foreach ($guests as $guest)
                <div class="flex gap-4 mb-4 items-center">
                    <div class="flex-1">
                        <flux:input placeholder="{{ __('GuestNamePlaceholder') }}" class="w-full" wire:model="guests.{{ $loop->index }}.name" />
                    </div>

                    <div class="flex-1">
                        <flux:select placeholder="{{ __('GuestTypePlaceholder') }}" class="w-full" wire:model="guests.{{ $loop->index }}.type">
                            @foreach (GuestType::values() as $type)
                                <flux:select.option value="{{ $type }}">{{ __('GuestType.' . $type) }}</flux:select.option>
                            @endforeach
                        </flux:select>
                    </div>

                    <flux:button type="button" variant="subtle" wire:click="removeGuest({{ $loop->index }})">
                        <flux:icon name="trash" />
                    </flux:button>
                </div>
            @endforeach
        </div>

        <div class="flex justify-end gap-x-4 mt-8">
            <flux:button type="button" size="sm" icon="plus" class="self-center" wire:click="addGuest">
                {{ __('Additional Guest') }}
            </flux:button>

            <flux:button type="submit" variant="primary" icon="check" size="sm">
                {{ __('Save') }}
            </flux:button>
        </div>
    </form>
</div>
