<?php

namespace App\Http\Controllers\api\v1\user\domain;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Http\Requests\api\v1\user\domain\DomainRequest;

use App\Models\Domain;

class DomainController extends Controller
{
    public function __construct(private Domain $domains){}

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

    public function pending_request(DomainRequest $request){
        // domains/domain_request
        $domains = $this->domains
        ->where('status', '!=', 1)
        ->get();

        return response()->json([
            'domains' => $domains
        ]);
    }

    public function add_domain(Request $request){
        $this->domains
        ->create();
    }
}
