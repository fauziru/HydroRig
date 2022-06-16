<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use Cache;
use Carbon\Carbon;

class DashboardSensorResponse extends JsonResource
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
            'status' => Cache::has('sensor-is-connect-' . $this->id) ? 1 : 0,
            'link' => '/sensor'.'/'.$this->uuid,
            'last_read' => $this->last_read ?? 0,
            'last_read_time' => $this->last_read_time,
            'data_set' => $this->wrapData($this->read)
        ];
    }
    private function wrapData($ReadSensors)
    {
        $arrSeries = [];
        $arrCategories = [];

        foreach ($ReadSensors as $read){
            array_push($arrSeries, $read->read);
            array_push($arrCategories, Carbon::parse($read->created_at)->setTimezone('Asia/Jakarta')->format('Y-m-d H:i:s'));
        }

        return $item = [
            'series_data' => $arrSeries,
            'categories_data' => $arrCategories
        ];
    }
}
