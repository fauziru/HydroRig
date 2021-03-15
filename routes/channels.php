<?php

use Illuminate\Support\Facades\Broadcast;

/*
|--------------------------------------------------------------------------
| Broadcast Channels
|--------------------------------------------------------------------------
|
| Here you may register all of the event broadcasting channels that your
| application supports. The given channel authorization callbacks are
| used to check if an authenticated user can listen to the channel.
|
*/
 Broadcast::channel('events', function ($user) {
     return true;
 });

Broadcast::channel('App.User.{uuid}', function ($user, $uuid) {
    return (int) $user->uuid === (int) $uuid;
});
