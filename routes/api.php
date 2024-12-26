<?php
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route; 
use App\Http\Controllers\Api\AuthController; 
use App\Http\Controllers\Api\UserAuthController; 
use App\Http\Controllers\Api\JobCardController; 


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
    Route::get('/user-info', [AuthController::class, 'userinfo']);
    Route::post('/user-profile/update', [UserAuthController::class, 'updateProfile']); 
    Route::post('/search-vehicle', [JobCardController::class, 'searchVehicle']); 
    Route::post('/add-customer', [JobCardController::class, 'addCustomer']); 
    Route::get('/vehicle-type', [JobCardController::class, 'vehicleType']); 
    Route::get('/vehicle-brand', [JobCardController::class, 'vehicleBrand']); 
    Route::post('/vehicle-model', [JobCardController::class, 'vehicleModel']); 
    Route::get('/vehicle-fuel-type', [JobCardController::class, 'vehicleFuelType']); 
    Route::post('/search-accessories', [JobCardController::class, 'searchaccessories']); 
    Route::post('/add-accessories', [JobCardController::class, 'addaccessories']); 
    Route::post('/search-customer-voice', [JobCardController::class, 'searchCustomerVoice']); 
    Route::post('/add-customer-voice', [JobCardController::class, 'addCustomerVoice']); 
    Route::post('/search-work-notes', [JobCardController::class, 'searchWorkNotes']); 
    Route::post('/add-work-notes', [JobCardController::class, 'addWorkNotes']); 
    Route::post('/store-jobcard', [JobCardController::class, 'storeJobCard']); 
});