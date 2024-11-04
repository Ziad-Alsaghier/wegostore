<?php

namespace App\Http\Controllers\api\v1\user\store;

use App\Http\Requests\api\v1\user\store\StoreRequest;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\UploadImage;

use App\Models\Store;
use App\Models\Activity;
use App\Models\User;

class StoreController extends Controller
{
    public function __construct(private Store $stores, private Activity $activties,
    private User $user){}
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
        ->get();
        $activties = $this->activties
        ->get();

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
        if (is_file($request->logo)) {
            $image_path = $this->imageUpload($request, 'logo', 'user/store/logo');
            $storeRequest['logo'] = $image_path;
        }
        $this->stores
        ->create($storeRequest);

        return response()->json([
            'success' => 'You make order success'
        ]);
    }

    public function delete_store($id){
        // login.wegostores.com/user/v1/store/delete/{id}
        $this->stores
        ->where('id', $id)
        ->update(['deleted' => 1]);

        return response()->json([
            'success' => 'You send request to delete store success'
        ]);
    }
}
