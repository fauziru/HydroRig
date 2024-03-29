<?php

namespace App\Notifications;
use Illuminate\Notifications\Notification;
use NotificationChannels\WebPush\WebPushMessage;
use NotificationChannels\WebPush\WebPushChannel;

class ReachThreshold1 extends Notification
{
    private $item;
    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct($item)
    {
        $this->item = $item;
    }

    public function via($notifiable)
    {
        return [WebPushChannel::class];
    }

    public function toWebPush($notifiable, $notification)
    {
        return (new WebPushMessage)
            ->title($this->item['title'])
            ->icon('/img/icons/android-chrome-512x512.png')
            ->body($this->item['body'])
            ->action('View Action', env('CLIENT_APP_URL').$this->item['link'])
            ->options(['TTL' => 1000])
            ->badge('/img/icons/badge.png');
            // ->data(['id' => $notification->id])
            // ->badge()
            // ->dir()
            // ->image()
            // ->lang()
            // ->renotify()
            // ->requireInteraction()
            // ->tag()
            // ->vibrate()
    }
}
