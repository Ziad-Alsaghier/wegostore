<?php

namespace App\Http\Controllers\api\v1\admin\promoCode;

use App\Http\Controllers\Controller;
use App\Http\Requests\api\v1\admin\promoCode\PromoCodeRequest;
use App\Models\PromoCode;
use App\Models\PromoCodeType;
use App\Models\User;
use Illuminate\Http\Request;

class PromoCodeController extends Controller
{
    // This Controlller About Promo Offer
    public function __construct(
        private PromoCode $promoCode ,
        private PromoCodeType $promoCodeType
        ){}
    protected $prormoRequest = [
    'code',
    'title',
    'usage',
    'user_usage',
    'calculation_method',
    'user_type',
    'start_date',
    'end_date',
    'quarterly',
    'semi-annual',
    'yearly',
    'monthly',
];
    protected $prormoTypeRequest = [];
    public function store(PromoCodeRequest $request){
        $promoCodeRequest = $request->only($this->prormoRequest);
        try {
            $promoCode = $this->promoCode->create($promoCodeRequest);
            $promoCodeTypeRequest = $request->only($this->prormoTypeRequest);
            return response()->json([
                'promoCode.message'=>'PromoCode Created Successfully',
                'promoCode'=>$promoCode
            ]);
        } catch (\Throwable $th) {
            return response()->json([
                'promoCode.message'=>'Something Wrong In Process Create',
                'message'=>$th->getMessage(),
            ]);
            
        }        

    }


    public function view(){
        try {
            $promoCode = $this->promoCode->get();
            $data = !empty($promoCode)?$promoCode : $promoCode = 'PromoCode Is Empty';
            return response()->json([
                'promoCode.success'=>'Data Returned Successfully',
                'promoCode'=>$data
            ]);
        } catch (\Throwable $th) {
            return response()->json([
                'promoCode.faield'=>'Something Wrong',
                'message'=>$th->getMessage()
            ]);
        }

    }
 public function delete($id){
        $promoCode = $this->promoCode->find($id);
             if (!$promoCode) {
             return response()->json(['error' => 'PromoCode not found'], status: 404);
             }
             $promoCode->delete();
            return response()->json([
                'promoCode.delete' => 'PromoCode Deleted Successfully',
            ], status: 200);
    }


}
