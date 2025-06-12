<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="dark">

<head>
    @include('partials.head')
</head>

<body class="min-h-screen bg-white dark:bg-zinc-800">
    <flux:sidebar sticky stashable class="border-e border-zinc-200 bg-zinc-50 dark:border-zinc-700 dark:bg-zinc-900">
        <flux:sidebar.toggle class="lg:hidden" icon="x-mark" />

        @if (auth()->user()->occasions->count() > 0)
            <livewire:layout.occasion-select />
        @endif

        @if (!empty(auth()->user()->currentOccasion))
            <flux:navlist variant="outline">
                <flux:navlist.item icon="arrow-trending-up" :href="route('occasions.page-dashboard', auth()->user()->currentOccasion)"
                    :current="request()->routeIs('occasions.page-dashboard')" wire:navigate>{{ __('Dashboard') }}
                </flux:navlist.item>

                <flux:navlist.item icon="user-group" :href="route('occasions.page-guests', auth()->user()->currentOccasion)"
                    :current="request()->routeIs('occasions.page-guests')" wire:navigate>{{ __('Guestlist') }}
                </flux:navlist.item>

                <flux:navlist.item icon="calendar" :href="route('occasions.page-save-the-date', auth()->user()->currentOccasion)"
                    :current="request()->routeIs('occasions.page-save-the-date')" wire:navigate>{{ __('Save the Date') }}
                </flux:navlist.item>

                <flux:navlist.item icon="document-check" :href="route('occasions.page-attendees', auth()->user()->currentOccasion)"
                    :current="request()->routeIs('occasions.page-attendees')" wire:navigate>{{ __('Attendees') }}
                </flux:navlist.item>
            </flux:navlist>
        @endif

        <flux:spacer />

        <!-- Desktop User Menu -->
        <flux:dropdown position="bottom" align="start">
            @php
                $userInitials = auth()->user()->initials();
                $userName = auth()->user()->name;
                $userEmail = auth()->user()->email;
            @endphp

            <flux:profile :name="$userName" :initials="$userInitials" icon-trailing="chevrons-up-down" />

            <flux:menu class="w-[220px]">
                <flux:menu.radio.group>
                    <div class="p-0 text-sm font-normal">
                        <div class="flex items-center gap-2 px-1 py-1.5 text-start text-sm">
                            <span class="relative flex h-8 w-8 shrink-0 overflow-hidden rounded-lg">
                                <span
                                    class="flex h-full w-full items-center justify-center rounded-lg bg-neutral-200 text-black dark:bg-neutral-700 dark:text-white">
                                    {{ $userInitials }}
                                </span>
                            </span>

                            <div class="grid flex-1 text-start text-sm leading-tight">
                                <span class="truncate font-semibold">{{ $userName }}</span>
                                <span class="truncate text-xs">{{ $userEmail }}</span>
                            </div>
                        </div>
                    </div>
                </flux:menu.radio.group>

                <flux:menu.separator />

                <flux:menu.radio.group>
                    <flux:menu.item :href="route('settings.profile')" icon="cog" wire:navigate>{{ __('Settings') }}
                    </flux:menu.item>
                </flux:menu.radio.group>

                <flux:menu.separator />

                <form method="POST" action="{{ route('logout') }}" class="w-full">
                    @csrf
                    <flux:menu.item as="button" type="submit" icon="arrow-right-start-on-rectangle" class="w-full">
                        {{ __('Log Out') }}
                    </flux:menu.item>
                </form>
            </flux:menu>
        </flux:dropdown>
    </flux:sidebar>

    <!-- Mobile User Menu -->
    <flux:header class="lg:hidden">
        <flux:sidebar.toggle class="lg:hidden" icon="bars-2" inset="left" />

        <flux:spacer />

        <flux:dropdown position="top" align="end">
            <flux:profile :initials="$userInitials" icon-trailing="chevron-down" />

            <flux:menu>
                <flux:menu.radio.group>
                    <div class="p-0 text-sm font-normal">
                        <div class="flex items-center gap-2 px-1 py-1.5 text-start text-sm">
                            <span class="relative flex h-8 w-8 shrink-0 overflow-hidden rounded-lg">
                                <span
                                    class="flex h-full w-full items-center justify-center rounded-lg bg-neutral-200 text-black dark:bg-neutral-700 dark:text-white">
                                    {{ $userInitials }}
                                </span>
                            </span>

                            <div class="grid flex-1 text-start text-sm leading-tight">
                                <span class="truncate font-semibold">{{ $userName }}</span>
                                <span class="truncate text-xs">{{ $userEmail }}</span>
                            </div>
                        </div>
                    </div>
                </flux:menu.radio.group>

                <flux:menu.separator />

                <flux:menu.radio.group>
                    <flux:menu.item :href="route('settings.profile')" icon="cog" wire:navigate>{{ __('Settings') }}
                    </flux:menu.item>
                </flux:menu.radio.group>

                <flux:menu.separator />

                <form method="POST" action="{{ route('logout') }}" class="w-full">
                    @csrf
                    <flux:menu.item as="button" type="submit" icon="arrow-right-start-on-rectangle" class="w-full">
                        {{ __('Log Out') }}
                    </flux:menu.item>
                </form>
            </flux:menu>
        </flux:dropdown>
    </flux:header>

    {{ $slot }}

    @fluxScripts

    <flux:toast />
    @persist('toast')
        <flux:toast />
    @endpersist
</body>

</html>
