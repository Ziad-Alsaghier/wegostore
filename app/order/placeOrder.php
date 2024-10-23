<?php

namespace App\order;

use App\Models\Payment;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Support\Facades\Log;

trait placeOrder
{
    // This Traite About Place Order
    protected $paymentRequest = ['user_id', 'payment_method_id', 'transaction_id', 'description', 'invoice_image', 'status'];
    protected $order = ['user_id', 'payment_id', 'total_amount'];
    protected $orderItem = ['order_id', 'plan_id', 'store_id', 'price'];
    protected $store = ['store_name','activities_id'];
    public function placeOrder($request, $user)
    {

        $paymentRequest = $request->only($this->paymentRequest);
        $checkpaymentMethod = $this->paymentMethod->where('status', '1')->find($paymentRequest['payment_method_id']);
        if (!$checkpaymentMethod) {
            return response()->json([
                'paymentMethod.message' => 'This Payment Method Unavailable ',
            ],404);
        }
        try {
            $user_id = $user->id;
            $paymentRequest['user_id'] = $user_id;
            $paymentRequest['transaction_id'] = $this->generateUniqueTransactionId();
            $payment = $this->payment->create($paymentRequest); // Start Create Payment 

        } catch (\Throwable $th) {
              throw new HttpResponseException(response()->json(['error' => 'Payment processing failed'], 500));
        }
        // Start Make Order
        try {
            $order = $request->only($this->order);
            $order['user_id'] = $user_id;
            $order['payment_id'] = $payment->id;
            $createOrder = $payment->order()->create($order);
        } catch (\Throwable $th) {
        throw new HttpResponseException(response()->json(['error' => 'Order processing failed'], 500));
        }

        // End Make Order


     
        // Start Make Order Items
        try {
            $orderItem = $request->only($this->orderItem);
               // Start Create Store For User If Make Store
        $store = $request->only($this->store);
        $store['user_id'] = $user_id;
        if (!empty($store)) {
            $newStore= $this->storeUser;
            $createNewStore = $this->storeUser->create($store);
                $store['store_id'] = $createNewStore->id;
        }
        // End 
            $orderItem['price'] = $request->total_amount;
            $createOrder->order_items()->create($orderItem);
        } catch (\Throwable $th) {
        throw new HttpResponseException(response()->json(['error' => 'Order item creation failed'], 500));

        }
        // End Make Order Items
        return response()->json(['placeOrder.message'=>'Order Placed Successfully'],200);
    }

    private function generateUniqueTransactionId()
{
    do {
        $length = 6;
        $min = pow(10, $length - 1);
        $max = pow(10, $length) - 1;
        $randomNumber = random_int($min, $max);
        $exists = $this->payment->where('transaction_id', $randomNumber)->exists();
    } while ($exists);
    
    return $randomNumber;
}
}
