<?php

namespace App\Http\Controllers\api\v1\user\promo_code;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Requests\api\v1\user\promo_code\PromoCodeRequest;

use App\Models\PromoCode;

class PromoCodeController extends Controller
{
    public function __construct(private PromoCode $promo_codes){}

    public function promo_code(PromoCodeRequest $request){
        // Keys
        // code, plan[{plan_id, duration}], extra[{extra_id, duration}], domains
        $promo_codes = $this->promo_codes
        ->where('code', $request->code)
        ->where('start_date', '<=', date('Y-m-d'))
        ->where('end_date', '>=', date('Y-m-d'))
        ->first();
        if (empty($promo_codes)) {
            return response()->json([
                'faild' => 'Promo code is expired'
            ], 400);
        }
        if ($promo_codes->usage <= 0) {
            return response()->json([
                'faild' => 'The number of times the promo code has been used has expired'
            ], 405);
        }
    }
}
