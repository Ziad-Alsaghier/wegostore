<?php

namespace App\Http\Controllers\api\v1\admin\store;

use App\Http\Controllers\Controller;
use App\Http\Requests\api\v1\admin\payment\ApprovePaymentRequest;
use App\Http\Requests\api\v1\admin\store\StoreEditReqeust;
use App\Models\Store;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Http\Request;

class StoreController extends Controller
{
    public function __construct(private Store $store){}
    // This About All Store Management
    
    public function store_approve(ApprovePaymentRequest $request){
        // store/approve
        // Keys
        // store_id, link_cbanal, link_store, email, password, status
        $approveStore = $request->validated();
        $store_id = $request->store_id; // Get Store ID  
        $store = $this->store->where('id',$store_id)->first(); // Get Store Need Approved
         $status = $approveStore['status'];
        if($status == 'in_progress'){
             $store->order->update(['order_status'=>'in_progress']);
            }else{
            $store->update($approveStore);
        }
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

    // public function delete($id){
    //     // /store/delete/{id}
    //     $this->store
    //     ->where('id', $id)
    //     ->delete();

    //     return response()->json([
    //         'success' => 'You delete delete store success'
    //     ]);
    // }

    public  function show_approve(){
        URL : http://wegostore.test/admin/v1/store/show/approve
            try {
                        $stores = $this->store->with('order')->where('status','approved')->get();
            } catch (\Throwable $th) {
                throw  new HttpResponseException(response()->json([
                    'message'=>'Somthing Wrong',
                    'error'=>$th,
                ],500));
            }

            return response()->json([
                'message'=>'Data Returned Successfully',
                'stores'=>$stores,
            ]);
    }
    public function edit(Store $store,StoreEditReqeust $request){
            URL : http://wegostore.test/admin/v1/store/edit/{id}
        try {
            $storeReqeust = $request->validated();
            $store->update($storeReqeust);
        } catch (\Throwable $th) {
                throw new HttpResponseException(response()->json([
                'message'=>'Somthing Wrong',
                'error'=>$th,
                ],500));
        }


        return response()->json([
        'message'=>"Store for $store->email Updated Successfully",
        'store'=>$store,
        ]);
    }


    public function delete(Store $store){
                    URL : http://wegostore.test/admin/v1/store/delete/{id}
   
        try {
                if($store->delete()){
            return response()->json([
                'message'=>'Store Deleted Successfully'
            ]);
            }
            } catch (\Throwable $th) {
            new HttpResponseException(response()->json([
                    'error'=>'Something Wrong',
                    'message'=>$th->getMessage(),
            ],500));
            }
    }
}
