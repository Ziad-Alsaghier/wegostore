<?php

namespace App\order;

use App\Models\Payment;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Log;

trait placeOrder
{
    // This Traite About Place Order
    protected $paymentRequest = ['user_id', 'plan_id','payment_method_id', 'transaction_id', 'description', 'invoice_image', 'status'];
    protected $store = ['store_name', 'activities_id'];
    public function placeOrder($request, $user): JsonResponse
    {

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
            $createOrder = $payment->order_payment()->create($order);
        } catch (\Throwable $th) {
            throw new HttpResponseException(response()->json(['error' => 'Order processing failed'], 500));
        }
        // End Make Order
        // Start Make Order Items
        try {
            $orderItem = $request->only($this->orderItem);
         
            
            // return response()->json($orderItem);
             // Start Create Store For User If Make Store
                    $store = $request->only($this->store);
                    $store['user_id'] = $user_id;
                    if (!$store) {
                    $newStore = $this->storeUser;
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
        return response()->json(['placeOrder.message' => 'Order Placed Successfully'], 200);
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
