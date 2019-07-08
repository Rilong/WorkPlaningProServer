<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class AuthController extends Controller
{
    public function login(Request $request) {
        $http = new \GuzzleHttp\Client();

        try {
            $response = $http->post(config('services.passport.url'), [
                'form_params' => [
                    'grant_type' => 'password',
                    'client_id' => config('services.passport.client_id'),
                    'client_secret' => config('services.passport.client_secret'),
                    'username' => $request->email,
                    'password' => $request->password
                ]
            ]);
            return $response->getBody();
        } catch (\GuzzleHttp\Exception\BadResponseException $e) {
            if ($e->getCode() == 400) {
                return response()->json(['message' => 'Your request are invalid. Please enter a username or a password'], $e->getCode());
            } elseif ($e->getCode() == 401) {
                return response()->json(['message' => 'Your credentials are incorrect. Please try again'], $e->getCode());
            }
        }
    }
}
