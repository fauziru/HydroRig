<?php

namespace App\Http\Controllers\Api;

use App\Notifications\AdminActivity;
use Notification;

trait AdminActivityTrait{
    public function sendNotif($message)
    {
        $item = [
            'message' => $this->adminName.' '.$message,
            'link' => $this->link
        ];
        Notification::send($this->usersAdmin, new AdminActivity($item));
    }
}
