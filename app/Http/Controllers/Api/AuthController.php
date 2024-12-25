<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Admin; 
use App\User;  
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class AuthController extends BaseController
{
    public function register(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:admins,email', // Use admins table
            'password' => 'required|string|min:8|confirmed',
        ]);

        $admin = Admin::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
        ]);

        return response()->json(['message' => 'Admin created successfully'], 201);
    }

    public function login(Request $request)
    {
        $validated = $request->validate([
            'email' => 'required|email',
            'password' => 'required|string',
        ]);

        $admin = Admin::where('email', $validated['email'])->first();

        if (!$admin || !Hash::check($validated['password'], $admin->password)) {
            throw ValidationException::withMessages([
                'email' => ['The provided credentials are incorrect.'],
            ]);
        }

        // Use the admin guard for creating the token
        $token = $admin->createToken('AdminApp')->plainTextToken;

        return response()->json(['token' => $token]);
    }

    

    public function userinfo(Request $request)
    {  
        $user = $request->user();  
        if (!$user) {
            return $this->sendError('User not authenticated.', [], 401);
        } 
        $success['user'] = User::find($user->id);
        return $this->sendResponse($success, 'User info show successfully.');
        
    }
}
