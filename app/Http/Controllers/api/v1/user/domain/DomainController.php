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
        $domains = $this->domains
        ->where('status', 1)
        ->get();

        return response()->json([
            'domains' => $domains
        ]);
    }

    public function pending_request(DomainRequest $request){
        $domains = $this->domains
        ->where('status', '!=', 1)
        ->get();

        return response()->json([
            'domains' => $domains
        ]);
    }
}
