<?php
use App\Http\Controllers\LocationController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\SearchController;
use App\Http\Controllers\UmkmController;
use Illuminate\Support\Facades\Route;


Route::get('/umkm/get/all',[UmkmController::class, 'getAllUmkm']);
Route::get('/umkm/get/{type}', [UmkmController::class, 'getUmkmByType']);
Route::get('/umkm/detail/{id}', [UmkmController::class,'getUmkmById']);
Route::post('/location/from/coordinates', [LocationController::class,'convertLocation']);
Route::get('/search/by/query', [SearchController::class,'searchWithQuery']);
Route::get('/search/by/keyword', [SearchController::class,'searchByKeyword']);
Route::get('/product/get/by/umkmId/{id}', [ProductController::class,'getProductsByUmkmId']);
