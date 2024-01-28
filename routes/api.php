<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\TaskController;
use App\Http\Controllers\Auth\LoginController;

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

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::post('/get-tasks/', [TaskController::class, 'index'])->name('task.undone');
Route::post('/tasks-completed/', [TaskController::class, 'getCompleted'])->name('task.completed');
Route::post('/tasks-all/', [TaskController::class, 'getAll'])->name('task.all');
//Route::post('/get-tasks/{token}', [TaskController::class, 'index'])->name('task.get');
Route::get('/tasks/{id}', [TaskController::class, 'show']);
Route::post('/tasks', [TaskController::class, 'store'])->name('task.create');
Route::put('/tasks/{id}', [TaskController::class, 'update']);
Route::delete('/tasks/{id}', [TaskController::class, 'destroy'])->name('task.delete');

Route::post('/register', [LoginController::class, 'register'])->name('register');
Route::post('/login', [LoginController::class, 'login'])->name('login');

Route::group([
    "middleware" => ["auth:sanctum"]
], function () {
    Route::post('/logout', [LoginController::class, 'logout'])
        ->middleware('auth:api')
        ->name('logout');
});
