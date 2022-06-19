<?php

namespace App\Http\Controllers\Api;

use App\Events\UpdateSensor;
use App\Jobs\QueueUserNotificationsJob;
use Illuminate\Http\Request;
use App\Models\Sensor;
use App\Http\Resources\Sensor as SensorResource;
use Cache;


class SensorController extends APIBaseController
{

    public function index(): \Illuminate\Http\JsonResponse
    {
        $sensors = SensorResource::collection(Sensor::with('node')->get());
        return $this->sendResponse($sensors);
    }

    public function store(Request $request): \Illuminate\Http\JsonResponse
    {
        $sensor = Sensor::create($request->only(['name_sensor', 'min_nutrisi']));
        try {
            event(new UpdateSensor(new SensorResource($sensor)));
            return $this->sendResponse(new SensorResource($sensor));
        } catch (\Throwable $th) {
            $sensor->delete();
            return $this->sendError('Terjadi kesalahan pada server', 500);
        }
    }

    public function put(Sensor $sensor, Request $request): \Illuminate\Http\JsonResponse
    {
        $sensor->name_sensor = $request->name_sensor;
        $sensor->threshold = json_encode($request->batas);
        $sensor->save();
        event(new UpdateSensor(new SensorResource($sensor)));
        dispatch(new QueueUserNotificationsJob('memperbarui sensor baru', '/sensor/'.$sensor->uuid));
        return $this->sendResponse(new SensorResource($sensor),'edit data successfull');
    }

    public function destroy(Sensor $sensor): \Illuminate\Http\JsonResponse
    {
        $sensor->delete();
        return $this->sendResponse([], 'deleted successfull');
    }

    public function connectStatus(Sensor $sensor): \Illuminate\Http\JsonResponse
    {
        $status = Cache::has('sensor-is-connect-' . $sensor->id) ? 1 : 0;
        $lastRead = $sensor->last_read;
        return $this->sendResponse(['status'=> $status, 'lastRead'=> $lastRead]);
    }

    public function connectStatusAll(): \Illuminate\Http\JsonResponse
    {
        $onlineStatuss = [];
        $sensors = Sensor::all(['node_id']);
        foreach ($sensors as $sensor) array_push($onlineStatuss, Cache::has('node-is-connect-' . $sensor->node_id) ? 1 : 0);
        $result = [
            "online" => count(array_filter($onlineStatuss, function($val) {return $val == 1;})),
            "from" => count($onlineStatuss)
        ];
        return $this->sendResponse($result);
    }

    public function widget(): \Illuminate\Http\JsonResponse
    {
        $sensors = Sensor::select('id', 'name_sensor')->get();
        return $this->sendResponse($sensors);
    }
}
