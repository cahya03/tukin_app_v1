<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\TukinController;
use App\Http\Controllers\HeaderController;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

Route::get('/tukin', [TukinController::class, 'index'])
    ->middleware(['auth', 'verified'])
    ->name('tukin');

Route::get('/tukin/create', [TukinController::class, 'create_tukin'])
    ->middleware(['auth', 'verified'])
    ->name('tukin-create');

Route::post('/tukin/create/import_tni', [TukinController::class, 'import_excel_tni'])
    ->middleware(['auth', 'verified'])
    ->name('tukin.import_tni');

Route::get('/header', [HeaderController::class, 'index'])
    ->middleware(['auth', 'verified'])
    ->name('header');
    
Route::post('/header/store', [HeaderController::class, 'store'])
    ->middleware(['auth', 'verified'])
    ->name('header.store'); 
require __DIR__.'/auth.php';
