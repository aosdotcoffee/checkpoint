<?php

use App\Http\Controllers\ServerlistController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/serverlist.json', [ServerlistController::class, 'index'])
    ->name('serverlist.list');
