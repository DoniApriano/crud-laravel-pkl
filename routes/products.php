<?php

use App\Http\Controllers\ProductController;
use Illuminate\Support\Facades\Route;

// Route::resource('/products',ProductController::class);
Route::get('/products',[ProductController::class,'index'])->name('products.index');
Route::post('/products',[ProductController::class,'store'])->name('products.store');
Route::get('/products/{id}',[ProductController::class,'show'])->name('products.show');
Route::post('/products/{id}',[ProductController::class,'update'])->name('products.update');
Route::delete('/products/{id}',[ProductController::class,'destroy'])->name('products.destroy');
