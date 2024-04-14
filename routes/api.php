<?php

use App\Http\Controllers\FileController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:api');


Route::get('files', [FileController::class, 'index']);
Route::post('files', [FileController::class, 'store']);
Route::delete('files/{id}', [FileController::class, 'destroy']);
