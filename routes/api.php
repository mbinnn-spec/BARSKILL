<?php

use App\Http\Controllers\Api\AuthController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\SkillController;
use App\Http\Controllers\Api\BarterRequestController;
use App\Http\Controllers\Api\ChatController;
use App\Http\Controllers\Api\MessageController;
use App\Http\Controllers\Api\RatingController;

// skill
Route::apiResource('skills', SkillController::class);

// barter request
Route::apiResource('barter-requests', BarterRequestController::class);
//post
Route::post('/barter', [BarterRequestController::class, 'store']);
//read
Route::apiResource('barter-requests', BarterRequestController::class);
//login/logout
Route::post('/login', [AuthController::class, 'login']);
Route::post('/logout', [AuthController::class, 'logout']);
//chat
Route::post('/chats', [ChatController::class, 'store']);
Route::get('/chats', [ChatController::class, 'index']);
//message
Route::post('/messages', [MessageController::class, 'store']);
Route::get('/messages/{chat_id}', [MessageController::class, 'index']);
//routes
Route::post('/ratings', [RatingController::class, 'store']);
Route::get('/ratings', [RatingController::class, 'index']);