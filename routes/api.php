<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Middleware\AuthToken;
use App\Http\Controllers\CourseController;

// Route::get('/user', function (Request $request) {
//     return $request->user();
// })->middleware('auth:sanctum');

Route::post('/login', [AuthController::class, 'login'])->withoutMiddleware(AuthToken::class);
Route::post('/signup', [AuthController::class, 'signup'])->withoutMiddleware(AuthToken::class);

Route::middleware(AuthToken::class)->group(function () {
    Route::get('/me', [AuthController::class, 'me']);
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::resource('/courses', CourseController::class);
});
