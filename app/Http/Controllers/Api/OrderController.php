<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Notifications\NutrisiKurang;
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
        $item = [
            'message' => 'tes notifikasi nutrisi kurang',
            'link' => '/kolam/uuid'
        ];
        Notification::send($usersAdmin, new NutrisiKurang($item));
        // Notification::send($usersAdmin, new MessageMasuk('ini notifikasi message'));
        // Notification::send($usersAdmin, new ReviewMasuk('ini notifikasi review'));
    }
}
