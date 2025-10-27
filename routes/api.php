<?php
use App\Http\Controllers\LocationController;
use App\Http\Controllers\UmkmController;
use Illuminate\Support\Facades\Route;


Route::get('/umkm/get/all',[UmkmController::class, 'getAllUmkm']);
Route::get('/umkm/get/{type}', [UmkmController::class, 'getUmkmByType']);
Route::post('/location/from/coordinates', [LocationController::class,'convertLocation']);
