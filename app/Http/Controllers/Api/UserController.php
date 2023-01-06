<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
    // Post - User Register API
    public function register(Request $request){
        
        $request->validate([
            "name"          =>  "required",
            "email"         =>  'required',
            "phone_no"      =>  'required',
            "password"      =>  "required",
        ]);

        // create user
        $user = new User();
        $user->name = $request->name;
        $user->email = $request->email;
        $user->phone_no = $request->phone_no;
        $user->password = bcrypt($request->password);        

        $user->save();
        // send response

        return response()->json([
            "status" => 1,
            "message"   => "User registered successfully",

        ], 201);
    }

    // Post - User Login API
    public function login(Request $request){
        // validation
        $request->validate([
            "email"     => "required|email",
            "password"  => "required"
        ]);
        // Verify User
        if(!$token = auth()->attempt([
            "email"     => $request->email,
            "password"  => $request->password
        ])){
            return response()->json([
                "status"    => 0,
                "message"   =>  "Invalid credentials"
            ]);
        }
        return response()->json([
            "status"        => 1,
            "message"       =>  "Logged in Successfully",
            "access_token"  =>  $token
        ]);
    }

    // Get - User Profile API
    public function profile(){
        $user_data = auth()->user();

        return response()->json([
            "status"    =>  1,
            "message"   =>  "User Profile Data",
            "date"      =>  $user_data
        ]);
    }

    // Get - User Logout API
    public function logout(){
        auth()->logout();
        return response()->json([
            "status"    => 1,
            "message"   => "user logged out"
        ]);
    }
}
