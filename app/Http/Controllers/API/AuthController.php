<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Controllers\API\BaseController as BaseController;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\support\Facades\Hash;

class AuthController extends BaseController
{
    public function register(Request $request)
    {
        $data = $request->validate([
            'name'    => 'required|max:255',
            'email'   => 'required|email|unique:users',
            'password'=> 'required|confirmed'
        ]);

        $data['password']= Hash::make($request->password);

        $user = User::create($data);

        $token = $user->createToken($request->name . 'api_token')->plainTextToken;

        return $this->sendResponse([
            'user'=> $user,
            'token'=> $token
        ], 'User register successfully.');
    }

    public function login(Request $request)
    {
        $request->validate([
            'email'   => 'required|email',
            'password' => 'required'
        ]);

        $user = User::where('email', $request->email)->first();

        $token = $user->createToken($request->name . ' api_token')->plainTextToken;

        return $this->sendResponse([
            'user' => $user,
            'token' => $token
        ], 'User login successfully.');

        if (!$user || Hash::check($request->password, $user->password)) {

            return $this->sendError('this credentials are incorrect.', ['error'=>'Unauthorised'], );
        }
    }

    public function logout(Request $request)
    {
        $user= Auth::user();

        $user->tokens()->delete();
        
        return ['message'=>'you are logged out '];
    }
}
