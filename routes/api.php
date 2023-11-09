<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProjectController;
use App\Http\Controllers\TaskController;
use Laravel\Passport\Passport;
use App\Http\Controllers\UserController;
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


Route::post('create-user', [UserController::class, 'register']);

Route::middleware('auth:api')->group(function () {
    Route::apiResource('projects', 'App\Http\Controllers\ProjectController');
    Route::apiResource('tasks', 'App\Http\Controllers\TaskController');
});

