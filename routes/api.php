<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Middleware\AuthToken;
use App\Http\Controllers\CourseController;
use App\Http\Controllers\MeetingController;

Route::post('/login', [AuthController::class, 'login'])->withoutMiddleware(AuthToken::class);
Route::post('/signup', [AuthController::class, 'signup'])->withoutMiddleware(AuthToken::class);

Route::middleware(AuthToken::class)->group(function () {
    Route::get('/me', [AuthController::class, 'me']);
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::resource('/courses', CourseController::class);
    Route::resource('/courses/{course_id}/meetings', MeetingController::class);
});
