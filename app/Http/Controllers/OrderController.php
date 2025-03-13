<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class OrderController extends Controller
{
    public function index()
    {
        $orders = Order::where('user_id', Auth::id())
            ->where('payment_status', 'paid') // Filter only paid orders
            ->latest()
            ->get();
        return view('orders.index', compact('orders'));
    }
}
