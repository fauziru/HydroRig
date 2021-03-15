<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Api\APIBaseController;
use Illuminate\Http\Request;
use App\Models\PaymentMethod;
use Illuminate\Support\Facades\Auth;
use App\User;

class PaymentMethodController extends APIBaseController
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
            $this->link = route('payment.index');
            return $next($request);
        });
    }

    public function index()
    {
        $paymentmethod = PaymentMethod::all();
        return $this->sendResponse($paymentmethod);
    }

    public function store(Request $request)
    {
        try {
            $payment = PaymentMethod::create($request->only(['name_payment', 'api_key', 'status']));
            $this->sendNotif('membuat payment method baru');
            return $this->sendResponse($payment);
        } catch (\Throwable $th) {
            $payment->delete();
            return $this->sendError($th, 500);
        }
    }

    public function put(PaymentMethod $payment, Request $request)
    {
        $payment->name_payment = $request->name_payment;
        $payment->api_key = $request->api_key;
        $payment->save();
        $this->sendNotif('mengubah data Payment '.$payment->name_payment);
        return $this->sendResponse($payment,'edit data successfull');
    }

    public function stat(PaymentMethod $payment)
    {
        $payment->status = $payment->status ? 0 : 1;
        $payment->save();
        $this->sendNotif($payment->status ? 'mengaktifkan payment method '.$payment->name_payment : 'menonaktifkan payment method '.$payment->name_payment);
        return $this->sendResponse([], 'status successfull');
    }

    public function destroy(PaymentMethod $payment)
    {
        $payment->delete();
        $this->sendNotif('menghapus payment method '.$payment->name_payment);
        return $this->sendResponse([], 'deleted successfull');
    }
}
