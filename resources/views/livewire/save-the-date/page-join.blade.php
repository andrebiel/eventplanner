<?php

use Livewire\Volt\Component;
use App\Models\Occasion;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use App\Models\GuestGroup;
use App\Models\Attendee;
use Illuminate\Support\Collection;

new 
#[Layout('components.layouts.standalone-page')]
#[Title('Save the Date')]
class extends Component {
    public Occasion $occasion;
    public string $token;
    public GuestGroup $guestGroup;
    public array $joinlist = [];
    public bool $loading = false;
    public Collection $attendees;

    public function mount()
    {
        $this->guestGroup = $this->occasion->guestGroups()
            ->where('invite_token', $this->token)
            ->with('guests')
            ->first();

        $this->attendees = $this->occasion->attendees()->where('guest_group_id', $this->guestGroup->id)->get();

        if($this->attendees->count() > 0) {
            $this->joinlist = $this->attendees->pluck('guest_id')->toArray();
        } else {
            $this->joinlist = $this->guestGroup->guests->pluck('id')->toArray();
        }
    }

    public function toggleJoin($guestId)
    {
        if (in_array($guestId, $this->joinlist)) {
            $this->joinlist = array_diff($this->joinlist, [$guestId]);
        } else {
            $this->joinlist[] = $guestId;
        }
    }

    public function join()
    {
        $this->loading = true;

        $this->occasion->attendees()->where('guest_group_id', $this->guestGroup->id)->delete();
        $this->occasion->attendees()->saveMany(
            collect($this->joinlist)->map(function ($guestId) {
                return new Attendee([
                    'guest_id' => $guestId,
                    'guest_group_id' => $this->guestGroup->id,
                ]);
            })
        );

        $this->loading = false;

        return redirect()->route('save-the-date.success', ['occasion' => $this->occasion, 'token' => $this->token]);
    }
}; ?>

<x-occasion.occasion-page :occasion="$occasion" class="py-16 min-h-screen flex flex-col justify-center items-center">
    <div class="w-full p-4 md:w-1/2 md:mx-auto">
        <p class="text-[#59684C] font-lora text-3xl leading-tight">
            {{ $occasion->name  }}<br>
            <span class="font-bold">{{ $occasion->date->format('d.m.Y') }}</span>
        </p>

        <p class="text-[#59684C] font-lora text-3xl leading-none mt-4">
            {{ $occasion->street ?? '' }} {{ $occasion->house_number ?? '' }}<br>
            {{ $occasion->zip ?? '' }} {{ $occasion->city ?? '' }} 
        </p>
    </div>

    <div class="w-full p-4 md:w-1/2 md:mx-auto">
        <h1 class="text-[#C07E72] font-lora mt-8 text-5xl md:text-8xl lg:text-[120px] bold uppercase leading-none">
            @if( $guestGroup->childGuests()->count() > 0)
                <span class="text-3xl">{{ __('Family') }}</span><br>
            @endif
            {{ $guestGroup->name }}
        </h1>

        <div class="flex flex-col gap-2 font-lora mt-8 text-xl text-[#59684C]">
            @foreach($guestGroup->guests as $guest)
                @php $joins = in_array($guest->id, $joinlist); @endphp
                <div class="flex items-center justify-between">
                    <p>{{ $guest->name }}</p>

                    @if($attendees->count() > 0)
                        @if($joins)
                            <x-icon.check class="w-5 h-5" />
                        @else
                            <x-icon.no-symbol class="w-5 h-5" />
                        @endif
                    @else
                        <button
                        class="px-2 py-1 border {{ $joins ? 'border-[#59684C] text-[#59684C]' : 'border-[#C07E72] text-[#C07E72]' }} rounded-md inline-flex items-center gap-2 justify-center 
                        w-auto transition-all duration-300 ease-in-out"
                        wire:click="toggleJoin({{ $guest->id }})"
                        >
                            @if($joins)
                                <x-icon.check class="w-5 h-5" />
                                {{__('Yes')}}
                            @else
                                <x-icon.no-symbol class="w-5 h-5" />
                                {{__('No')}}
                            @endif
                        </button>
                    @endif
                </div>
            @endforeach
        </div>

        @if($attendees->count() === 0)
            <div class="mt-8 md:mt-16 flex justify-center md:justify-end">
                <button class="font-lora hover:cursor-pointer w-full md:w-auto text-center text-xl bg-[#C07E72] text-[#BBC6B5] font-bold px-4 py-2 rounded-full border-2 tracking-wider border-[#C07E72]"
                        wire:click="join"
                        wire:loading.attr="disabled"
                        wire:loading.class="opacity-50 cursor-not-allowed">
                    {{ __('Save') }}
                </button>
            </div>
        @else
            <p class="text-[#59684C] font-lora text-sm mt-8">
                {{ __('You joined on', ['date' => $attendees->first()->created_at->format('d.m.Y')]) }}
            </p>
        @endif

    </div>
</x-occasion.occasion-page>
