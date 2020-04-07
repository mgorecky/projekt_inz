<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User;
use App\Http\Requests\RegisterRequest;

class AuthController extends ResponseController
{
    public function register(Request $request){
        $user = User::create([
            'email' => $request->email,
            'password' => bcrypt($request->password),
            'first_name' => $request->first_name,
            'last_name' => $request->last_name,
            'role_id' => 2,
        ]);

        $token = auth()->login($user);
        return $this->respondWithToken($token);
    }

    public function login(Request $request){
        $credentials = $request->only(['email', 'password']);
        if (!$token = auth()->attempt($credentials))
            return response()->json(
                ['error' => 'unauthorized'],
                401
            );

        return $this->respondWithToken($token);
    }

    public function getAuthUser(Request $request){
        return $this->success(auth()->user());
    }

    public function logout(){
        auth()->logout();
        return $this->success('logged out');
    }

    protected function respondWithToken($token){
        return $this->success([
            'access_token' => $token,
            'token_type' => 'bearer',
            'expires_in' => auth()->factory()->getTTL() * 60
        ]);
    }
}
