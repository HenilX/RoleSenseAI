<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ResumeController;

Route::get('/', function () {
    return view('home');
});

Route::post('/upload', [ResumeController::class, 'uploadWeb'])->name('upload.web');
Route::post('/match', [ResumeController::class, 'matchWeb'])->name('match.web');
