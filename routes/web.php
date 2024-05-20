<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\QuizController;
use App\Http\Controllers\Users;
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
Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
Route::post('/login/action', [AuthController::class, 'login'])->name('login.action');
Route::get('/logout', [AuthController::class, 'logout'])->name('logout');
Route::middleware(['auth'])->group(function () {
    Route::get('/dashboard', [Users::class, 'dashboard'])->name('dashboard');
    Route::get('/users', [Users::class, 'users'])->name('users');
    Route::get('/quiz', [Users::class, 'quiz'])->name('quiz');
    Route::get('/quiz/list', [Users::class, 'listQuiz'])->name('quiz.list');
    Route::get('/quiz/dropdown', [Users::class, 'quizDropDown'])->name('quiz.dropdown');
    Route::post('/quest/dropdown', [Users::class, 'questionDropdown'])->name('quest.dropdown');
    Route::get('/users/list', [Users::class, 'listUsers'])->name('users.list');
    Route::post('save/users', [Users::class, 'saveUser'])->name('save.users');
    Route::post('save/quiz', [Users::class, 'saveQuiz'])->name('save.quiz');
    Route::post('delete/quiz', [Users::class, 'deleteQuiz'])->name('delete.quiz');
    Route::post('/answer/ansDropdown', [Users::class, 'ansDropdown'])->name('answer.ansDropdown');
    Route::post('/onchange/quiz/data', [Users::class, 'quizData'])->name('onchange.quiz.data');
});
Route::get('/', [QuizController::class, 'index'])->name('index');
Route::get('/quiz/frontend/dropdown', [QuizController::class, 'quizDropDown'])->name('quiz.frontend.dropdown');
Route::post('/start/quiz', [QuizController::class, 'startQuiz'])->name('start.quiz');
Route::post('/submit/quiz', [QuizController::class, 'submitForm'])->name('submit.quiz');
Route::get('/quiz/overview/', [QuizController::class, 'submitOverview'])->name('quiz.overview');
Route::post('/quiz/overview/view', [QuizController::class, 'viewOverview'])->name('quiz.overview.view');






