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
use App\Events\RealtimeDataSensor;
use Carbon\Carbon;

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
        event(new RealtimeDataSensor($readSensor, $sensor->id));
        return $this->sendResponse($readSensor);
    }

    public function showWidget(Sensor $sensor)
    {
        // dd($sensor->readNutrisi[0]->created_at);
        $arrCategories = [];
        $arrSeries = [];
        foreach ($sensor->readNutrisi->orderBy('created_at', 'desc')->take(50) as $read){
            array_push($arrSeries, $read->read_nutrisi);
            array_push($arrCategories, Carbon::parse($read->created_at)->setTimezone('Asia/Jakarta')->format('Y-m-d H:i:s'));
        }
        $item = [
            'sensor_data' => new SensorResource($sensor),
            'series_data' => $arrSeries,
            'categories_data' => $arrCategories
        ];
        return $this->sendResponse($item);
    }

    public function showDetail(Sensor $sensor)
    {
        $arrCategories = [];
        $arrSeries = [];
        foreach ($sensor->readNutrisi as $read){
            array_push($arrSeries, $read->read_nutrisi);
            array_push($arrCategories, Carbon::parse($read->created_at)->setTimezone('Asia/Jakarta')->format('Y-m-d H:i:s'));
        }
        $item = [
            'sensor_data' => new SensorResource($sensor),
            'series_data' => $arrSeries,
            'categories_data' => $arrCategories
        ];
        return $this->sendResponse($item);
    }
}
