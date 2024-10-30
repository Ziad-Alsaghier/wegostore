<?php

namespace App\Http\Controllers\api\v1\admin\order;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    // This Is About All Order 

    public function __construct(private Order $order) {}
    public function bindOrder()
    {
        URL : http://localhost/wegostore/public/admin/v1/order/show/pending
        $orders = $this->order->with(
        'users','users.plan','order_items.plans')->where('order_status',
        "pending")->get();
        try {
            empty($orders) == Null ? $data = $orders : $data = "Not Found any orders";
            return response()->json([
                'order.message' => 'data Returned Successfully',
                'order' => $data
            ]);
        } catch (\Throwable $th) {
            return response()->json(['payment.message' => 'Not Found any orders']);
        }
    }
}
