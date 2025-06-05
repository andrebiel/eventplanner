<?php

use Livewire\Volt\Component;
use Livewire\Attributes\Layout;
use App\Models\Occasion;
use Livewire\Attributes\Title;

new 
#[Layout('components.layouts.standalone-page')]
#[Title('Save the Date')]
class extends Component {
    public Occasion $occasion;
    public string $token;

}; ?>

<x-occasion.occasion-page class="flex flex-col items-center justify-center bg-[#BBC6B5] min-h-screen" :occasion="$occasion">
    <div>
        <p class="text-[#C07E72] font-lora text-8xl lg:text-[150px] uppercase text-center leading-none">
            Save<br>our<br>date
        </p>

        <h1 class="font-dancing text-5xl lg:text-[100px] text-[#59684C] my-16 text-center">
            Johanna & André
        </h1>

        <p class="font-lora text-[#59684C] text-5xl lg:text-[60px] text-center leading-none">
            {{ $occasion->date->format('d.m.Y') }}
        </p>
    </div>

    <div class="mt-16 p-4 text-center">
        <p class="text-md font-lora text-[#59684C]">
            {{ __('YouAlreadyKnowJoinText') }}.
        </p>

        <div class="mt-8 flex justify-center">
            <a href="{{ route('save-the-date.join', ['occasion' => $occasion, 'token' => $token]) }}" 
                class="font-lora text-xl bg-[#C07E72] text-[#BBC6B5] font-bold px-4 py-2 rounded-full border-2 tracking-wider border-[#C07E72] w-full md:w-auto">
                {{ __('Join') }}
            </a>
        </div>
    </div>
</x-occasion.occasion-page>

