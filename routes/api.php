<?php

use App\Http\Controllers\MainController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/


Route::prefix('auth')->group(function () {
    Route::post('/register', 'App\Http\Controllers\Api\Auth\AuthController@signup')->name('auth.signup');
    Route::post('/login', 'App\Http\Controllers\Api\Auth\AuthController@login')->name('auth.login');
    Route::post('/password/email', 'App\Http\Controllers\Api\Auth\AuthController@sendPasswordResetLinkEmail')->middleware('throttle:5,1')->name('password.email');
    Route::post('/password/reset', 'App\Http\Controllers\Api\Auth\AuthController@resetPassword')->name('password.reset');
});

// private routes
Route::prefix('auth')->middleware('auth:sanctum')->group(function () {
    Route::post('/logout', 'App\Http\Controllers\Api\Auth\AuthController@logout')->name('auth.logout');
    Route::get('user', 'App\Http\Controllers\Api\Auth\AuthController@getAuthenticatedUser')->name('auth.user');
});

// main routes
Route::prefix('v1')->group(function () {
    Route::get('/home', [MainController::class, 'dashboard']);

    // questions to be answered
    Route::get('/questions', [MainController::class, 'questions']);
    //get single question to be answered
    Route::get('/questions/{id}/single', [MainController::class, 'singleQuestion']);

    //answer question
    Route::post('/questions/answer', [MainController::class, 'answerQuestion']);
});
