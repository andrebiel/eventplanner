@props([
    'title',
    'description',
    'class' => '',
])

<div class="flex justify-between mb-16 {{ $class }}">
    <div>
        <flux:heading size="lg">{{ $title }}</flux:heading>
        <flux:text class="mt-2">{{ $description }}</flux:text>
    </div>

    @if ($slot->isNotEmpty())
        <div class="flex justify-end">
            {{ $slot }}
        </div>
    @endif
</div>