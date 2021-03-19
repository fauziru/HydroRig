<?php

namespace App\Http\Controllers\Api;

use App\Notifications\AdminActivity;
use Illuminate\Support\Facades\Auth;
use App\User;
use Notification;

trait AdminActivityTrait{
    public function sendNotif($message, $link = '/sensor')
    {
        $item = [
            'message' => Auth::user()->name.' '.$message,
            'link' => $link
        ];
        Notification::send(User::all(), new AdminActivity($item));
    }
}
