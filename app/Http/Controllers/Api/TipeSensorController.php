<?php

namespace App\Http\Controllers\Api;

use App\Models\TipeSensor as ModelsTipeSensor;

class TipeSensorController extends APIBaseController
{
    public function index()
    {
        $tipes = ModelsTipeSensor::all();
        return $this->sendResponse($tipes);
    }
}
