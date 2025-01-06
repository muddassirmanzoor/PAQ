<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\LoginController;

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

Route::get('/', [LoginController::class, 'login'])->name('login');
Route::get('/login', [LoginController::class, 'login']);
Route::get('logout', [LoginController::class, 'logout']);
Route::post('/login', [LoginController::class, 'checkLogin']);
//
//
//Route::get('dashboard', [\App\Http\Controllers\DashboardController::class, 'dashboard']);


Route::middleware(['auth'])->group(function () {
    Route::get('assembly-question', [\App\Http\Controllers\AssemblyQuestionController::class, 'assemblyQuestion']);
    Route::get('assembly-question-list', [\App\Http\Controllers\AssemblyQuestionController::class, 'assemblyQuestionList']);
    Route::post('assembly-question-list', [\App\Http\Controllers\AssemblyQuestionController::class, 'filterAssemblyQuestionList']);
    Route::get('archived-assembly-question-list', [\App\Http\Controllers\AssemblyQuestionController::class, 'archivedAssemblyQuestionList']);
    Route::post('archived-assembly-question-list', [\App\Http\Controllers\AssemblyQuestionController::class, 'filterArchivedAssemblyQuestionList']);
    Route::post('assembly-question', [\App\Http\Controllers\AssemblyQuestionController::class, 'saveAssemblyQuestion']);
    Route::put('update-assembly-question', [\App\Http\Controllers\AssemblyQuestionController::class, 'updateAssemblyQuestion']);
    Route::get('accept-assembly-question/{question_id}', [\App\Http\Controllers\AssemblyQuestionController::class, 'acceptAssemblyQuestion']);
    Route::get('accepted-assembly-question-list', [\App\Http\Controllers\AssemblyQuestionController::class, 'acceptedAssemblyQuestionList']);
    Route::get('forwarded-assembly-question-list', [\App\Http\Controllers\AssemblyQuestionController::class, 'forwardedAssemblyQuestionList']);
    Route::get('completed-assembly-question-list', [\App\Http\Controllers\AssemblyQuestionController::class, 'completedAssemblyQuestionList']);
    Route::get('get-users-by-department/{department_id}', [\App\Http\Controllers\AssemblyQuestionController::class, 'getUsersByDepartment']);
    Route::post('assembly-question-track', [\App\Http\Controllers\AssemblyQuestionController::class, 'saveAssemblyQuestionTrack']);
    Route::get('forward-assembly-question/{question_id}', [\App\Http\Controllers\AssemblyQuestionController::class, 'forwardAssemblyQuestion']);
    Route::get('edit-assembly-question/{question_id}', [\App\Http\Controllers\AssemblyQuestionController::class, 'editAssemblyQuestion']);
    Route::any('dashboard', [\App\Http\Controllers\AssemblyQuestionController::class, 'dashboard']);

});



