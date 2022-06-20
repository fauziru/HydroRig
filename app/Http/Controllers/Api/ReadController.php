<?php

namespace App\Http\Controllers\Api;

use App\Events\UpdateNode;
use App\Events\UpdateSensor;
use App\Http\Resources\NodeResponse;
use Illuminate\Http\Request;
use App\Models\Sensor;
use App\Models\Node;
use App\Models\Read;
use App\User;
use App\Http\Resources\Sensor as SensorResource;
use App\Notifications\ReachThreshold;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use Cache;
use Notification;

class ReadController extends APIBaseController
{
    public function messageSen($tipe): string
    {
        switch ($tipe) {
            case 'tds':
                return 'Konsentrasi nutrisi';
            case 'tds_t':
                return 'Suhu larutan';
            case 'hum':
                return 'Kelembaban';
            case 'temp':
                return 'Suhu lingkungan';
            case 'light':
                return 'Konsentrasi cahaya';
            case 'height':
                return 'Ketinggian Muka air';
            default:
                return 'unknown';
        }
    }

    public function messageTh($reachMax): string
    {
        return $reachMax ? ' diatas batas maksimum!' : ' dibawah batas minimum!';
    }

    public function store(Node $node, Request $request)
    {
        // update cache
        $users = User::select(['id'])->get();
        $expiresAt = Carbon::now()->addSeconds(3);
        Cache::put('node-is-connect-'.$node->id, true, $expiresAt);
        $node->update(['status' => 1]);
        $sensors = $node->sensors;
        // tds, tds_t, hum, temp, lux, height
        $values = json_decode($request->read);

        foreach ($sensors as $sensor) {
            $field = $sensor->tipe;
            $readValue = 0;
            // var_dump($field);
            // var_dump($values);
            if(isset($values->$field)){
                // var_dump($field);
                $readValue = $values->$field;
            } else {
                break;
            }
            $readSensor = Read::create(['sensor_id' => $sensor->id,'read' => $readValue]);
            $reachMin = $readValue < json_decode($sensor->threshold)->min;
            $reachMax = $readValue > json_decode($sensor->threshold)->max;

            Sensor::where('id', $sensor->id)->update([
                'last_read' => $readValue,
                'last_read_time' => (new \DateTime())->format("Y-m-d H:i:s")
            ]);

            // cek threshold
            if ($reachMax or $reachMin){
                // cek apakah sudah pernah mengirim notifikasi
                $cacheKey = 'sensor-notif-sent-'.$sensor->id;
                $isNotifSent = \Illuminate\Support\Facades\Cache::has($cacheKey);
                if (!$isNotifSent) {
                    $item = [
                        'title' => 'Penting!!',
                        'message' => '<span class="primary--text">'.$sensor->name_sensor.'</span> &mdash; '.$this->messageSen($sensor->tipe).' pada titik '.$sensor->name_sensor.$this->messageTh($reachMax),
                        'body' => $sensor->name_sensor.', '.$this->messageSen($sensor->tipe).' pada titik '.$sensor->name_sensor.$this->messageTh($reachMax),
                        'link' => '/sensor'.'/'.$sensor->uuid
                    ];
                    // notifikasi
                    Notification::send($users, new ReachThreshold($item));
                    $expiresAt = Carbon::now()->addMinutes(5);
                    \Illuminate\Support\Facades\Cache::put($cacheKey, true, $expiresAt);
                    $node->update(['status' => 0]);
                }
                $node->update(['status' => 0]);
            }
            // update sensor
            event(new UpdateSensor(new SensorResource($sensor)));
            MQTT::publish('events/sensor', json_encode(new SensorResource($sensor)));
        }
        // update node
        event(new UpdateNode(new NodeResponse($node)));
        // MQTT::publish();
        return $request->read;
    }

    public function showWidget(Sensor $sensor): \Illuminate\Http\JsonResponse
    {
        return $this->sendResponse($this->wrapData($sensor->read->take(50), $sensor));
    }

    public function showDetail(Sensor $sensor, $filter, $from = null, $to = null): \Illuminate\Http\JsonResponse
    {
        $filterArray = ['today' => 1, 'this-week' => 8, 'this-month' => 31, 'date' => 1];

        if (!$filterArray[$filter]) return $this->sendError([], 404);

        $filter === 'date'
            ? $dataReadSensor = DB::table('reads')
                    ->where('sensor_id', '=', $sensor->id)
                    ->whereBetween('created_at', [$from, $to])
                    ->get()
            : $dataReadSensor = DB::table('reads')
                    ->where('sensor_id', '=', $sensor->id)
                    ->whereDate('created_at', '>', Carbon::now()->subDays($filterArray[$filter]))
                    ->get();

        return $this->sendResponse($this->wrapData($dataReadSensor, $sensor));
    }

    private function wrapData($ReadSensors, $sensor): array
    {
        $arrSeries = [];
        $arrCategories = [];

        foreach ($ReadSensors as $read){
            array_push($arrSeries, $read->read);
            array_push($arrCategories, Carbon::parse($read->created_at)->setTimezone('Asia/Jakarta')->format('Y-m-d H:i:s'));
        }

        return $item = [
            'sensor_data' => new SensorResource($sensor),
            'series_data' => $arrSeries,
            'categories_data' => $arrCategories
        ];
    }
}
