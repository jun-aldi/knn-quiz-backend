<?php

use App\Http\Controllers\API\AnswerController;
use App\Http\Controllers\API\QuestionController;
use App\Http\Controllers\API\ChoiceController;
use App\Http\Controllers\API\CategoryController;
use App\Http\Controllers\API\QuizController;
use App\Http\Controllers\API\StudentController;
use App\Http\Controllers\API\UserController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

// // Student API for student
// Route::prefix('student')->middleware('auth:sanctum')->name('student.')->group(function () {
//     Route::post('', [StudentController::class, 'create'])->name('create');
//     Route::get('', [StudentController::class, 'fetch'])->name('fetch');
//     Route::post('update/{id}', [StudentController::class, 'update'])->name('update');
//     Route::delete('{id}', [StudentController::class, 'destroy'])->name('delete');
// });

// Student API
Route::prefix('student')->middleware('auth:sanctum')->name('student.')->group(function () {
    Route::post('', [StudentController::class, 'create'])->name('create');
    Route::get('', [StudentController::class, 'fetch'])->name('fetch');
    Route::post('update/{id}', [StudentController::class, 'update'])->name('update');
    Route::delete('{id}', [StudentController::class, 'destroy'])->name('delete');
});

// Quiz API only for admin
Route::prefix('quiz')->middleware('auth:sanctum', 'admin')->name('quiz.')->group(function () {
    Route::post('', [QuizController::class, 'create'])->name('create');
    Route::get('', [QuizController::class, 'fetch'])->name('fetch');
    Route::post('update/{id}', [QuizController::class, 'update'])->name('update');
    Route::delete('{id}', [QuizController::class, 'destroy'])->name('delete');
});

// Question API for student
Route::prefix('question')->middleware('auth:sanctum')->name('question.')->group(function () {
    Route::get('', [QuestionController::class, 'fetch'])->name('fetch');
});
// Choice API for student
Route::prefix('choice')->middleware('auth:sanctum')->name('choice.')->group(function () {
    Route::get('', [ChoiceController::class, 'fetch'])->name('fetch');
});
// Category API for student
Route::prefix('category')->middleware('auth:sanctum')->name('category.')->group(function () {
    Route::get('', [CategoryController::class, 'fetch'])->name('fetch');
});

// Question API for admin roles
Route::prefix('question')->middleware('auth:sanctum', 'admin')->name('question.')->group(function () {
    Route::post('', [QuestionController::class, 'create'])->name('create');
    Route::post('update/{id}', [QuestionController::class, 'update'])->name('update');
    Route::delete('{id}', [QuestionController::class, 'destroy'])->name('delete');
});

// Choice API for admin roles
Route::prefix('choice')->middleware('auth:sanctum', 'admin')->name('choice.')->group(function () {
    Route::post('', [ChoiceController::class, 'create'])->name('create');
    Route::post('update/{id}', [ChoiceController::class, 'update'])->name('update');
    Route::delete('{id}', [ChoiceController::class, 'destroy'])->name('delete');
});

// Category API for admin roles
Route::prefix('category')->middleware('auth:sanctum')->name('category.')->group(function () {
    Route::post('', [CategoryController::class, 'create'])->name('create');
    Route::post('update/{id}', [CategoryController::class, 'update'])->name('update');
    Route::delete('{id}', [CategoryController::class, 'destroy'])->name('delete');
});

// Answer API for student
Route::prefix('answer')->middleware('auth:sanctum')->name('answer.')->group(function () {
    Route::post('', [AnswerController::class, 'create'])->name('create');
    Route::get('', [AnswerController::class, 'fetch'])->name('fetch');
});

// Answer API for admin
Route::prefix('answer')->middleware('auth:sanctum', 'admin')->name('answer.')->group(function () {
    Route::post('update/{id}', [AnswerController::class, 'update'])->name('update');
    Route::delete('{id}', [AnswerController::class, 'destroy'])->name('delete');
});

// Auth API
Route::name('auth.')->group(function () {
    Route::post('login', [UserController::class, 'login'])->name('login');
    Route::post('register', [UserController::class, 'register'])->name('register');
    Route::middleware(['auth:sanctum'])->group(function () {
        //logout karena ambil token login pakai middleware
        Route::post('logout', [UserController::class, 'logout'])->name('logout');
        //fetch harus login dl
        Route::get('user', [UserController::class, 'fetch'])->name('user');
    });

    //fetch user for admin roles
    Route::middleware(['auth:sanctum', 'admin'])->group(function () {
        Route::get('getuser', [UserController::class, 'getUser'])->name('getuser');
    });
});
