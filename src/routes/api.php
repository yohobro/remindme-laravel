<?php

use App\Enums\AccessTokenEnum;
use App\Http\Controllers\Api\ReminderController;
use App\Http\Controllers\AuthController;
use App\Models\Reminder;
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

Route::middleware('auth:sanctum', 'ability:' . AccessTokenEnum::REFRESH_ACCESS_TOKEN->value)->group(function() {
    Route::put('/session', [AuthController::class, 'refreshToken']);
});


Route::middleware('auth:sanctum', 'ability:' . AccessTokenEnum::ACCESS_TOKEN->value)->group(function () {
    Route::post('/reminders', [ReminderController::class, 'store']);
    Route::get('/reminders/{id}', [ReminderController::class, 'show']);
    Route::put('/reminders/{reminder}', [ReminderController::class, 'update']);
    Route::delete('/reminders/{reminder}', [ReminderController::class, 'delete']);
    Route::get('/reminders/limit/{limit}', [ReminderController::class, 'index']);
    Route::post('/logout', [AuthController::class, 'logout']);
});

Route::post('/register', [AuthController::class, 'register']);
Route::post('/session', [AuthController::class, 'login']);
