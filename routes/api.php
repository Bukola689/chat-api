<?php

use Illuminate\Http\Request;
use App\Http\Controllers\Auth\AuthenticationController;
use App\Http\Controllers\Auth\ForgotPasswordController;
use App\Http\Controllers\Auth\ResetPasswordController;
use App\Http\Controllers\Auth\VerifyEmailController;
use App\Http\Controllers\ConversationController;
use App\Http\Controllers\MessageController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

     Route::group(['prefix'=> 'auth'], function() {
            Route::post('register', [AuthenticationController::class, 'register']);
            Route::post('login', [AuthenticationController::class, 'login']);
            Route::post('forgot-password', [ForgotPasswordController::class, 'forgotPassword']);
          Route::group(['middleware' => 'auth:sanctum'], function() {
            Route::get('user', [AuthenticationController::class, 'user']);
            Route::post('logout', [AuthenticationController::class, 'logout']);
            Route::post('/email/verification-notification', [VerifyEmailController::class, 'resendNotification'])->name('verification.send');
            Route::post('reset-password', [ResetPasswordController::class, 'resetPassword']);
            
             Route::get('/conversations', [ConversationController::class, 'index']);
             Route::post('/conversations/find-or-create', [ConversationController::class, 'findOrCreate']);

             Route::get('/conversations/{conversation}/messages', [MessageController::class, 'index']);
             Route::post('/conversations/{conversation}/messages', [MessageController::class, 'store']);
         });
     });
