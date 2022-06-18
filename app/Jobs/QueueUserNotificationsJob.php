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
        $user = \App\User::all();
        $item = [
            'message' => '<span class="primary--text">'.$user->name_user.'</span> &mdash; '.$this->message,
            'link' => $this->link
        ];
        Notification::send(User::all(), new AdminActivity($item));
        $item = [
            'title' => 'Admin',
            'body' => $user->name_user.', '.$this->message,
            'link' => $this->link
        ];
        Notification::send($user, new WebPush($item));
    }
}
