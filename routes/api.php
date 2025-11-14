<?php

use App\Http\Controllers\Api\HealthController;
use App\Http\Controllers\Api\VersionController;
use Illuminate\Support\Facades\Route;

// Health and version endpoints
Route::get('/health', [HealthController::class, 'index'])->name('health');
Route::get('/version', [VersionController::class, 'index'])->name('version');

// Version 1
Route::prefix('v1')->group(base_path('routes/api_v1.php'));