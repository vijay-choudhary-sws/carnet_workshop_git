<?php
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route; 
use App\Http\Controllers\Api\AuthController; 
use App\Http\Controllers\Api\UserAuthController; 


Route::controller(UserAuthController::class)->group(function(){
    Route::post('user/signup', 'signup');
    Route::post('user/verify-otp', 'verifyOtp');
    Route::post('user/login-otp', 'login');
    Route::post('user/login', 'resendOtp'); 
    Route::get('user/countries', 'countries'); 
    Route::post('user/state', 'state'); 
    Route::post('user/cities', 'cities'); 
    
}); 
 
Route::middleware('auth:sanctum')->group(function () { 
    Route::get('/user-info', [AuthController::class, 'userinfo'])->name('user-info');
    Route::post('/user-profile/update', [UserAuthController::class, 'updateProfile']); 
});