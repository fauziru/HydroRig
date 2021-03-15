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
            'role' => $this->role,
            'name' => $this->name,
            'email_address' => $this->email,
            'phone_number' => $this->phone,
            'img_path' => $this->profile_image
        ];
    }
}
