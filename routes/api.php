<?php

use App\Http\Controllers\Api\AuthController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\SkillController;
use App\Http\Controllers\Api\BarterRequestController;
use App\Http\Controllers\Api\ChatController;
use App\Http\Controllers\Api\MessageController;
use App\Http\Controllers\Api\RatingController;
use App\Http\Controllers\Api\UserController;

// skill
Route::apiResource('skills', SkillController::class);

// barter request
Route::apiResource('barter-requests', BarterRequestController::class);

//post
Route::post('/barter', [BarterRequestController::class, 'store']);
//login/logout
Route::post('/login', [AuthController::class, 'login']);
Route::post('/logout', [AuthController::class, 'logout']);
//chat
Route::get('/chats/all', [ChatController::class, 'allChats']);
Route::post('/chats', [ChatController::class, 'store']);
Route::get('/chats', [ChatController::class, 'index']);
//message
Route::post('/messages', [MessageController::class, 'store']);
Route::post('/messages/upload-image', [MessageController::class, 'uploadImage']);
Route::get('/messages/{chat_id}', [MessageController::class, 'index']);
//rating
Route::post('/ratings', [RatingController::class, 'store']);
Route::get('/ratings', [RatingController::class, 'index']);
//user
Route::post('/users/{id}/upload-profile', [UserController::class, 'uploadProfileImage']);
Route::apiResource('users', UserController::class);