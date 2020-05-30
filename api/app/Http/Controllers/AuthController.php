<?php

namespace App\Http\Controllers;

use App\Http\Requests\LoginRequest;
use Illuminate\Http\Request;
use App\User;
use App\Http\Requests\RegisterRequest;

/**
 * @group AuthController
 * APIs for front page actions with Auth actions.
 */
class AuthController extends ResponseController
{
    /**
     * Register account
     * @bodyParam email email required User's email. Example: mikolaj.gorecki@student.up.krakow.pl
     * @bodyParam password password required User's password. Example: P@$$w0rd
     * @bodyParam first_name email required User's email. Example: Mikołaj
     * @bodyParam last_name email required User's email. Example: Górecki
     *
     * @response 200 {
     * "status":"success",
     * "code":200,
     * "message":"OK",
     * "data":{
     * "access_token": "GeneratedJWTUserToken",
     * "token_type": "bearer",
     * "expires_in": 180,
     * "role": 2
     * }
     * }
     */
    public function register(RegisterRequest $request){
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

    /**
     * Login into website
     * @bodyParam email email required User's email. Example: mikolaj.gorecki@student.up.krakow.pl
     * @bodyParam password password required User's password. Example: P@$$w0rd
     *
     * @response 200 {
     * "status":"success",
     * "code":200,
     * "message":"OK",
     * "data":{
     * "access_token": "GeneratedJWTUserToken",
     * "token_type": "bearer",
     * "expires_in": 180,
     * "role": 2
     * }
     * }
     *
     * @response 401 {
     * "status":"unauthorized",
     * "code":401,
     * "message":"unauthorized"
     * }
     */
    public function login(LoginRequest $request){
        $credentials = $request->only(['email', 'password']);
        if (!$token = auth()->attempt($credentials))
            return $this->unauthorized('unauthorized');

        auth()->user()->SetForHash($credentials['email'], $credentials['password']);

        return $this->respondWithToken($token);
    }

    /**
     * Logout
     * @authenticated
     *
     * @response 200 {
     * "status":"success",
     * "code":200,
     * "message":"OK",
     * "data": "logged out"
     * }
     */
    public function logout(){
        auth()->logout();
        return $this->success('logged out');
    }

    /**
     * Respond with token
     * Function returns response with status 200. Created to use in other functions.
     *
     * @response 200 {
     * "status":"success",
     * "code":200,
     * "message":"OK",
     * "data":{
     * "access_token": "GeneratedJWTUserToken",
     * "token_type": "bearer",
     * "expires_in": 180,
     * "role": 2
     * }
     * }
     */
    protected function respondWithToken($token){
        return $this->success([
            'access_token' => $token,
            'token_type' => 'bearer',
            'expires_in' => auth()->factory()->getTTL() * 60,
            'role' => auth()->user()->role_id
        ]);
    }
}
