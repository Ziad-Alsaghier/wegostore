<?php

namespace App\Http\Controllers\api\v1\user\domain;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Domain;
use App\Models\Store;
use App\services\PleskService;

class DomainController extends Controller
{
    // use PleskService;

    private $domains;

    protected $domainRequest = [
        'name',
        'store_id',
    ];

    public function __construct(Domain $domains)
    {
        $this->domains = $domains;
        // $this->pleskUrl = config('plesk.url');
        // $this->username = config('plesk.username');
        // $this->password = config('plesk.password');
    }

    /**
     * Get the list of domains categorized by status.
     */
    public function my_domains(Request $request)
    {
        $user_id = $request->user()->id;

        $my_domains = $this->domains
            ->where('status', 1)
            ->where('price_status', 1)
            ->where('user_id', $user_id)
            ->with('store')
            ->get();

        $approve_domains = $this->domains
            ->where('status', 1)
            ->where('price_status', 0)
            ->where('user_id', $user_id)
            ->orWhere('status', 1)
            ->whereNull('price_status')
            ->where('user_id', $user_id)
            ->with('store')
            ->get();

            $pending_domains = $this->domains
            ->where('user_id', $user_id)
            ->whereNull('status') // Pending domains
            ->with('store')
            ->get();

        $rejected_domains = $this->domains
            ->where('status', 0)
            ->where('user_id', $user_id)
            ->with('store')
            ->get();

        return response()->json([
            'my_domains' => $my_domains,
            'approve_domains' => $approve_domains,
            'pending_domains' => $pending_domains,
            'rejected_domains' => $rejected_domains,
        ]);
    }
    public function add_domain(Request $request){
        // domains/add_domain
        // Keys
        // name, store_id
        $domainRequest = $request->only($this->domainRequest);
        $domainRequest['user_id'] = $request->user()->id;
        if (!empty($domain)) {
            return response()->json([
                'faild' => 'Domain is pending'
            ], 400);
        }
        $this->domains
        ->create($domainRequest);

        return response()->json([
            'success' => 'You add data success'
        ]);
        
        // return $result = $this->createSubdomain('Test.com');

        // // Return response
        // if ($result) {
        //     return response()->json(['success' => true, 'message' => 'Subdomain created successfully']);
        // } else {
        //     return response()->json(['success' => false, 'message' => 'Failed to create subdomain']);
        // }   
    }
    /**
     * Add a new domain and create a subdomain automatically.
     */
    // public function add_domain(Request $request)
    // {
    //     $request->validate([
    //         'name' => 'required|string|max:255',
    //         'store_name' => 'required|string|max:255',
    //     ]);

    //     // First, check if the store exists by the store_name
    //     $store = Store::where('name', $request->store_name)->first();

    //     if (!$store) {
    //         // If store doesn't exist, create a new one
    //         $store = Store::create([
    //             'name' => $request->store_name,
    //             'user_id' => $request->user()->id, // Make sure to associate it with the user
    //         ]);
    //     }

        // Now, create the domain associated with the store
        // $domainRequest = [
        //     'name' => $request->name,
        //     'store_id' => $store->id,
        //     'user_id' => $request->user()->id, // Associate the domain with the user
        // ];

        // // Check if a pending domain with the same name exists
        // $existingDomain = $this->domains
        //     ->where('name', $request->name)
        //     ->whereNull('status')
        //     ->first();

        // if (!empty($existingDomain)) {
        //     return response()->json([
        //         'fail' => 'Domain is pending approval.',
        //     ], 400);
        // }

        // // Create the domain in the database
        // $domain = $this->domains->create($domainRequest);

        // Call Plesk API to create the subdomain
        // $response = $this->createSubdomain($domain->name);

        // if ($response['success']) {
        //     return response()->json([
        //         'success' => 'Domain added and subdomain created successfully.',
        //         'plesk_response' => $response['data'],
        //     ], 201);
        // }

        // Rollback if Plesk API fails
    //     $domain->delete();

    //     return response()->json([
    //         'fail' => 'Failed to create subdomain.',
    //         'plesk_error' => $response['error'],
    //     ], 500);
    // }
}
