<?php

namespace App\Http\Controllers\Api\Auth;

use App\Http\Controllers\Api\APIBaseController;
use Illuminate\Http\Request;
use Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Validator;
use Laravel\Passport\Client;
use Laravel\Passport\HasApiTokens;
use App\User;

class LoginController extends APIBaseController
{
    use IssueTokenTrait;
    
    private $client;

    public function __construct(){
        $this->client = Client::find(2);
    }

    public function login(Request $request) {
        // return $this->sendResponse(['help' => 'ya'],'tes');
        $validator = Validator::make($request->all(), [
            'email' => 'required',
            'password' => 'required'
        ]);
        $authUser = User::where('email', $request->email)->first();
        
        // cek validasi
        if ($validator->fails()) return $this->sendError($validator->errors(), 401);
        // cek email tidak terdaftar
        if(!$authUser) return $this->sendError('Email belum terdaftar', 401);
        // cek kecocokan email dan password
        if (Auth::attempt(['email' => $request->email, 'password' => $request->password])) {
            // jika login berhasil
            return $this->issueToken($request, 'password');
            // return $this->sendAuthSuccess($payload, $request->email, 'Percobaan masuk berhasil');
        } else {
            // jika login gagal
            return $this->sendError('Password tidak cocok', 403);
        } 
    }

    public function refresh(Request $request){
        $validator = Validator::make($request->all(), [
            'refresh_token' => 'required'
        ]);
        if ($validator->fails()) return $this->sendError($validator->errors(), 401);
    	  return $this->issueToken($request, 'refresh_token');
    }

    public function logoutApi(){
        $token = Auth::user()->token();
        $token->revoke();
        $response = ['message' => 'You have been successfully logged out!'];
        return response($response, 200);
    }  
}
