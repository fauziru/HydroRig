<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Notifications\WebPush;
use Notification;
use Illuminate\Support\Facades\Auth;

class NotificationController extends APIBaseController
{

    public function tes()
    {
        return $this->sendResponse('a');
    }

    public function markAsRead($id)
    {
        $notification = Auth()->User()->unreadNotifications->where('id', $id)->markAsRead();
        // return back();
    }

    public function index()
    {
        // return 'tes';
        $unreadnotification = Auth()->user()->unreadNotifications;
        return $this->sendResponse($unreadnotification);
    }

    public function readall()
    {
        $notification = Auth()->User()->unreadNotifications->markAsRead();
    }

    public function allpaginate($page)
    {
        $notification = Auth()->user()->Notifications()->paginate(8, ['*'], 'page', $page);
        return $this->sendResponse($notification);
    }

    public function sensorpaginate($page)
    {
        $notification = Auth()->user()->Notifications()->where('type', 'App\Notifications\NutrisiKurang')->paginate(8, ['*'], 'page', $page);
        return $this->sendResponse($notification);
    }

    public function adminpaginate($page)
    {
        $notification = Auth()->user()->Notifications()->where('type', 'App\Notifications\AdminActivity')->paginate(8, ['*'], 'page', $page);
        return $this->sendResponse($notification);
    }

    public function subscribePushNotification(Request $request)
    {
        $user = Auth::user();
        // echo $request->keys[''];
        $user->updatePushSubscription($request->endpoint, $request->keys['p256dh'], $request->keys['auth']);
        $item = [
            'title' => 'Selamat datang di HydroFarm 😄😄',
            'body' => $user->name_user.', mulai sekarang, notifikasi akan masuk ke sistem anda'
        ];
        Notification::send($user, new WebPush($item));
        return $this->sendResponse('');
    }

    public function tesPushNotification()
    {
        // $item = [
        //     'title' => 'Selamat datang di HydroFarm 😄😄😄',
        //     'body' => 'Mulai sekarang, notifikasi akan masuk ke sistem anda'
        // ];
        // $user = \App\User::all();
        // Notification::send($user, new WebPush($item));
        $usersReceive = \App\User::all();
        $sender = Auth::user();
        $itemNotif2 = [
            'title' => 'Admin',
            'body' => $sender->name_user.', '.'ini adalh',
            'link' => $this->link
        ];
        Notification::send($usersReceive, new WebPush($itemNotif2));
    }

    public function unSubscribePushNotification()
    {

    }

}
