<?php

namespace App\Http\Controllers\api\v1\user\store;

use App\Http\Requests\api\v1\user\store\StoreRequest;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\Store;
use App\Models\Activity;

class StoreController extends Controller
{
    public function __construct(private Store $stores, private Activity $activties){}
    protected $storeRequest = [
        'store_name',
        'instgram_link',
        'facebook_link',
        'phone',
        'activities_id'
    ];

    public function stores(Request $request){
        $stores = $this->stores
        ->where('user_id', $request->user()->id)
        ->get();
        $activties = $this->activties
        ->get();

        return response()->json([
            'stores' => $stores,
            'activties' => $activties,
        ]);
    }

    public function make_store(StoreRequest $request){
        $plans = $request->user()->plan;
        if (empty($plans)) {
            return response()->json([
                'faild' => 'You must buy plan'
            ], 403);
        }
        $stores = $this->stores
        ->where('user_id', $request->user()->id)
        ->where('plan_id', $plans->id)
        ->count();
        return $stores;
        return $plans->limet_store;
    }
}
