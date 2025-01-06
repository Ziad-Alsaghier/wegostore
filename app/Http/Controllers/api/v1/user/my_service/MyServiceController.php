<?php

namespace App\Http\Controllers\api\v1\user\my_service;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\Order;
use App\Models\Domain;

class MyServiceController extends Controller
{
    public function __construct(private Order $order, private Domain $domains){}

    public function my_service(Request $request){
        // my_service
        $user_id = $request->user()->id;
        $orders = $this->order
        ->whereNull('expire_date')
        ->where('user_id', $user_id )
        ->whereHas('payment', function($query){
            $query->where('status', '!=', 'rejected');
        })
        ->orWhere('expire_date', '>=', date('Y-m-d'))
        ->whereHas('payment', function($query){
            $query->where('status', '!=', 'rejected');
        })
        ->where('user_id', $user_id )
        ->with(['extra', 'domain', 'plans'])
        ->orderByDesc('id')
        ->get();
        $extras = array_filter($orders->pluck('extra')->toArray());
        $domains = $this->domains
        ->where('price_status', 1)
        ->get();
        $plan = array_filter($orders->pluck('plans')->toArray());
        $plan = array_values($plan);
        if (count($plan) > 0) {
            $plan = $plan[count($plan) - 1];
        }
        $stores = $this->order
        ->whereNotNull('store_id')
        ->whereNull('expire_date')
        ->where('user_id', $user_id )
        ->orWhereNotNull('store_id')
        ->where('expire_date', '>=', date('Y-m-d'))
        ->where('user_id', $user_id )
        ->with(['store'])
        ->get();

        return response()->json([
            'extras' => array_values($extras),
            'domains' => array_values($domains->toArray()),
            'plan' => $plan,
            'stores' => $stores->pluck('store'),
        ]);
    }
}
