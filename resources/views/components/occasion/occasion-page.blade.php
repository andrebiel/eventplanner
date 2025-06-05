@props(['occasion'])

<div {{ $attributes->merge(['class' => 'bg-[#BBC6B5] min-h-screen']) }}>
    {{ $slot }}
</div>