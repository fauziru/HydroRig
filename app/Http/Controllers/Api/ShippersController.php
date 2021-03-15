<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Api\APIBaseController;
use Illuminate\Http\Request;
use App\Models\Shipper;
use Illuminate\Support\Facades\Auth;
use App\User;

class ShippersController extends APIBaseController
{
    use AdminActivityTrait;

    private $adminName;
    private $link;
    private $usersAdmin;

    public function __construct()
    {
        $this->middleware(function ($request, $next) {
            $this->adminName = Auth::user()->name;
            $this->usersAdmin = User::where('role', 'admin')->get();
            $this->link = route('shipper.index');
            return $next($request);
        });
    }

    public function index()
    {
        $shipper = Shipper::all();
        return $this->sendResponse($shipper);
    }

    public function store(Request $request)
    {
        try {
            $shipper = Shipper::create($request->only(['name_shipper', 'api_key', 'status']));
            $this->sendNotif('membuat ekspedisi baru');
            return $this->sendResponse($shipper);
        } catch (\Throwable $th) {
            $shipper->delete();
            return $this->sendError('kesalahan pada server', 500);
        }
    }

    public function put(Shipper $shipper, Request $request)
    {
        $shipper->name_shipper = $request->name_shipper;
        $shipper->api_key = $request->api_key;
        $shipper->save();
        $this->sendNotif('mengubah data ekspedisi '.$shipper->name_shipper);
        return $this->sendResponse($shipper,'edit data successfull');
    }

    public function stat(Shipper $shipper)
    {
        $shipper->status = $shipper->status ? 0 : 1;
        $shipper->save();
        $this->sendNotif($shipper->status ? 'mengaktifkan data ekspedisi '.$shipper->name_shipper : 'menonaktifkan data ekspedisi '.$shipper->name_shipper);
        return $this->sendResponse([], 'status successfull');
    }

    public function destroy(Shipper $shipper)
    {
        $shipper->delete();
        $this->sendNotif('menghapus data ekspedisi '.$shipper->name_shipper);
        return $this->sendResponse([], 'deleted successfull');
    }
}
