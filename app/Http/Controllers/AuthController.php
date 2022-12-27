<?php

namespace App\Http\Controllers;

use App\Http\Requests\UserSignUpRequest;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth:api')->only('logout');
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     * @throws \Illuminate\Validation\ValidationException
     */
    public function login( Request $request )
    {
        $credentials = $request->only('email', 'password');
        $this->validateRequest($credentials,[
           'email' => 'required|email',
           'password' => 'required'
        ]);
        if( $token = auth()->attempt($credentials)){
           return $this->sendJsonSuccess([
               'access_token' => $token,
               'expires_in' => auth()->factory()->getTTL() * 60,
               'user' => auth()->user()
           ]);
        }
        return $this->sendJsonError( 'Invalid login details');
    }

    /**
     * User register
     *
     * @param UserSignUpRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function register( UserSignUpRequest $request )
    {
        $user = new User();
        $user->fill( $request->only('name', 'email') );
        $user->password = Hash::make( $request->password );
        $user->save();
        return $this->sendJsonSuccess( $user, 'User registered successfully');
    }

    /**
     * Logout authenticate user
     */
    public function logout()
    {
        auth()->logout();
        return $this->sendJsonMessage('Successfully logout');
    }

    /**
     * Refresh jwt token
     */
    public function refreshToken()
    {
       return $this->sendJsonSuccess([
          'token' => auth()->refresh(),
          'expires_in' => auth()->factory()->getTTL() * 60
       ], 'Token refresh successfully');
    }
}
