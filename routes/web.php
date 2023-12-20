<?php

use App\Http\Controllers\CategoryController;
use App\Http\Controllers\LocationController;
use App\Http\Controllers\PolygonMarkerController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\SearchController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

// Route::get('/', function () {
//     return view('welcome');
// });


Route::resource('/categories',CategoryController::class);
Route::get('/search',[SearchController::class,'search'])->name('search');

Route::get('/getAllCategories', [CategoryController::class, 'getAllCategories'])->name('getAllCategories');

Route::get('/polygon',[PolygonMarkerController::class,'index']);

Route::get('/location',[LocationController::class,'index'])->name('location');
Route::get('/all-location',[LocationController::class,'allLocation']);
Route::post('/location',[LocationController::class,'store'])->name('postLocation');
Route::post('/location/{id}',[LocationController::class,'update'])->name('updateLocation');
Route::delete('/location/{id}',[LocationController::class,'destroy'])->name('destroyLocation');
Route::get('/location/{id}',[LocationController::class,'show'])->name('showLocation');
