<?php

use Livewire\Volt\Component;
use Livewire\Attributes\Layout;
use App\Models\Occasion;
use Livewire\Attributes\Title;

new 
#[Layout('components.layouts.standalone-page')]
#[Title('Success')]
class extends Component {
    public Occasion $occasion;
    public string $token;

}; ?>

<x-occasion.occasion-page class="flex flex-col items-center justify-center bg-[#BBC6B5] min-h-screen" :occasion="$occasion">
        <p class="text-[#C07E72] font-lora text-8xl lg:text-[150px] uppercase text-center leading-none">
            {{__('You') }}<br>{{__('are') }}<br>{{__('in') }}
        </p>
        
        <p class="font-lora text-[#59684C] text-5xl lg:text-[60px] text-center leading-none my-16">
            {{ __('We are looking forward to seeing you') }}!
        </p>

        <h1 class="font-dancing text-5xl lg:text-[100px] text-[#59684C] text-center">
            Johanna & André
        </h1>
</x-occasion.occasion-page>

