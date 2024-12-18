<?php

namespace App\Http\Controllers\api\v1\user\store;

use App\Http\Requests\api\v1\user\store\StoreRequest;
use App\Http\Controllers\Controller;
use App\Http\Resources\ActivityResource;
use Illuminate\Http\Request;
use App\UploadImage;

use App\Models\Store;
use App\Models\Activity;
use App\Models\User;
use App\Models\Order;

class StoreController extends Controller
{
    public function __construct(private Store $stores, private Activity $activties,
    private User $user, private Order $orders){}
    protected $storeRequest = [
        'store_name',
        'instgram_link',
        'facebook_link',
        'phone',
        'activities_id'
    ];
    use UploadImage;

    public function stores(Request $request){
        // login.wegostores.com/user/v1/store
        $stores = $this->stores
        ->where('user_id', $request->user()->id)
        ->where('deleted', '!=', 1)
        ->with('activity')
        ->get();
        $locale = $request->query('locale',app()->getLocale());
        $activties = $this->activties
        ->withLocale($locale)
        ->get();
        $activties = ActivityResource::collection($activties);

        return response()->json([
            'stores' => $stores,
            'activties' => $activties,
        ]);
    }

    public function make_store(StoreRequest $request){
        // login.wegostores.com/user/v1/store/add
        // Keys
        // store_name, instgram_link, facebook_link, phone, activities_id, logo
        $user = $this->user
        ->where('id', $request->user()->id)
        ->where('expire_date', '>=', date('Y-m-d'))
        ->first();
        if (empty($user)) {
            return response()->json([
                'faild' => 'You must buy plan'
            ], 403);
        }
        $plans = $user->plan;
        if (empty($plans)) {
            return response()->json([
                'faild' => 'You must buy plan'
            ], 403);
        }
        $stores = $this->stores
        ->where('user_id', $request->user()->id)
        ->where('plan_id', $plans->id)
        ->where('status', '!=', 'rejected')
        ->count();
        if ($stores >= $plans->limet_store) {
            return response()->json([
                'faild' => 'You must buy new plan'
            ], 405);
        }
        $storeRequest = $request->only($this->storeRequest);
        $storeRequest['user_id'] = $request->user()->id;
        $storeRequest['plan_id'] = $request->user()->plan_id;
        $storeRequest['status'] = 'pending';
        if ($request->logo) {
            $image_path = $this->imageUpload($request, 'logo', 'user/store/logo');
            $storeRequest['logo'] = $image_path;
        }
        $store = $this->stores
        ->create($storeRequest);
        $this->orders
        ->create([
            'user_id' => auth()->user()->id,
            'order_status' => 'pending',
            'store_id' => $store->id
        ]);

        return response()->json([
            'success' => 'You make order success'
        ]);
    }

    public function delete_store($id){
        // login.wegostores.com/user/v1/store/delete/{id}
        $this->stores
        ->where('id', $id)
        ->update(['deleted' => 1]);
        $this->orders
        ->create([
            'user_id' => auth()->user()->id,
            'store_id' => $id,
            'status' => 'delete',
        ]);

        return response()->json([
            'success' => 'You send request to delete store success'
        ]);
    }
}
