<?php

namespace App\Http\Controllers\api\v1\admin\store;

use App\Http\Controllers\Controller;
use App\Http\Requests\api\v1\admin\payment\ApprovePaymentRequest;
use App\Http\Requests\api\v1\admin\store\StoreEditReqeust;
use App\Models\Store;
use App\services\PleskService;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Http\Request;

class StoreController extends Controller
{
    private Store $store;
    private PleskService $pleskService;

    public function __construct(Store $store, PleskService $pleskService)
{
    $this->store = $store;
    $this->pleskService = $pleskService;
}

public function store_approve(ApprovePaymentRequest $request)
{
    // Validate and process approval request
    $approveStore = $request->validated();
    $store_id = $request->store_id; // Get Store ID
    $store = $this->store->where('id', $store_id)->firstOrFail(); // Get Store that needs approval
    $status = $approveStore['status'];

    if ($status === 'approved') {
        try {
            // Generate subdomain based on store name
            $subdomain = strtolower($store->store_name) . '.' . parse_url(config('app.url'), PHP_URL_HOST);

            // Attempt to create the subdomain using PleskService
            $pleskResponse = $this->pleskService->createSubdomain($subdomain);

            if ($pleskResponse['success']) {
                // Generate full URL for the subdomain
                $storeUrl = 'https://' . $subdomain;

                // Update store data upon successful subdomain creation
                $store->update([
                    'status' => 'approved',
                    'subdomain' => $subdomain, // Store the subdomain in the database
                    'link_store' => $storeUrl,  // Set the full URL for the store
                ]);

                return response()->json([
                    'message' => 'Store approved and subdomain created successfully.',
                    'store' => $store,
                ]);
            } else {
                return response()->json([
                    'message' => 'Store approved, but subdomain creation failed.',
                    'error' => $pleskResponse['error'],
                ], 500);
            }
        } catch (\Throwable $th) {
            return response()->json([
                'message' => 'Store approval failed during subdomain creation.',
                'error' => $th->getMessage(),
            ], 500);
        }
    } else {
        // Handle cases where the status is not "approved"
        $store->update($approveStore);

        return response()->json([
            'message' => 'Store status updated successfully.',
            'store' => $store,
        ]);
    }
}

    public function showPinding()
    {
        URL:
        http: //localhost/wegostore/public/admin/v1/store/show/pending

        try {
            $store = $this->store->where('status', 'pending')->get();
            $data =  count($store) >= 0 ?  $store : "Not Found any store";
            return response()->json([
                'store.message' => 'data Returned Successfully',
                'store' => $data
            ], 200);
        } catch (\Throwable $th) {
            return response()->json([
                'payment.message' => 'Something Wrong In Stores',
                'error' => $th->getMessage(),
            ], 500);
        }
    }
    public function showApproved()
    {
        URL:
        http: //localhost/wegostore/public/admin/v1/store/show/pending

        try {
            $store = $this->store->where('status', 'approved')->get();
            $data =  count($store) >= 0 ?  $store : "Not Found any store";
            return response()->json([
                'store.message' => 'data Returned Successfully',
                'store' => $data
            ], 200);
        } catch (\Throwable $th) {
            return response()->json([
                'payment.message' => 'Something Wrong In Stores',
                'error' => $th->getMessage(),
            ], 500);
        }
    }

    public function deleted_stores()
    {
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

    public  function show_approve()
    {
        URL:
        http: //wegostore.test/admin/v1/store/show/approve
        try {
            $stores = $this->store->with('order')->where('status', 'approved')->get();
        } catch (\Throwable $th) {
            throw  new HttpResponseException(response()->json([
                'message' => 'Somthing Wrong',
                'error' => $th,
            ], 500));
        }

        return response()->json([
            'message' => 'Data Returned Successfully',
            'stores' => $stores,
        ]);
    }
    public function edit(Store $store, StoreEditReqeust $request)
    {
        URL:
        http: //wegostore.test/admin/v1/store/edit/{id}
        try {
            $storeReqeust = $request->validated();
            $store->update($storeReqeust);
        } catch (\Throwable $th) {
            throw new HttpResponseException(response()->json([
                'message' => 'Somthing Wrong',
                'error' => $th,
            ], 500));
        }


        return response()->json([
            'message' => "Store for $store->email Updated Successfully",
            'store' => $store,
        ]);
    }


    public function delete(Store $store)
    {
        URL:
        http: //wegostore.test/admin/v1/store/delete/{id}

        try {
            if ($store->delete()) {
                return response()->json([
                    'message' => 'Store Deleted Successfully'
                ], 200);
            }
        } catch (\Throwable $th) {
            new HttpResponseException(response()->json([
                'error' => 'Something Wrong',
                'message' => $th->getMessage(),
            ], 500));
        }
    }
}
