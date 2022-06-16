<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Models\Layout;
use App\Models\Setting;
use App\Http\Resources\LayoutResponse;
use App\Traits\ImageHandler;
use App\Jobs\QueueUserNotificationsJob;
use App\Events\UpdateLayout;

class LayoutController extends APIBaseController
{
    use ImageHandler;
    public function index()
    {
        $layouts = LayoutResponse::collection(Layout::all());
        $setting = Setting::first();
        $id_layout = null;
        if ($setting->layout) {
            $id_layout = $setting->layout->uuid;
        }
        $response = [ 'layouts' => $layouts, 'selectedLayouts' => $id_layout];
        return $this->sendResponse($response);
    }

    public function store(Request $request)
    {
        $this->validate($request,[
            'name_layout'=>'required',
            'image'=>'required'
        ]);

        $layout = Layout::create($request->only(['name_layout']));
        $setting = Setting::first();
        $fileName = '';

        // upload gambar
        if($file = $request->file('image'))
        {
            $fileName = $this->saveImage($file, 'layout');
        }

        $layout->file_name = $fileName;
        // update setting
        $setting->layout_id = $layout->id;
        $layout->save();
        $setting->save();
        dispatch(new QueueUserNotificationsJob('menambahkan layout baru '.$layout->name_layout, '/dashboard'));
        event(new UpdateLayout($fileName));
        return $this->sendResponse(new LayoutResponse($layout));
    }

    public function change(Layout $layout)
    {
        $setting = Setting::first();
        $setting->layout_id = $layout->id;
        $setting->save();
        dispatch(new QueueUserNotificationsJob('merubah layout', '/dashboard'));
        event(new UpdateLayout($setting->layout->file_name));
        return $this->sendResponse($setting->layout->uuid);
    }

    public function destroy(Layout $layout)
    {
        $tempLayout = $layout;
        $layout->delete();
        $this->deleteImage($tempLayout->file_name, 'layout');
        dispatch(new QueueUserNotificationsJob('menghapus layout '.$tempLayout->name_layout , '/dashboard'));
        return $this->sendResponse($tempLayout->uuid);
    }
}
