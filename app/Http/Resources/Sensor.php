<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use Cache;

class Sensor extends JsonResource
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
            'id' => $this->id,
            'name_sensor' => $this->name_sensor,
            'min_nutrisi' => $this->min_nutrisi,
            'status' => Cache::has('sensor-is-connect-' . $this->id) ? 1 : 0,
            'link' => '/sensor'.'/'.$this->id,
            'last_read' => $this->last_read
        ];
    }
}
