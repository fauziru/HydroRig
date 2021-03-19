<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Api\APIBaseController;
use Illuminate\Http\Request;
use App\Models\Sensor;
use Cache;


class SensorController extends APIBaseController
{
    use AdminActivityTrait;

    public function index()
    {
        $sensor = Sensor::all();
        return $this->sendResponse($sensor);
    }

    public function store(Request $request)
    {
        try {
            $sensor = Sensor::create($request->only(['name_sensor', 'min_nutrisi']));
            $this->sendNotif('menambahkan sensor baru', '/sensor'.'/'.$sensor->id);
            return $this->sendResponse($sensor);
        } catch (\Throwable $th) {
            $sensor->delete();
            return $this->sendError('Terjadi kesalahan pada server', 500);
        }
    }

    public function put(Sensor $sensor)
    {
        $sensor->name_sensor = $request->name_sensor;
        $sensor->min_nutrisi = $request->min_nutrisi;
        $sensor->save();
        $this->sendNotif('mengubah data sensor '.$sensor->name_sensor, '/sensor'.'/'.$sensor->id);
        return $this->sendResponse($sensor,'edit data successfull');
    }

    public function destroy(Sensor $sensor)
    {
        $sensor->delete();
        $this->sendNotif('menghapus sensor '.$sensor->name_sensor);
        return $this->sendResponse([], 'deleted successfull');
    }

    public function connectStatus(Sensor $sensor)
    {
        $status = Cache::has('sensor-is-connect-' . $sensor->id) ? 'connected' : 'disconnected';
        $lastRead = $sensor->last_read;
        return $this->sendResponse(['status'=> $status, 'lastRead'=> $lastRead]);
    }
}
