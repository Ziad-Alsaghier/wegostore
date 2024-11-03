<?php

namespace App\Http\Controllers\api\v1\admin\store;

use App\Http\Controllers\Controller;
use App\Http\Requests\api\v1\admin\payment\ApprovePaymentRequest;
use App\Models\Store;
use Illuminate\Http\Request;

class StoreController extends Controller
{
    public function __construct(private Store $store){}
    // This About All Store Management
    protected $storeRequest = ['store_id','link_cbanal','link_store','email','password','status'];
    public function store_approve(ApprovePaymentRequest $request){
    $approveStore = $request->only($this->storeRequest);
                $store_id = $request->store_id; // Get Store ID  
                $store = $this->store->where('id',$store_id)->first(); // Get Store Need Approved
                $store->update($approveStore);
        return response()->json([
            'store.message'=>'Store Approved Successfully',
            'store'=>$store,
        ]);
    }
}
