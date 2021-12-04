<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Api\APIBaseController;
use App\Models\Node;
use App\Models\Sensor;
use App\Http\Resources\NodeResponse as NodeResponse;
use Illuminate\Http\Request;
use App\Jobs\QueueUserNotificationsJob;

class NodeController extends APIBaseController
{
    //

    protected $sensors = ['lux', 'nutrisi', 'suhu_larutan', 'suhu', 'rh'];

    public function index()
    {
        # code...
    }

    public function store(Request $request)
    {
        $index = count(Node::all()) + 1;
        try {
            $request->merge(['name_node' => $request->name_node." ".$index]);
            $node = Node::create($request->only(['name_node', 'lat', 'lng']));

            // bikin setiap sensor yang tersedia pada node
            // ['lux', 'nutrisi', 'rh & suhu']
            foreach ($this->sensors as $value) {
                $arr = [
                    'node_id'  => $node->id,
                    'name_sensor' => $value."-".$index,
                    'threshold' => json_encode([
                        "max" => 0,
                        "min" => 0
                    ])
                ];
                $sensor = Sensor::create($arr);
            }
            dispatch(new QueueUserNotificationsJob('menambahkan node baru', '/node/'.$node->uuid));
            return $this->sendResponse(new NodeResponse($node));
        } catch (\Throwable $th) {
            echo ($th);
            return $this->sendError('Terjadi kesalahan pada server', 500);
        }
    }
    
    public function update(Node $node, Request $request)
    {
        // print_r($node->name_node);
        $node->name_node = $request->name_node;
        $node->lat = $request->lat;
        $node->lng = $request->lng;
        $node->save();
        dispatch(new QueueUserNotificationsJob('mengubah data node '.$node->name_node, '/node/'.$node->uuid));
        return $this->sendResponse(new NodeResponse($node));
    }

    public function destroy(Node $node, Request $request)
    {
        // print_r($node);
        // delete sensor
        $node->reads()->delete();
        $node->sensors()->delete();
        dispatch(new QueueUserNotificationsJob('menghapus node '.$node->name_node, '#'));
        $node->delete();
        return $this->sendResponse([], 'deleted successfull');
    }

}
