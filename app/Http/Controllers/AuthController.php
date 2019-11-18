<?php

namespace App\Http\Controllers;

use App\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Laravel\Passport\Token;

class AuthController extends Controller
{
    public function login(Request $request)
    {
        if (\Auth::guard('web')->attempt($request->all())) {
            $user = User::where(['email' => $request->email])->first();
            $tokenResult = $user->createToken('Personal Access Token');
            $token = $tokenResult->token;
            $token->expires_at = Carbon::now()->addDays(20);
            $token->save();
            return response()->json([
                'token' => $tokenResult->accessToken,
                'user'  => [
                    'id' => $user->id,
                    'name' => $user->name,
                    'email' => $user->email
                ]
            ]);
        } else {
            return response()->json('Your request are invalid. Please enter a username or a password', 401);
        }
    }

    public function register(Request $request)
    {
        $validator = \Validator::make($request->all(), [
            'name' => 'required|min:4',
            'email' => 'required|email|unique:users',
            'password' => 'required|min:6',
        ]);
        if ($validator->fails()) {
            return response()->json([
                'field' => $validator->errors()->keys()[0],
                'message' => $validator->errors()->first()
            ], 400);
        }

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => \Hash::make($request->password)
        ]);

        if ($user) {
            return response()->json(['message' => 'The user is created.'], 201);
        }
    }

    public function logout() {
        auth()->user()->tokens->each(function (Token $token, $key) {
           $token->delete();
        });
        return response()->json(['message' => 'Logged out successfully.'], 200);
    }
}
