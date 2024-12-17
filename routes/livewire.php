<?php

use Illuminate\Support\Facades\Route;

// Web routes without the default namespace
Route::get('/custom', function () {
    return 'This is a custom route without the namespace';
});

Route::get('/job-cards', \App\Http\Livewire\JobCards::class)->name('job-cards');
Route::get('/job-card/create', \App\Http\Livewire\JobCardCreate::class)->name('job-card-create');