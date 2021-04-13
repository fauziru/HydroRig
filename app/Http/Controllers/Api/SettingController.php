<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Api\APIBaseController;
use Illuminate\Http\Request;
use App\Models\Setting;

class SettingController extends APIBaseController
{
    use AdminActivityTrait;

    protected $settings;

    public function __construct()
    {
        $this->settings = Setting::first();
    }

    public function show($type)
    {
        return $this->sendResponse($this->settings[$type]);
    }

    public function generate($type)
    {
        $generateAble = [ 'api_key', 'registrasi_key'];
        // jika tidak cocok, send error
        if(!in_array($type, $generateAble)) return $this->sendError('Generate gagal', 500);
        $this->settings[$type] = \Illuminate\Support\Str::random(32);
        $this->settings->save();
        return $this->sendResponse($this->settings[$type]);
    }
}
