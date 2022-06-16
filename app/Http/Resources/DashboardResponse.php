<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use App\Http\Resources\DashboardSensorResponse as SensorResponse;

class DashboardResponse extends JsonResource
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
            'name_node' => $this->name_node,
            'status' => $this->status,
            'konektivitas' => $this->konektivitas,
            'sensors' => SensorResponse::collection($this->sensors)
        ];
    }
}
