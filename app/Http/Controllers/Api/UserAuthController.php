<?php
namespace App\Http\Controllers\Api;

use App\Models\Companie;
use Illuminate\Http\Request;
use App\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;
use Twilio\Rest\Client;
use DB;

class UserAuthController extends BaseController
{
    public function signup(Request $request)
    {
        //echo "<pre>";print_r($request->all());die;
        $validator = Validator::make($request->all(), [
            'name' => 'required|string',
            'lastname' => 'required|string',
            'mobile' => 'required|regex:/^([0-9\s\-\+\(\)]*)$/|min:10|unique:users,mobile_no',
            'email' => 'required|unique:users,email', 
            'birth_date' => 'required',
            'address' => 'required',
            'gender' => 'required',
            'password' => 'required|string|min:8|confirmed', 
        ]);

        if ($validator->fails()) {
            $errors = collect($validator->errors())->map(function ($error) {
                return $error[0]; // Only return the first error
            });
            return $this->sendResponse(null,'Validation Error.', $errors ,200,false); 
        }

        $otp = rand(1000, 9999); 

        $user = new User;
        $user->name = $request->name;
        $user->lastname = $request->lastname; 
        $user->gender = $request->gender;
        $user->birth_date = $request->birth_date;
        $user->email = $request->email;
        $user->password = Hash::make($request->password);
        $user->mobile_no = $request->mobile; 
        $user->address = $request->address;
        $user->country_id = $request->country_id;
        $user->state_id = $request->state_id;
        $user->city_id = $request->city_id;
        $user->otp = $otp; 
        $user->otp_expiry =Carbon::now()->addMinutes(10); 
        $user->save();
 
        // $this->sendOtp($user->mobile_no, $otp);

        return $this->sendResponse(['otp'=>$otp], 'User created successfully. Please verify OTP.', null ,200);
    }

 
    
    public function verifyOtp(Request $request)
    { 
        $validator = Validator::make($request->all(), [
            'mobile' => 'required',
            'otp' => 'required',
        ]);
        if ($validator->fails()) {
            $errors = collect($validator->errors())->map(function ($error) {
                return $error[0]; // Only return the first error
            });
            return $this->sendResponse(null,'Validation Error.', $errors ,200,false); 
        }

        $user = User::where('mobile_no', $request->mobile)->where('otp', $request->otp)->first();

        // echo "<pre>";print_r($user);die;
        if (!$user) {
            return $this->sendResponse(null,'Validation Error.',['otp'=>'Invalid OTP or mobile number.'] ,200,false);
        }

        if (Carbon::now()->gt($user->otp_expiry)) {
            return $this->sendResponse(null,'Validation Error.',['otp'=>'OTP expired.'] ,200,false);
        }

        $user->otp = null;
        $user->otp_expiry = null;
        $user->save();

        $success['token'] =  $user->createToken('admin-api')->plainTextToken;
        $success['user'] =  $user;
        return $this->sendResponse($success,'User login successfully.',null ,200); 
    }

    public function resendOtp(Request $request)
    {
        $validator = Validator::make($request->all(), [ 
            'mobile' => 'required|exists:users,mobile_no',
        ]);

          if ($validator->fails()) {
            $errors = collect($validator->errors())->map(function ($error) {
                return $error[0]; // Only return the first error
            });
            return $this->sendResponse(null,'Validation Error.', $errors ,200,false); 
        }

        $user = User::where('mobile_no', $request->mobile)->first();
        $otp = rand(1000, 9999);

        $user->otp = $otp;
        $user->otp_expiry = Carbon::now()->addMinutes(10);
        $user->save();

        // Send OTP to user via SMS (implement your own method)
        // $this->sendOtp($user->mobile, $otp);

        return $this->sendResponse(['otp' => $otp],'OTP sent successfully.',null ,200);
    }

    public function login(Request $request)
    {
        $validator = Validator::make($request->all(), [ 
            'mobile' => 'required',
            'otp' => 'required', 
        ]);

        if ($validator->fails()) {
            $errors = collect($validator->errors())->map(function ($error) {
                return $error[0]; // Only return the first error
            });
            return $this->sendResponse(null,'Validation Error.', $errors ,200,false); 
        }

        // Attempt to find the user with the provided mobile number
        $user = User::where('mobile_no', $request->mobile)->where('otp',$request->otp)->first();

        // Check if user exists and password matches
        if (!$user) {
            return $this->sendResponse(null,'Validation Error.', ['error'=>'Invalid mobile or otp.'] ,200,false);
        }

        $user->tokens()->delete();

        $success['token'] =  $user->createToken('auth_token')->plainTextToken;
        $success['user'] =  $user;
        
        
        return $this->sendResponse($success,'User login successfully.', null ,200);
    } 
    
    public function updateProfile(Request $request)
    { 
        $userA = $request->user();

        $validator = Validator::make($request->all(), [ 
            'name' => 'required|string',
            'lastname' => 'required|string',
            'mobile' => 'sometimes|string|max:15|unique:users,mobile_no,' . $userA->id,
            'email' => 'sometimes|email|unique:users,email,' . $userA->id,
            'birth_date' => 'required',
            'address' => 'required',
            'gender' => 'required',
            'password' => 'required|string|min:8|confirmed',  
        ]);

        if ($validator->fails()) {
            $errors = collect($validator->errors())->map(function ($error) {
                return $error[0]; // Only return the first error
            });
            return $this->sendResponse(null,'Validation Error.', $errors ,200,false); 
        }

        $user = User::where('id', $userA->id)->first();

        if ($request->filled('current_password')) {
            if (!Hash::check($request->current_password, $user->password)) {
                throw ValidationException::withMessages([
                    'current_password' => ['The current password is incorrect.'],
                ]);
            }

            $user->password = Hash::make($request->password);
        }
 


        if ($request->filled('name')) {
            $user->name = $request->name;
        }
        
        if ($request->filled('lastname')) {
            $user->lastname = $request->lastname;
        }
 
        if ($request->filled('gender')) {
            $user->gender = $request->gender;
        }

         if ($request->filled('birth_date')) {
            $user->birth_date = $request->birth_date;
        } 

        if ($request->filled('email')) {
            $user->email = $request->email;
        }

        if ($request->filled('mobile')) {
            $user->mobile_no = $request->mobile;
        }

        if ($request->filled('address')) {
            $user->address = $request->address;
        }

        if ($request->filled('country_id')) {
            $user->country_id = $request->country_id;
        }
       
        if ($request->filled('state_id')) {
            $user->state_id = $request->state_id;
        }

        if ($request->filled('city_id')) {
            $user->city_id = $request->city_id;
        }

        $user->save(); 
         
        
        return $this->sendResponse($user,'Profile updated successfully.', null ,200); 
    }

    private function sendOtp($mobileNumber, $otp)
    { 

        $twilio = new Client(env('TWILIO_SID'), env('TWILIO_TOKEN'));
        $twilio->messages->create($mobileNumber, [
            'from' => env('TWILIO_FROM'),
            'body' => "Dear Your OTP is $otp for login in Drop us. This OTP is valid for next 10 minutes.\nDropus Team"
        ]);
    }
    



    public function countries()
    {   
        $success['countries'] = DB::table('tbl_countries')->get(); 
        return $this->sendResponse($success, 'Contries show successfully.');
        
    }


    
    public function state(Request $request)
    {   
        $success['state'] = DB::table('tbl_states')->where('country_id',$request->country_id)->get(); 
        return $this->sendResponse($success, 'State show successfully.');
        
    } 

    public function cities(Request $request)
    {   
        $success['cities'] = DB::table('tbl_cities')->where('state_id',$request->state_id)->get(); 
        return $this->sendResponse($success, 'Cities show successfully.');
        
    } 

}
