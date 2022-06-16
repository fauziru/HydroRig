<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class User extends JsonResource
{
    /**
     * Transform the resource collection into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request) {
        return [
            'id' => $this->uuid,
            'role' => $this->role->name_role,
            'name' => $this->name_user,
            'email_address' => $this->email,
            'phone_number' => $this->phone,
            'address' => $this->address,
            'img_path' => $this->profile_image,
            'is_subscribe' => $this->is_subscribe,
            'last_seen' => $this->last_seen,
            'is_online' => $this->is_online
        ];
    }
}
