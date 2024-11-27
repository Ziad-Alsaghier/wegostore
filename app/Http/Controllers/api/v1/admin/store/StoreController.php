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
        // store/approve
        // Keys
        // store_id, link_cbanal, link_store, email, password, status
        $approveStore = $request->only($this->storeRequest);
        $store_id = $request->store_id; // Get Store ID  
        $store = $this->store->where('id',$store_id)->first(); // Get Store Need Approved
        $store->update($approveStore);
        return response()->json([
            'store.message'=>'Store Approved Successfully',
            'store'=>$store,
        ]);
    }
   public function showPinding()
    {
        URL : http://localhost/wegostore/public/admin/v1/store/show/pending
       
        try {
            $store = $this->store->where('status','pending')->get();
            $data =  count($store) >= 0 ?  $store : "Not Found any store";
            return response()->json([
                'store.message' => 'data Returned Successfully',
                'store' => $data
            ],200);
        } catch (\Throwable $th) {
            return response()->json([
                'payment.message' => 'Something Wrong In Stores',
                'error'=>$th->getMessage(),
            ],500);
        }
    }
   public function showApproved()
    {
        URL : http://localhost/wegostore/public/admin/v1/store/show/pending
       
        try {
            $store = $this->store->where('status','approved')->get();
            $data =  count($store) >= 0 ?  $store : "Not Found any store";
            return response()->json([
                'store.message' => 'data Returned Successfully',
                'store' => $data
            ],200);
        } catch (\Throwable $th) {
            return response()->json([
                'payment.message' => 'Something Wrong In Stores',
                'error'=>$th->getMessage(),
            ],500);
        }
    }

    public function deleted_stores(){
        // /store/deleted_stores
        $deleted_stores = $this->store
        ->where('deleted', 1)
        ->get();

        return response()->json([
            'deleted_stores' => $deleted_stores
        ]);
    }

    public function delete($id){
        // /store/delete/{id}
        $this->stores
        ->where('id', $id)
        ->delete();

        return response()->json([
            'success' => 'You delete delete store success'
        ]);
    }
}
