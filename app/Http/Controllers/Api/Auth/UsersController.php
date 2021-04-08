<?php

namespace App\Http\Controllers\Api\Auth;

use App\Http\Controllers\Api\APIBaseController;
use App\Http\Resources\User as UserResource;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use Laravel\Passport\Client;
use Laravel\Passport\HasApiTokens;
use App\User;
use App\Models\Setting;

class UsersController extends APIBaseController
{
    use IssueTokenTrait;

    private $client;

    public function __construct(){
        $this->client = Client::find(2);
    }

    public function index()
    {
        $users = UserResource::collection(User::all());
        return $this->sendResponse($users);
    }

    public function show(User $user = null)
    {
        return $this->sendResponse(new UserResource($user));
    }

    public function checkRegisterKey(Request $request)
    {
        $settings = Setting::first();
        if($settings->registrasi_key !== $request->keyRegister) return $this->sendResponse([ 'validKey' => false ], 'Key registrasi tidak valid');
        return $this->sendResponse([ 'validKey' => true ], 'Validasi Key registrasi berhasil');
    }

    public function register(Request $request){
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            // 'role' => 'required',
            // 'phone' => 'required|unique:users|regex:/(0)[0-9]{10}/',
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
        return $this->issueToken($request, 'password');
    }

    public function details() {
        $user = new UserResource(Auth::user());
        return response()->json([
            'success' => true,
            'data' => $user
        ]);
    }

}
