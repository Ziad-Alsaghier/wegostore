<?php

namespace App\Http\Controllers\api\v1\admin\domain;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Support\Facades\Validator;

use App\Models\Domain;

class DomainController extends Controller
{
    public function __construct(private Domain $domain){}

    public function domains_pending(){
        // /domains
        $domains = $this->domain
        ->with(['store.activity', 'user'])
        ->get();

        return response()->json([
            'domains' => $domains
        ]);
    }

    public function approve_domain($id, Request $request){
        // /domains/approve/{id}
        // Keys
        // price
        $validator = Validator::make($request->all(), [
            'price' => 'required|numeric',
        ]);
        if ($validator->fails()) { // if Validate Make Error Return Message Error
            return response()->json([
                'error' => $validator->errors(),
            ],400);
        }
        $this->domain
        ->where('id', $id)
        ->update([
            'status' => 1,
            'price' => $request->price
        ]);

        return response()->json([
            'success' => 'You approve domain success'
        ]);
    }

    public function rejected_domain($id, Request $request){
        // /domains/rejected/{id}
        // Keys
        // rejected_reason
        $validator = Validator::make($request->all(), [
            'rejected_reason' => 'required',
        ]);
        if ($validator->fails()) { // if Validate Make Error Return Message Error
            return response()->json([
                'error' => $validator->errors(),
            ],400);
        }
        $this->domain
        ->where('id', $id)
        ->update([
            'status' => 0,
            'rejected_reason' => $request->rejected_reason
        ]);

        return response()->json([
            'success' => 'You reject domain success',
        ]);
    }

}
