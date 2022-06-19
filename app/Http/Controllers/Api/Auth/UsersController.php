<?php

namespace App\Http\Controllers\Api\Auth;

use App\Events\UpdateUserStatus;
use App\Http\Controllers\Api\APIBaseController;
use App\Http\Resources\User as UserResource;
use App\Jobs\QueueUserNotificationsJob;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use Laravel\Passport\Client;
use Laravel\Passport\HasApiTokens;
use App\User;
use App\Models\Setting;
use App\Traits\ImageHandler;

class UsersController extends APIBaseController
{
    use IssueTokenTrait, ImageHandler;

    private $client;

    public function __construct(){
        $this->client = Client::find(2);
    }

    public function index(): \Illuminate\Http\JsonResponse
    {
        $users = UserResource::collection(User::where("id", "!=", Auth::user()->id)->with('role')->get());
        return $this->sendResponse($users);
    }

    public function show(User $user = null): \Illuminate\Http\JsonResponse
    {
        return $this->sendResponse(new UserResource($user));
    }

    public function checkRegisterKey(Request $request): \Illuminate\Http\JsonResponse
    {
        $settings = Setting::first();
        if($settings->registrasi_key !== $request->keyRegister) return $this->sendResponse([ 'validKey' => false ], 'Key registrasi tidak valid');
        return $this->sendResponse([ 'validKey' => true ], 'Validasi Key registrasi berhasil');
    }

    public function register(Request $request): \Illuminate\Http\JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'name_user' => 'required',
            'email' => 'required|email|unique:users',
            'password' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => $validator->errors(),
            ], 400);
        }

        $input = $request->all();
        $input['password'] = Hash::make($request['password']);
        $user = User::create($input);
        return $this->issueToken($request, 'password');
    }

    public function update(Request $request): \Illuminate\Http\JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'name_user' => 'required',
            'phone' => 'regex:/(0)[0-9]{10}/'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => $validator->errors(),
            ], 400);
        }

        $user = Auth::user();
        $user->update($request->all());
        return $this->sendResponse($request->all());
    }

    public function details(): \Illuminate\Http\JsonResponse
    {
        $user = new UserResource(Auth::user());
        return response()->json([
            'success' => true,
            'data' => $user
        ]);
    }

    public function countUser(): \Illuminate\Http\JsonResponse
    {
        return $this->sendResponse(count(User::all(['id'])));
    }

    public function storeAvatar(Request $request): \Illuminate\Http\JsonResponse
    {
        $this->validate($request, [
            'image'=>'required'
        ]);
        $user = Auth::user();
        $fileName = null;
        // hapus jika ada avatar sebelumnya
        if($user->profile_image) {
            $this->deleteImage($user->profile_image, 'avatar');
        }
        // upload gambar
        if($file = $request->file('image'))
        {
            $fileName = $this->saveImage($file, 'avatar');
        }
        $user->profile_image = $fileName;
        $user->save();
        return $this->sendResponse($fileName);
    }

    public function upgradeAdmin(User $user): \Illuminate\Http\JsonResponse
    {
        // jika sudah admin maka beri resoonse error
        if ($user->role_id == 1) return $this->sendError('user sudah menjadi admin', 500);
        $user->update(['role_id' => 1]);
        event(new UpdateUserStatus(new UserResource($user)));
        dispatch(new QueueUserNotificationsJob('menjadikan '.$user->name_user.' admin', '#'));
        return $this->sendResponse($user);
    }

    public function cekEmail(Request $request): \Illuminate\Http\JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email|unique:users'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => $validator->errors(),
            ], 200);
        }
        return $this->sendResponse($request->email);
    }
}
