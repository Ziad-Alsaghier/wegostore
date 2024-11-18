<?php

namespace App\service\order;

use App\Models\Payment;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Log;

trait placeOrder
{
    // This Traite About Place Order
    protected $paymentRequest = ['user_id', 'plan_id', 'payment_method_id', 'transaction_id', 'description', 'invoice_image', 'status'];
    protected $orderRequest = ['user_id', 'cart'];
    public function placeOrder($request, $user)
    {

        // Start Make Payment
        $paymentRequest = $request->only($this->paymentRequest);
        try {
            $activePaymentMethod = $this->paymentMethod->where('status', '1')->find($paymentRequest['payment_method_id']);
            if (!$activePaymentMethod) {
                return response()->json([
                    'paymentMethod.message' => 'This Payment Method Unavailable ',
                ], 404);
            }
            $user_id = $user->id;
            $paymentRequest['user_id'] = $user_id;
            
            $payment = $this->payment->create($paymentRequest); // Start Create Payment 
        } catch (\Throwable $th) {
            throw new HttpResponseException(response()->json(['error' => 'Payment processing failed'], 500));
        }
        // End Make Payment

        try {

            // Start Make Order For Payment
            $orderItems = $request->only($this->orderRequest); // Get Reqeust Of Order
            $cart = $orderItems['cart'] ?? null; // Check Cart

            if (!$cart) {
                return response()->json(['error' => 'Cart data is missing'], 422);
            }
            $data = [
                'user_id' => $user_id,
                'payment_id' => $payment->id ?? null, // Ensure payment ID is available or defaulet to null
            ];
            // Array to store created orders for response
            $createdOrders = [];
            // Step 2: Handle creating orders for each extra_id
            if (!empty($cart['extra'])) {
                $createdOrders = array_merge($createdOrders, $this->createOrdersForItems($cart['extra'], 'extra_id', $data));
            }
            // Step 3: Handle creating orders for each domain_id
            if (!empty($cart['domain'])) {
                $createdOrders = array_merge($createdOrders, $this->createOrdersForItems($cart['domain'], 'domain_id', $data));
            }
            if (!empty($cart['plan'])) {
              
                $createdOrders = array_merge($createdOrders, $this->createOrdersForItems($cart['plan'], 'plan_id', $data));
            }
            // Step 4: Return a response with all created orders
            // End Make Order For Payment
        } catch (\Throwable $th) {
            throw new HttpResponseException(response()->json(['error' => 'Order processing failed'], 500));
        }

        return [
            'payment'=>$payment,
            'orderItems'=>$createdOrders,
        ];
    }



    private function createOrdersForItems(array $items, string $field, array $baseData)
    {
        $createdOrders = [];
        $total = 0;
        $count = 0;
        foreach ($items as $item) {
            $itemName = $field == 'extra_id' ? 'extra' : 'domain';
            $orderData = array_merge($baseData, [$field => $item[$field]]);
            $createdOrder = $this->order->create($orderData);
            $model = $this->$itemName->find($item[$field]);
            // $total = $model->pluk('price')->sum();
            $quantity = $count + 1;
            $item = [
                'name' => $itemName,
                'amount_cents' => $model->price,
                'quantity' => $quantity ,
                'description' => "Yor Item Is $model->name and Price : $model->price",
            ];

            $createdOrders[] = $item;
        }

        return $createdOrders;
    }
    public function payment_approve ($payment){
        if($payment){
            $payment->update(['status'=>'approved']);
            return true;
        }
        return false ;
    }
    public function order_success($payment){
         $payment;
        $payment_approved =$this->payment_approve($payment);
        $orders = $payment->orders;
           $createdOrders = [];
           $newService = [];
        foreach ($orders as $order) {
            if($order->domain_id != Null){
                $domain_id = $order->domain_id; 
                $newService['domain'] = $this->domain->where('id',$domain_id)->get();
                $createdOrders[]=$newService;
            }elseif($order->extra_id != Null){
                     $extra_id = $order->extra_id;
                     $newService['extra'] = $this->extra->where('id',$extra_id)->get();
                     $createdOrders[]=$newService;
            }
        }
        return $createdOrders;
    }
}
