<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Api\APIBaseController;
use Illuminate\Http\Request;
use App\Models\Sensor;
use App\Models\ReadNutrisi;
use App\User;
use App\Notifications\NutrisiKurang;
use Notification;
use App\Http\Resources\Sensor as SensorResource;

class ReadNutrisiController extends APIBaseController
{
    public function store(Sensor $sensor, $read)
    {
        // jika dibawah min
        if ($read < $sensor->min_nutrisi){
            $item = [
                'message' => '<span class="primary--text">'.$sensor->name_sensor.'</span> &mdash; Konsentrasi nutrisi pada bacaan sensor '.$sensor->name_sensor.' dibawah batas minimum!',
                'link' => '/sensor'.'/'.$sensor->id
            ];
            Notification::send(User::all(), new NutrisiKurang($item));
        }
        $readSensor = ReadNutrisi::create(['sensor_id' => $sensor->id,'read_nutrisi' => $read]);
        // event update realtime
        return $this->sendResponse($readSensor);
    }

    public function showWidget(Sensor $sensor)
    {
        // dd($sensor->readNutrisi);
        $arrCategories = [];
        $arrSeries = [];
        $sensorData = $sensor;
        $readData = $sensor->readNutrisi;
        foreach ($sensor->readNutrisi as $read){
            array_push($arrSeries, $read->read_nutrisi);
            array_push($arrCategories, $read->created_at);
        }
        $item = [
            'sensor_data' => new SensorResource($sensor),
            'series_data' => $arrSeries,
            'categories_data' => $arrCategories
        ];
        return $this->sendResponse($item);
    }
}
