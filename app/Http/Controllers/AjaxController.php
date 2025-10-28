<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\Order;
use App\Models\Product;
use App\Models\OrderList;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AjaxController extends Controller
{
    //
    public function pizzaList(Request $request) {
        if($request->status == 'asc') {
            $pizzas = Product::orderBy('created_at', 'asc')->get();
        } else if($request->status = 'desc') {
            $pizzas = Product::orderBy('created_at', 'desc')->get();
        } else {
            $pizzas = Product::orderBy('view_count', 'asc')->get();
        }
        return response()->json($pizzas, 200);
    }

    public function autoAddToCart(Request $request) {
        $userId = Auth::user()->id; 
        $count = 1 ; 
        $productId = intval($request->pizzaId);
        $data = [ 'user_id' => $userId, 'product_id' => $productId, 'quantity' => $count ];
        Cart::create($data);
        $response = [
            'message' => 'Item added to the cart successfully',
            'status' => 'success',
        ];
        return response()->json($response, 200);
    }

    public function addToCart(Request $request) {
        $data = $this->getData($request);
        Cart::create($data);
        $response = [
            'message' => 'Item Added to the cart successfully !!',
            'status' => 'success',
        ];
        return response()->json($response, 200);
    }

    public function order(Request $request) {
            $orderData = $this->getOrderData($request->order);
            $total = 0;
            $order = Order::create($orderData);
            foreach($request->orderList as $item) {
                $data = OrderList::create([
                    'user_id' => $item['user_id'],
                    'product_id' => $item['product_id'],
                    'qty' => $item['qty'],
                    'total' => $item['total'],
                    'orderCode' => $item['order_code'],
                    'order_id' => $order['id'],
                ]);
                $total += $data->total;
            }
            Cart::where('user_id', Auth::user()->id)->delete();
            $response = [
                'message' => 'Order created successfully',
                'status' => 'success'
            ];
            return response()->json($response, 200);
    }

    public function filterOrders(Request $request) {
        if($request->status == 'all' ) {
            $orders = Order::join('users', 'orders.user_id', '=', 'users.id')
            ->join('order_lists', 'orders.id', '=', 'order_lists.order_id')
            ->select('orders.*', 'users.name')
            ->withCount('orderLists')
            ->orderBy('created_at', 'desc')
            ->distinct()
            ->get();

            return response()->json($orders, 200);
        }
        else {
            $orders = Order::where('status', $request->status)
            ->join('users', 'orders.user_id', '=', 'users.id')
            ->join('order_lists', 'orders.id', '=', 'order_lists.order_id')
            ->select('orders.*', 'users.name')
            ->withCount('orderLists')
            ->orderBy('created_at', 'desc')
            ->distinct()
            ->get();
            return response()->json($orders, 200);
        } 
    }

    public function clearCart() {
        Cart::where('user_id', Auth::user()->id)->delete();
    }

    public function remove(Request $request) {
        Cart::where('product_id', $request->productId)
                    ->where('user_id', Auth::user()->id)
                    ->delete();
    }

    public function orderStatus(Request $request) {
        $orderId = $request->orderId;
        Order::where('id', $orderId)->update(['status' => $request->orderStatus]);
    }













    private function getData($request) {
        return [
            'user_id' => $request->userId,
            'product_id'=> $request->pizzaId,
            'quantity' => $request->count,
        ];
    }

    private function getOrderData($request) {
        return [
            'user_id' => $request['user_id'],
            'total_price' => $request['total_price'],
        ];
    }
}
