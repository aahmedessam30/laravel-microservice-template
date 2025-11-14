<?php

use App\Http\Controllers\ApiDocumentationController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

// API Documentation Routes
Route::get('/docs/{version?}', [ApiDocumentationController::class, 'docs'])
    ->name('api.docs')
    ->where('version', 'v[0-9]+');

Route::get('/openapi/{version?}.json', [ApiDocumentationController::class, 'json'])
    ->name('api.openapi')
    ->where('version', 'v[0-9]+');