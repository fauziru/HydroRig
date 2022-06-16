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
            'id' => $this->uuid,
            'name_sensor' => $this->name_sensor,
            'batas' => json_decode($this->threshold),
            'tipe' => $this->tipe,
            'status' => Cache::has('node-is-connect-' . $this->node->id) ? 1 : 0,
            'link' => '/sensor'.'/'.$this->uuid,
            'last_read' => $this->last_read ?? 0,
            'last_read_time' => $this->last_read_time,
            'node' => $this->node->name_node
        ];
    }
}
