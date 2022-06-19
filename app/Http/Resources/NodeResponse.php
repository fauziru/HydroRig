<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use App\Http\Resources\LastRead as LastReadResponse;
use Cache;

class NodeResponse extends JsonResource
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
            'lat' => $this->lat,
            'lng' => $this->lng,
            'status' => $this->status,
            'konektivitas' => Cache::has('node-is-connect-' . $this->id) ? 1 : 0,
            'link' => '/dashboard'.'/'.$this->uuid,
            'last_reads' => LastReadResponse::collection($this->sensors)
        ];
    }
}
