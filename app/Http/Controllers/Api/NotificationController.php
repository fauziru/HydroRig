<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Api\APIBaseController;
use Illuminate\Http\Request;

class NotificationController extends APIBaseController
{

    public function tes()
    {
      return 'tes';
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

}
