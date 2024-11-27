<?php

namespace App\Http\Controllers\api\v1\user\my_service;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\Order;

class MyServiceController extends Controller
{
    public function __construct(private Order $order){}

    public function my_service(){
        // my_service
        $orders = $this->order
        ->whereNotNull('extra_id')
        ->whereNull('expire_date')
        ->where('user_id', auth()->user()->id)
        ->whereHas('payment', function($query){
            $query->where('status', '!=', 'rejected');
        })
        ->orWhereNotNull('extra_id')
        ->where('expire_date', '>=', date('Y-m-d'))
        ->whereHas('payment', function($query){
            $query->where('status', '!=', 'rejected');
        })
        ->where('user_id', auth()->user()->id)
        ->with(['extra', 'domain', 'plans'])
        ->orderByDesc('id')
        ->get();
        $extras = array_filter($orders->pluck('extra')->toArray());
        $domains = array_filter($orders->pluck('domain')->toArray());
        $plan = $orders->first()->plans;
        $stores = $this->order
        ->whereNotNull('store_id')
        ->whereNull('expire_date')
        ->where('user_id', auth()->user()->id)
        ->whereHas('payment', function($query){
            $query->where('status', '!=', 'rejected');
        })
        ->orWhereNotNull('store_id')
        ->where('expire_date', '>=', date('Y-m-d'))
        ->whereHas('payment', function($query){
            $query->where('status', '!=', 'rejected');
        })
        ->where('user_id', auth()->user()->id)
        ->with(['store'])
        ->get();

        return response()->json([
            'extras' => $extras,
            'domains' => $domains,
            'plan' => $plan,
            'stores' => $stores->pluck('store'),
        ]);
    }
}
