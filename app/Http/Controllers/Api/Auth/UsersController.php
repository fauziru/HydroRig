<?php

namespace App\Http\Controllers\Api\Auth;

use App\Http\Controllers\Api\APIBaseController;
use App\Http\Resources\User as UserResource;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use App\User;

class UsersController extends APIBaseController
{
    public function index()
    {
        $users = UserResource::collection(User::all());
        return $this->sendResponse($users);
    }

    public function show(User $user = null)
    {
        return $this->sendResponse(new UserResource($user));
    }

    public function tes() {
        return response()->json([
        'success' => true,
        'message' => 'tes',
        ], 200);
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
