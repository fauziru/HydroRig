<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User;
use App\Models\Category;
use App\Models\SubCategory;
use App\Models\ChildCategory;
use App\Models\Shipper;
use App\Models\PaymentMethod;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        return view('dashboard.index');
    }

    public function indexUser()
    {
        $users = User::all();
        return view('user.index', compact('users'));
    }

    public function indexNotification()
    {
        $orderNotifications = Auth()->user()->Notifications->where('type', 'App\Notifications\OrderanMasuk')->all();
        $allNotifications = Auth()->user()->Notifications;
        return view('notification.index', compact('orderNotifications', 'allNotifications'));
    }

    public function indexCategory()
    {
        $category = Category::all();
        return view('category.index', compact('category'));
    }

    public function indexSubCategory()
    {
        $subcategory = SubCategory::all();
        return view('category.sub', compact('subcategory'));
    }

    public function indexChildCategory()
    {
        $childcategory = ChildCategory::all();
        return view('category.child', compact('childcategory'));
    }

    public function indexShipper()
    {
        $shippers = Shipper::all();
        return view('shipper.index', compact('shippers'));
    }

    public function indexPayment()
    {
        $payments = PaymentMethod::all();
        return view('payment.index', compact('payments'));
    }
}
