<?php

namespace App\Http\Controllers\Api;

use Illuminate\Support\Facades\Storage;

class DashboardController extends APIBaseController
{
    public function showImage($storage, $fileName)
    {
        // echo($fileName);
        // echo($storage);
        // echo asset('images/'.$fileName);
        // $url = Storage::url($fileName);
        return Storage::get($storage."/".$fileName);
    }
}
