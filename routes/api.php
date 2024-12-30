<?php

use App\Http\Controllers\ServerlistController;
use Illuminate\Support\Facades\Route;

Route::get('/serverlist.json', [ServerlistController::class, 'index'])
    ->name('serverlist.list');
