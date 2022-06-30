<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;

class AuthController extends Controller
{
    public function register(Request $request)
    {
        $verification_code = random_int(100000, 999999);
        $user = User::create([
            'name' => $request->name,
            'phone_number' => $request->phone_number,
            'password' => Hash::make($request->password),
            'verification_code' => $verification_code
        ]);

        Log::info($user->name .', Your Verification Code Is : '.$verification_code);

        $token = $user->createToken('authToken')->plainTextToken;

        return response()->json(['status_code' => 200, 'User Data' => $user, 'token' => 'Bearer ' . $token]);

    }


    public function login(Request $request)
    {

        $credentials = request(['phone_number', 'password']);
        if (!Auth::attempt($credentials)) {

            return response()->json(['status_code' => 401, 'message' => 'Phone Number Or Password Doesnt Match ']);

        }

        $user = User::where('phone_number', $request->phone_number)->first();

        $token = $user->createToken('authToken')->plainTextToken;
        return response()->json(
            ['status_code' => 200, 'User Data' => $user, 'token' => 'Bearer ' . $token]
        );
    }


    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();
        return response()->json(['status_code' => 200, 'message' => 'Token deleted and you are now logout!!']);
    }

    public function verifying(Request $request)
    {
        $userId = $request->user()->id;
       $verifycode = $request->verification_code;
      $user = User::query()->where('verification_code','=',$verifycode)->find($userId);
      if ($user){
          $user->update(['verified_at' => now()]);
          return response()->json(['status_code' => 200 , 'message' => 'You Successfully Verified Your Account']);
      }
      return response()->json(['message' => 'Your Verification Code Incorrect']);
    }
}
