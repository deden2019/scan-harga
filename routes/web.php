<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ScanController;

Route::get('/', function () {
    return view('welcome');
});



Route::get('/', [ScanController::class, 'index']);
Route::post('/scan', [ScanController::class, 'scan']);
