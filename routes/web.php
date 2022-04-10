<?php

use App\Http\Controllers\Web\AdminMainController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('index');
});

Route::get('/questions', [AdminMainController::class, 'questions'])->name('questions');

Route::post('/store/question', [AdminMainController::class, 'storeQuestion'])->name('store.question');

Route::post('/update/question/{id}', [AdminMainController::class, 'updateQuestion'])->name('update.question');


Route::get('/answers', [AdminMainController::class, 'answers'])->name('answers');

Route::post('store/answers', [AdminMainController::class, 'storeAnswer'])->name('store.answer');

Route::post('update/answer/{id}', [AdminMainController::class, 'updateAnswer'])->name('update.answer');

Route::post('/update/assign/answer', [AdminMainController::class, 'addQuestionAnswer'])->name('update.assign-answer-question');


Route::get('delete/{table}/{id}', [AdminMainController::class, 'destroy'])->name('delete');

