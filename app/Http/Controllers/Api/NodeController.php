<?php

namespace App\Http\Controllers\Api;

use App\Models\Node;
use App\Models\Sensor;
use App\Models\Setting;
use App\Events\UpdateNode;
use App\Events\DeleteNode;
use App\Http\Resources\NodeResponse as NodeResponse;
use App\Http\Resources\DashboardResponse as DashboardNodeResponse;
use Illuminate\Http\Request;
use App\Jobs\QueueUserNotificationsJob;
use PhpMqtt\Client\Facades\MQTT;

class NodeController extends APIBaseController
{
    //

    protected $sensors = ['tds', 'tds_t', 'temp', 'hum', 'light', 'height'];

    public function index(): \Illuminate\Http\JsonResponse
    {
        $nodes = NodeResponse::collection(Node::with('sensors')->get());
        $setting = Setting::first();
        $file_name = null;
        if ($setting->layout) {
            $file_name = $setting->layout->file_name;
        }
        $response = ['nodes' => $nodes, 'layoutUrl' => $file_name];
        return $this->sendResponse($response);
    }

    public function show(Node $node): DashboardNodeResponse
    {
        $node->load('sensors');
        $node->sensors->load('read');
        return new DashboardNodeResponse($node);
    }

    public function store(Request $request): \Illuminate\Http\JsonResponse

    {
        try {
            $request->merge(['name_node' => $request->name_node]);
            $node = Node::create($request->only(['name_node', 'lat', 'lng']));

            $sensors_to_create = $request->get('sensors');
            // $sensors_to_create = array_merge(array_flip($this->sensors), $request->only(['sensors']));
            // bikin setiap sensor yang tersedia pada node
            // ['lux', 'nutrisi', 'rh & suhu']
            foreach ($sensors_to_create as $value) {
                $arr = [
                    'node_id'  => $node->id,
                    'name_sensor' => $value."-".$node->name_node,
                    'threshold' => json_encode([
                        "max" => 0,
                        "min" => 0
                    ]),
                    'tipe' => $value
                ];
                $sensor = Sensor::create($arr);
            }
            dispatch(new QueueUserNotificationsJob('menambahkan node baru', '/node/'.$node->uuid));
            // event(new UpdateNode(new NodeResponse($node)));
            MQTT::publish('events/UpdateNode', json_encode(new NodeResponse($node)));
            return $this->sendResponse(new NodeResponse($node));
        } catch (\Throwable $th) {
            echo ($th);
            return $this->sendError('Terjadi kesalahan pada server', 500);
        }
    }
    public function update(Node $node, Request $request): \Illuminate\Http\JsonResponse
    {
        // print_r($node->name_node);
        $node->name_node = $request->name_node;
        $node->lat = $request->lat;
        $node->lng = $request->lng;
        $node->save();
        dispatch(new QueueUserNotificationsJob('mengubah data node '.$node->name_node, '/node/'.$node->uuid));
        event(new UpdateNode(new NodeResponse($node)));
        return $this->sendResponse(new NodeResponse($node));
    }

    public function destroy(Node $node, Request $request): \Illuminate\Http\JsonResponse
    {
        // print_r($node);
        // delete sensor
        $node->reads()->delete();
        $node->sensors()->delete();
        dispatch(new QueueUserNotificationsJob('menghapus node '.$node->name_node, '#'));
        event(new DeleteNode(new NodeResponse($node)));
        $node->delete();
        return $this->sendResponse([], 'deleted successfull');
    }

}
