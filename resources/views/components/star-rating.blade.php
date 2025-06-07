@props(['rating'])

@php
    $scaledRating = round($rating * 3);
@endphp

<div class="flex items-center">
    @for ($i = 1; $i <= 3; $i++)
        <flux:icon name="star"
        variant="{{ $i <= $scaledRating ? 'solid' : 'outline' }}"
        />
    @endfor
</div>