<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class ApiController extends Controller
{
    public function userRegister(Request $req)
    {
        try {
            $validator = Validator::make($req->all(), [
                "name" => "required",
                "email" => "required|unique:users,email",
                "password" => "required|min:6|confirmed"
            ]);

            if ($validator->fails()) {
                return response()->json(["status" => 0, "message" => "Validation failed", "data" => $validator->errors()], 422);
            }

            $user = User::where("email", $req->email)->first();
            if ($user) {
                return response()->json(["status" => 0, "message" => "User already exists"], 409);
            }
            $user = User::create([
                "name" => $req->name,
                "email" => $req->email,
                "password" => Hash::make($req->password)
            ]);

            $token = $user->createToken("APIToken")->accessToken;

            return response()->json(["status" => 1, "message" => "User registered successfully", "data" => $token]);
        } catch (\Exception $e) {
            return response()->json(["status" => 0, "message" => "Something went wrong", "data" => $e->getMessage()], 500);
        }
    }

    public function userLogin(Request $req)
    {
        try {
            $validator = Validator::make($req->all(), [
                "email" => "required|email",
                "password" => "required"
            ]);

            if ($validator->fails()) {
                return response()->json(["status" => 0, "message" => "Validation failed", "data" => $validator->errors()], 422);
            }

            $user = User::where("email", $req->email)->where("role", 2)->first();
            if (!$user) {
                return response()->json(["status" => 0, "message" => "User not found"], 404);
            }

            if (!Hash::check($req->password, $user->password)) {
                return response()->json(["status" => 0, "message" => "Invalid password"], 401);
            }

            $token = $user->createToken("APIToken")->accessToken;

            return response()->json(["status" => 1, "message" => "User logged in successfully", "data" => $token]);
        } catch (\Exception $e) {
            return response()->json(["status" => 0, "message" => "Something went wrong", "data" => $e->getMessage()], 500);
        }
    }

    public function profile(Request $req)
    {
        try {
            $user = $req->user();
            return response()->json(["status" => 1, "message" => "User profile", "data" => $user]);
        } catch (\Exception $e) {
            return response()->json(["status" => 0, "message" => "Something went wrong", "data" => $e->getMessage()], 500);
        }
    }
}
