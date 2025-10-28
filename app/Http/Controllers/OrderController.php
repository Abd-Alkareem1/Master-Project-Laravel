<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\OrderList;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    //
    public function list() {
        $orders = Order::when(request('key'), function($query) {
            $query->whereHas('user', function($userQuery) {
                $userQuery->where('name', 'like', '%' . request('key') . '%');
            });
        })
        ->orderBy('created_at', 'desc')
        ->paginate(4);
        return view('admin.order.orderList', compact('orders'));
    }

    public function orderList($id) {
        $orderList = OrderList::where('order_id', $id)->get();
        return view('orderList.orderList', compact('orderList'));
    }
}
