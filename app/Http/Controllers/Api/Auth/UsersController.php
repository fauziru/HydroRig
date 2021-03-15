<?php

namespace App\Http\Controllers\Api\Auth;

use App\Http\Controllers\Controller;
use App\Http\Resources\User as UserResource;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use App\User;

class UsersController extends Controller
{
  public function tes() {
    return response()->json([
      'success' => true,
      'message' => 'tes',
    ], 200);
  }
  
  public function login() {
    if (Auth::attempt(['email' => request('email'), 'password' => request('password')])) {
      $user = Auth::user();
      $success['token'] = $user->createToken('appToken')->accessToken;
        
      //After successfull authentication, notice how I return json parameters
      return response()->json([
        'success' => true,
        'token' => $success,
        'user' => $user
      ]);
    } else {
    //if authentication is unsuccessfull, notice how I return json parameters
      return response()->json([
        'success' => false,
        'message' => 'Invalid Email or Password',
      ], 401);
    } 
  }

  public function register(Request $request){
    $validator = Validator::make($request->all(), [
      'name' => 'required',
      'role' => 'required',
      'phone' => 'required|unique:users|regex:/(0)[0-9]{10}/',
      'email' => 'required|email|unique:users',
      'password' => 'required',
    ]);

    if ($validator->fails()) {
      return response()->json([
        'success' => false,
        'message' => $validator->errors(),
      ], 401);
    }

    $input = $request->all();
    $input['password'] = Hash::make($request['password']);
    $user = User::create($input);
    $success['token'] = $user->createToken('nApp')->accessToken;
    return response()->json([
      'success' => true,
      'token' => $success,
      'user' => $user
    ]);
  }

  public function logout(Request $res)
  {
    if (Auth::user()) {
      $user = Auth::user()->token();
      $user->revoke();

      return response()->json([
        'success' => true,
        'message' => 'Logout successfully'
    ]);
    }else {
      return response()->json([
        'success' => false,
        'message' => 'Unable to Logout'
      ]);
    }
   }

  public function details() {
      $user = new UserResource(Auth::user());
      return response()->json([
        'success' => true,
        'data' => $user  
      ]);
  }

}
