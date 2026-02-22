<?php

use App\Http\Controllers\CloneController;
use App\Http\Controllers\CrossController;
use App\Http\Controllers\HomeController;
use Illuminate\Support\Facades\Route;

Route::get('/', HomeController::class)->name('home');

Route::get('/crosses', [CrossController::class, 'index'])->name('crosses.index');
Route::get('/crosses/{cross:slug}', [CrossController::class, 'show'])->name('crosses.show');

Route::get('/clones', [CloneController::class, 'index'])->name('clones.index');
Route::get('/clones/{cactusClone:slug}', [CloneController::class, 'show'])->name('clones.show');
