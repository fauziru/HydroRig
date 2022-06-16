<?php

namespace App\Http\Controllers\Api;

use App\Models\Setting;
use App\Jobs\QueueUserNotificationsJob;

class SettingController extends APIBaseController
{

    protected $settings;

    public function __construct()
    {
        $this->settings = Setting::first();
    }

    public function show($type): \Illuminate\Http\JsonResponse
    {
        return $this->sendResponse($this->settings[$type]);
    }

    public function generate($type): \Illuminate\Http\JsonResponse
    {
        $generateAble = [ 'api_key', 'registrasi_key'];
        $arr = [
            'api_key' => ['title' => 'Api Key', 'link' => 'apikey'],
            'registrasi_key' => ['title' => 'Registrasi Key', 'link' => 'registrasikey']
        ];
        // jika tidak cocok, send error
        if(!in_array($type, $generateAble)) return $this->sendError('Generate gagal', 500);
        $this->settings[$type] = \Illuminate\Support\Str::random(32);
        $this->settings->save();
        dispatch(new QueueUserNotificationsJob('mengenerate kode baru '.$arr[$type]['title'], '/setting/'.$arr[$type]['link']));
        // $this->sendNotif('mengenerate kode baru '.$arr[$type]['title'], '/setting'.'/'.$arr[$type]['link']);
        return $this->sendResponse($this->settings[$type]);
    }

    public function updateMaintenance(): \Illuminate\Http\JsonResponse
    {
        $this->settings->update(['is_maintenance' => !$this->settings->is_maintenance]);
        return $this->sendResponse();
    }

    public function getMeta(): \Illuminate\Http\JsonResponse
    {
        $setting = $this->settings;
        return $this->sendResponse([
            'isMaintenance' => $setting->is_maintenance === 1,
            'version' => $setting->version
        ]);
    }
}
