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
    protected $priceCycle;
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
             if (!empty($cart['plan'])) {

              $createdOrders = array_merge($createdOrders, $this->createOrdersForItems($cart['plan'],'plan_id',$data));
             }
           // Step 2: Handle creating orders for each extra_id
           if (!empty($cart['extra'])) {
           $createdOrders = array_merge($createdOrders, $this->createOrdersForItems($cart['extra'], 'extra_id', $data));
           }
           // Step 3: Handle creating orders for each domain_id
           if (!empty($cart['domain'])) {
           $createdOrders = array_merge($createdOrders, $this->createOrdersForItems($cart['domain'], 'domain_id',
           $data));
           }
         
                        try {
                    $createdOrders = [];
                    
                    if (!empty($cart['plan'])) {
                        $createdOrders = array_merge($createdOrders, $this->createOrdersForItems($cart['plan'], 'plan_id', $data));
                    }

                    if (!empty($cart['extra'])) {
                        $createdOrders = array_merge($createdOrders, $this->createOrdersForItems($cart['extra'], 'extra_id', $data));
                    }

                    if (!empty($cart['domain'])) {
                        $createdOrders = array_merge($createdOrders, $this->createOrdersForItems($cart['domain'], 'domain_id', $data));
                    }

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
    $count = 1;
    foreach ($items as $item) {
        // Ensure $item is an array
        
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
         $orderData = array_merge($baseData, [$field => $item[$field]  ,
        'price_cycle' => $periodPrice, // Add price_cycle here
        'price_item' => $this->priceCycle // Add price_item here
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
        $count++;
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
        // Retrieve orders related to the payment
        $orders = $payment->orders;

        // Collect unique IDs for batch fetching
        $domainIds = $orders->whereNotNull('domain_id')->pluck('domain_id')->unique();
        $extraIds = $orders->whereNotNull('extra_id')->pluck('extra_id')->unique();
        $planIds = $orders->whereNotNull('plan_id')->pluck('plan_id')->unique();
        // Approved Domains
            if ($domainIds->isNotEmpty()) {
            $this->domain->whereIn('id', $domainIds)->update(['price_status' => true]);
            }
        // End Approved Domains   
                    // Fetch all required services in batch only if IDs are present
    $domains = $domainIds->isNotEmpty() ? $this->domain->whereIn('id', $domainIds)->get()->keyBy('id') : collect();
    $extras = $extraIds->isNotEmpty() ? $this->extra->whereIn('id', $extraIds)->get()->keyBy('id') : collect();
    $plans = $planIds->isNotEmpty() ? $this->plan->whereIn('id', $planIds)->get()->keyBy('id') : collect();
    $createdOrders = $orders->map(function ($order) use ($domains, $extras, $plans) {
        $newService = [];

        if ($order->domain_id !== null) {
            $newService['domain'] = $domains->get($order->domain_id);
        }
        if ($order->extra_id !== null) {
            $newService['extra'] = $extras->get($order->extra_id);
        }
        if ($order->plan_id !== null) {
            $newService['plan'] = $plans->get($order->plan_id);
        }

        return $newService;
    });

        return $createdOrders->unique();
    }
}
