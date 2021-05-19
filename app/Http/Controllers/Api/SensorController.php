<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Api\APIBaseController;
use Illuminate\Http\Request;
use App\Models\Sensor;
use App\Http\Resources\Sensor as SensorResource;
use Cache;


class SensorController extends APIBaseController
{
    use AdminActivityTrait;

    public function index()
    {
        $sensors = SensorResource::collection(Sensor::all());
        return $this->sendResponse($sensors);
    }

    public function store(Request $request)
    {
        try {
            $sensor = Sensor::create($request->only(['name_sensor', 'min_nutrisi']));
            $this->sendNotif('menambahkan sensor baru', '/sensor'.'/'.$sensor->id);
            return $this->sendResponse(new SensorResource($sensor));
        } catch (\Throwable $th) {
            $sensor->delete();
            return $this->sendError('Terjadi kesalahan pada server', 500);
        }
    }

    public function put(Sensor $sensor, Request $request)
    {
        $sensor->name_sensor = $request->name_sensor;
        $sensor->min_nutrisi = $request->min_nutrisi;
        $sensor->save();
        $this->sendNotif('mengubah data sensor '.$sensor->name_sensor, '/sensor'.'/'.$sensor->id);
        return $this->sendResponse(new SensorResource($sensor),'edit data successfull');
    }

    public function destroy(Sensor $sensor)
    {
        $sensor->delete();
        $this->sendNotif('menghapus sensor '.$sensor->name_sensor);
        return $this->sendResponse([], 'deleted successfull');
    }

    public function connectStatus(Sensor $sensor)
    {
        $status = Cache::has('sensor-is-connect-' . $sensor->id) ? 1 : 0;
        $lastRead = $sensor->last_read;
        return $this->sendResponse(['status'=> $status, 'lastRead'=> $lastRead]);
    }

    public function connectStatusAll()
    {
        $onlineStatuss = [];
        $sensors = Sensor::all(['id']);
        foreach ($sensors as $sensor) array_push($onlineStatuss, Cache::has('sensor-is-connect-' . $sensor->id) ? 1 : 0);
        $result = [
            "online" => count(array_filter($onlineStatuss, function($val) {return $val == 1;})),
            "from" => count($onlineStatuss)
        ];
        return $this->sendResponse($result);
    }

    public function widget()
    {
        $sensors = Sensor::select('id', 'name_sensor')->get();
        return $this->sendResponse($sensors);
    }
}
