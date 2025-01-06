<?php

namespace App\services\order;

use App\Models\Payment;
use App\services\ExpireDate;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

trait placeOrder
{
    use ExpireDate;
    // This Traite About Place Order
    protected $paymentRequest = ['user_id', 'plan_id', 'payment_method_id', 'transaction_id', 'description', 'invoice_image', 'status'];
    protected $orderRequest = ['user_id', 'cart'];
    protected $priceCycle;
    public function placeOrder($request, $user, $orderType)
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
            $paymentRequest['status'] = "rejected";
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
            $createdOrders = [];
            if ($orderType == 'plan') {
                if (!empty($cart['plan'])) {

                    $createdOrders = array_merge($createdOrders, $this->createOrdersForItems($cart['plan'], 'plan_id', $data));
                }
            } else {
                if (!empty($cart['plan'])) {

                    $createdOrders = array_merge(
                        $createdOrders,
                        $this->createOrdersForItems($cart['plan'], 'plan_id', $data)
                    );
                }
                // Step 2: Handle creating orders for each extra_id
                if (!empty($cart['extra'])) {
                    $createdOrders = array_merge($createdOrders, $this->createOrdersForItems($cart['extra'], 'extra_id', $data));
                }
                // Step 3: Handle creating orders for each domain_id
                if (!empty($cart['domain'])) {
                    $createdOrders = array_merge($createdOrders, $this->createOrdersForItems(
                        $cart['domain'],
                        'domain_id',
                        $data
                    ));
                }
            }
        } catch (\Throwable $th) {
            throw new HttpResponseException(response()->json(['error' => 'Order processing failed'], 500));
        }

        return [
            'payment' => $payment,
            'orderItems' => $createdOrders,
        ];
    }



    private function createOrdersForItems(array $items, string $field, array $baseData)
    {

        $createdOrders = [];
        $count = 1;
        foreach ($items as $item) {
            // Ensure $item is an array
            // return $items; 
            if (!is_array($item)) {
                throw new \InvalidArgumentException("Each item should be an array.");
            }
            $periodPrice = $item['price_cycle'];

            // Determine the model based on the $field
            $itemName = match ($field) {
                'extra_id' => 'extra',
                'domain_id' => 'domain',
                'plan_id' => 'plan',
                default => throw new \InvalidArgumentException("Invalid field provided: $field"),
            };
            $model = $this->$itemName->find($item[$field]);
            $this->priceCycle = $model->$periodPrice ?? $model->price;
            // Prepare the order data

            $orderData = array_merge($baseData, [
                $field => $item[$field],
                'price_cycle' => $periodPrice, // Add price_cycle here
                'price_item' => $this->priceCycle, // Add price_item here
            ]);

            // Validate if item has the field key
            if (!isset($item[$field])) {
                throw new \InvalidArgumentException("Missing $field key in item.");
            }
            // Create the order and retrieve the model
            $createdOrder = $this->order->create($orderData);
            // Prepare the item data
            $itemData = [
                'name' => $model->name,
                'amount_cents' => $this->priceCycle ?? $model->price,
                'period' => $item['price_cycle'],
                'quantity' => $count,
                'description' => "Your Item is $model->name and Price: " . $this->priceCycle ?? $model->price,
            ];

            $createdOrders[] = $itemData;
        }

        return $createdOrders;
    }



    public function payment_approve($payment)
    {
        if ($payment) {
            $payment->update(['status' => 'approved']);
            return true;
        }
        return false;
    }
    public function order_success($payment)
    {
        $payment_approved = $this->payment_approve($payment);
        // Retrieve orders related to the payment
        $orders = $payment->orders;
        $user = $payment->user;
        // Collect unique IDs for batch fetching
        $domainIds = $orders->whereNotNull('domain_id')->pluck('domain_id', 'price_cycle')->unique();
        $extraIds = $orders->whereNotNull('extra_id')->pluck('extra_id', 'price_cycle')->unique();
        $planIds = $orders->whereNotNull('plan_id')->pluck('plan_id')->unique();
        $plan_price_cycle = $orders->whereNotNull('price_cycle')->pluck('price_cycle')->unique();
        // Approved Domains
        if ($domainIds->isNotEmpty()) {
            $date = now();
            $renueDate =$date->addYear();
            
            $this->domain->whereIn('id', $domainIds)->update(['price_status' => true,'renewdate'=>$renueDate]);
        }
        if ($planIds->isNotEmpty()) {
            $expireDate = $this->getExpireDateTime($plan_price_cycle, now());
            $packate_cycle = $this->package_cycle($plan_price_cycle, now());
           foreach ($planIds as $key => $plan_id) {
                 $user->update([
                 'plan_id' => $plan_id,
                 'expire_date' => $expireDate,
                 'package' => $packate_cycle,
                 ]);
           }
        }
        // Update Order And Put Expire Date
    foreach($orders as $order){
            $priceCycle = $order->price_cycle ;
            $expireDate = $this->getOrderDateExpire($priceCycle,now());
            $order->update(['expire_date'=>$expireDate]);
    }
        // End Approved Domains   
        // Fetch all required services in batch only if IDs are present
        $domains = $domainIds->isNotEmpty() ? $this->domain->whereIn('id', $domainIds)->get()->keyBy('id') : collect();
        $extras = $extraIds->isNotEmpty() ? $this->extra->whereIn('id', $extraIds)->get()->keyBy('id') : collect();
        $plans = $planIds->isNotEmpty() ? $this->plan->whereIn('id', $planIds)->get()->keyBy('id') :
            collect();
        $createdOrders = $orders->map(function ($order) use ($domains, $extras, $plans) {
            $newService = [];

            if ($order->domain_id !== null) {
                $newService['domain'] = $domains->find($order->domain_id);
            }
            if ($order->extra_id !== null) {
                $newService['extra'] = $extras->find($order->extra_id);
            }
            if ($order->plan_id !== null) {
                $newService['plan'] = $plans->find($order->plan_id);
            }
                
            return $newService;
        });

        return $orders;
    }
}
