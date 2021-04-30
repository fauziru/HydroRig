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
use Illuminate\Support\Facades\DB;

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
        return $this->sendResponse($this->wrapData($sensor->readNutrisi->take(50), $sensor));
    }

    public function showDetail(Sensor $sensor, $filter, $from = null, $to = null)
    {
        $filterArray = ['today' => 1, 'this-week' => 8, 'this-month' => 31, 'date' => 1];

        if (!$filterArray[$filter]) return $this->sendError([], 404);

        $filter === 'date'
            ? $dataReadSensor = DB::table('read_nutrisi')
                    ->where('sensor_id', '=', $sensor->id)
                    ->whereBetween('created_at', [$from, $to])
                    ->get()
            : $dataReadSensor = DB::table('read_nutrisi')
                    ->where('sensor_id', '=', $sensor->id)
                    ->whereDate('created_at', '>', Carbon::now()->subDays($filterArray[$filter]))
                    ->get();

        return $this->sendResponse($this->wrapData($dataReadSensor, $sensor));
    }

    private function wrapData($ReadSensors, $sensor)
    {
        $arrSeries = [];
        $arrCategories = [];

        foreach ($ReadSensors as $read){
            array_push($arrSeries, $read->read_nutrisi);
            array_push($arrCategories, Carbon::parse($read->created_at)->setTimezone('Asia/Jakarta')->format('Y-m-d H:i:s'));
        }

        return $item = [
            'sensor_data' => new SensorResource($sensor),
            'series_data' => $arrSeries,
            'categories_data' => $arrCategories
        ];
    }
}
