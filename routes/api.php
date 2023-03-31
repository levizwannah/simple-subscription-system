<?php

use App\Http\Controllers\SubscriptionController;
use App\Http\Controllers\WebsiteController;
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

Route::post('/website', [WebsiteController::class, 'store']);

Route::controller(PostController::class)->prefix('posts')
->group(function(){
    Route::post('/', 'store');
    Route::post('{postId}/status', 'status');
});

Route::controller(SubscriptionController::class)->prefix('subscription')
->group(function(){
    Route::post('/{userId}/subscribe/{websiteId}', 'subscribe');
});