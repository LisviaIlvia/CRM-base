<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ZoomController;

Route::get('/zoom/token', [ZoomController::class, 'getZoomToken']);
Route::get('/zoom/user', [ZoomController::class, 'getUserId']);
Route::post('/zoom/meetings', [ZoomController::class, 'createMeeting']);
Route::get('/zoom/meetings', [ZoomController::class, 'getMeetings']);
Route::post('/zoom/meetings/{meetingId}/add-participant', [ZoomController::class, 'addParticipant']);
