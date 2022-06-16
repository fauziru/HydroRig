<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use Cache;

class LayoutResponse extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'id' => $this->uuid,
            'name_layout' => $this->name_layout,
            'file_name' => $this->file_name
        ];
    }
}
