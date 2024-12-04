<?php

namespace App\Http\Controllers\api\v1\user\domain;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Requests\api\v1\user\domain\DomainRequest;
use App\service\PleskService;

use App\Models\Domain;

class DomainController extends Controller
{
    public function __construct(private Domain $domains){}
    protected $domainRequest = [
        'name',
        'store_id',
    ];
    use PleskService;

    public function my_domains(){
        // domains/my_domains
        $my_domains = $this->domains
        ->where('status', 1)
        ->where('price_status', 1)
        ->with('store')
        ->get();
        $approve_domains = $this->domains
        ->where('status', 1)
        ->where('price_status', 0)
        ->orWhere('status', 1)
        ->whereNull('price_status')
        ->with('store')
        ->get();
        $pending_domains = $this->domains
        ->whereNull('status')
        ->with('store')
        ->get();
        $rejected_domains = $this->domains
        ->where('status', 0)
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
        // $domainRequest = $request->only($this->domainRequest);
        // $domainRequest['user_id'] = $request->user()->id;
        // $this->domains
        // ->create($domainRequest);

        // return response()->json([
        //     'success' => 'You add data success'
        // ]);
        
        return $result = $this->createSubdomain('Test.com');

        // Return response
        if ($result) {
            return response()->json(['success' => true, 'message' => 'Subdomain created successfully']);
        } else {
            return response()->json(['success' => false, 'message' => 'Failed to create subdomain']);
        }
    }
}
