<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\ChatController;

Route::post('/chat/send', [ChatController::class, 'send']);
Route::get('/chat/history', [ChatController::class, 'history']);
Route::get('/chat/{id}', [ChatController::class, 'show']);


