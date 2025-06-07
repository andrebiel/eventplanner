@props(['occasion'])

<div {{ $attributes->merge(['class' => 'bg-[#BBC6B5] min-h-dvh']) }}>
    {{ $slot }}
</div>