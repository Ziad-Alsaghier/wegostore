<?php

namespace App\Http\Controllers\api\v1\admin\order;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Payment;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    // This Is About All Order 

    public function __construct(
        private Payment $payment,
        private Order $order
    ) {}
    public function bindOrder()
    {
        URL : http://localhost/wegostore/public/admin/v1/order/show/pending
       
        try {
             $payments = $this->payment->with(['orders' => function($query){
                $query->with(['plans', 'domain', 'extra']);
             }, 'user'])->get();  
            return response()->json([
                'order.message' => 'data Returned Successfully',
                'order' => $payments
            ],200);
        } catch (\Throwable $th) {
            return response()->json(['payment.message' => 'Not Found any orders'],500);
        }
    }
    public function orderHistory()
    {
        URL : http://localhost/wegostore/public/admin/v1/order/show/pending
       
        try {
             $payments = $this->payment->with('orders')->where('status','pending')->get();
             $orders = $payments->collect()->pluck('orders');
          $data =  count($orders) > 1 ?  $orders : "Not Found any orders";
            return response()->json([
                'order.message' => 'data Returned Successfully',
                'order' => $data
            ]);
        } catch (\Throwable $th) {
            return response()->json([
                'payment.message' => 'Something Wrong In Orders',
                'error'=>$th->getMessage(),
            ],500);        }
    }   
    public function all_orders()
    {
        URL : http://localhost/wegostore/public/admin/v1/order/show/pending
       
        try {
             $payments = $this->payment->with('orders')->get();
               $orders = $payments->collect()->pluck('orders');
          $data =  count($orders) >= 1 ?  $orders : "Not Found any orders";
            return response()->json([
                'order.message' => 'data Returned Successfully',
                'order' => $data
            ]);
        } catch (\Throwable $th) {
            return response()->json([
                'payment.message' => 'Something Wrong In Orders',
                'error'=>$th->getMessage(),
            ],500);        }
    }   

        public function process_action($id,Request $request){
            try {
            $order_status = $request->order_status;
                  $order = $this->order->where('id',$id)->first();
            $orderUpdate = $order->update(['order_status'=>$order_status]);
            if($orderUpdate){
            return response()->json([
                'order.success'=>'Change Order Status Successfully',
            ]);
            }
            } catch (\Throwable $th) {
            throw new HttpResponseException(response()->json([
                'order.faield'=>'Something Wron In Process Update Order',
            ]));
            }
        }
}
