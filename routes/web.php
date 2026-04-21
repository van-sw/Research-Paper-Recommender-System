<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PaperController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\SavedPaperController;

Route::get('/', [PaperController::class, 'index'])->name('papers.index');
Route::get('/papers/{id}', [PaperController::class, 'show'])->name('papers.show');

Route::get('/dashboard', [DashboardController::class, 'index'])
    ->name('dashboard');

Route::post('/save-paper', [SavedPaperController::class, 'store'])->name('papers.save');
Route::get('/saved', [SavedPaperController::class, 'index'])->name('papers.saved');
Route::delete('/save-paper/{id}', [SavedPaperController::class, 'destroy'])->name('papers.unsave');