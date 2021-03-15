<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Notifications\OrderanMasuk;
use App\Notifications\MessageMasuk;
use App\Notifications\ReviewMasuk;
use App\Events\RealTimeMessage;
use App\Notifications\AdminActivity;
use App\User;
use Notification;

class OrderController extends Controller
{
    public function tesNotif()
    {
        // return 'tes';
        $usersAdmin = User::where('role', 'admin')->get();
        // event(new RealTimeMessage('tes private tes'));
        Notification::send($usersAdmin, new OrderanMasuk('ini notifikasi order'));
        Notification::send($usersAdmin, new MessageMasuk('ini notifikasi message'));
        Notification::send($usersAdmin, new ReviewMasuk('ini notifikasi review'));
        $name = 'tes';
        $item = [
            'message' => $name.'ini notifikasi admin',
            'link' => route('payment.index')
        ];
        Notification::send($usersAdmin, new AdminActivity($item));
    }
}
