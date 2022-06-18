<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use App\User;
use Notification;
use App\Notifications\AdminActivity;
use App\Notifications\WebPush;
use Illuminate\Support\Facades\Auth;

class QueueUserNotificationsJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public $message = null;
    public $link = null;
    public function __construct($message, $link)
    {
        $this->message = $message;
        $this->$link = $link;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $usersReceive = \App\User::all();
        $sender = Auth::user();
        $itemNotif1 = [
            'message' => '<span class="primary--text">'.$user->name_user.'</span> &mdash; '.$this->message,
            'link' => $this->link
        ];
        Notification::send($usersReceive, new AdminActivity($itemNotif1));
        $itemNotif2 = [
            'title' => 'Admin',
            'body' => $sender->name_user.', '.$this->message,
            'link' => $this->link
        ];
        Notification::send($usersReceive, new WebPush($itemNotif2));
    }
}
