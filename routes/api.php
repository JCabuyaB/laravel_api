<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\BookController;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::get('/', [BookController::class, 'index']);
Route::get('/book/{id}', [BookController::class, 'show'])->where('id', '[0-9]+');
Route::post('/book', [BookController::class, 'store']);
Route::put('/book/{id}', [BookController::class, 'update'])->where('id', '[0-9]+');
Route::delete('/book/{id}', [BookController::class, 'destroy'])->where('id', '[0-9]+');
