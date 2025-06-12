<?php

use Illuminate\Support\Facades\Route;
use Livewire\Volt\Volt;

Route::get('/', function () {
    return view('welcome');
})->name('home');

Route::view('dashboard', 'dashboard')
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

Route::middleware(['auth'])->group(function () {
    Route::redirect('settings', 'settings/profile');

    Volt::route('settings/profile', 'settings.profile')->name('settings.profile');
    Volt::route('settings/password', 'settings.password')->name('settings.password');
    Volt::route('settings/appearance', 'settings.appearance')->name('settings.appearance');

    Volt::route('occasions/{occasion}/dashboard', 'occasions.page-dashboard')->name('occasions.page-dashboard');
    Volt::route('occasions/{occasion}/guests', 'occasions.page-guests')->name('occasions.page-guests');
    Volt::route('occasions/{occasion}/save-the-date', 'occasions.page-save-the-date')->name('occasions.page-save-the-date');
    Volt::route('occasions/{occasion}/attendees', 'occasions.page-attendees')->name('occasions.page-attendees');
});

Volt::route('save-the-date/{occasion}/{token}', 'save-the-date.page-info')->name('save-the-date.detail');
Volt::route('save-the-date/{occasion}/join/{token}', 'save-the-date.page-join')->name('save-the-date.join');
Volt::route('save-the-date/{occasion}/join/{token}/success', 'save-the-date.page-success')->name('save-the-date.success');

require __DIR__ . '/auth.php';
