<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Api\APIBaseController;
use Illuminate\Http\Request;
use App\Models\Sensor;
use App\Models\ReadNutrisi;
use App\User;
use App\Notifications\NutrisiKurang;
use Notification;


class SensorController extends APIBaseController
{
    public function index()
    {

    }

    public function store(Sensor $sensor, $read)
    {
        // jika dibawah min
        if ($read < $sensor->min_nutrisi){
            $item = [
                'message' => 'Konsentrasi nutrisi pada bacaan sensor '.$sensor->id . ' dibawah batas minimum!',
                'link' => '/sensor'.'/'.$sensor->id
            ];
            Notification::send(User::all(), new NutrisiKurang($item));
        }
        $readSensor = ReadNutrisi::create(['sensor_id' => $sensor->id,'read_nutrisi' => $read]);
        return $this->sendResponse($readSensor);
    }
}
