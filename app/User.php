<?php

namespace App;

use App\Traits\Uuid;
//use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Passport\HasApiTokens;
use NotificationChannels\WebPush\HasPushSubscriptions;

class User extends Authenticatable
{
    use HasApiTokens, Notifiable, Uuid, HasPushSubscriptions;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name_user', 'email', 'password', 'role_id', 'phone'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'is_online' => 'boolean'
    ];

    public function receivesBroadcastNotificationsOn()
    {
        return 'App.User.'.$this->uuid;
    }

    public function role()
    {
        return $this->belongsTo('App\Models\Role','role_id','id');
    }

    public function getIsSubscribeAttribute()
    {
        $subs = $this->pushSubscriptions();
        return $subs->count() > 0;
    }

    public function resolveRouteBinding($value, $field = NULL)
    {
        return $this->where('uuid', $value)->first() ?? abort(404);
    }
}
