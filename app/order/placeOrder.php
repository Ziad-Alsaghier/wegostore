<?php

namespace App\order;

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
            $paymentRequest['transaction_id'] = $this->generateUniqueTransactionId();
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
            if (!empty($cart['extra_id'])) {
                $createdOrders = array_merge($createdOrders, $this->createOrdersForItems($cart['extra_id'], 'extra_id', $data));
            }
            
            // Step 3: Handle creating orders for each domain_id
            if (!empty($cart['domain_id'])) {
                $createdOrders = array_merge($createdOrders, $this->createOrdersForItems($cart['domain_id'], 'domain_id', $data));
            }
            // Step 4: Return a response with all created orders

          
            // End Make Order For Payment
        } catch (\Throwable $th) {
                throw new HttpResponseException(response()->json(['error' => 'Order processing failed'], 500));
        }
        
            return $createdOrders; 
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


    private function createOrdersForItems(array $items, string $field, array $baseData)
    {
        $createdOrders = [];
        $total= 0;
        foreach ($items as $item) {
            $itemName = $field == 'extra_id' ? 'extra' : 'domain';
            $orderData = array_merge($baseData, [$field => $item,]);
            $createdOrder = $this->order->create($orderData);
            $model = $this->$itemName->find($item);
            // $total = $model->pluk('price')->sum();
            $item= [
            'name'=> $itemName,
            'amount_cents'=> $model->price,
            'quantity'=> '1',
            'description'=> "Yor Item Is $item and Price : $model->price",
            ];
            
            $createdOrders[] = $item;
        }
        
        return $createdOrders;
    }
}
