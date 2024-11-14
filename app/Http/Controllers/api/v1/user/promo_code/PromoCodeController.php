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
        // promocode
        // Keys
        // code, plan[{plan_id, duration, price}], extra[{extra_id, duration, price}], 
        // domain[{domain_id, price}]
        $promo_codes = $this->promo_codes
        ->where('code', $request->code)
        ->where('start_date', '<=', date('Y-m-d'))
        ->where('end_date', '>=', date('Y-m-d'))
        ->first();
        $discount = 0;
        if (empty($promo_codes)) {
            return response()->json([
                'faild' => 'Promo code is expired'
            ], 400);
        }
        if ($promo_codes->usage <= 0 && $promo_codes->promo_status == 'fixed') {
            return response()->json([
                'faild' => 'The number of times the promo code has been used has expired'
            ], 405);
        }
        $count_user_usage = $promo_codes->users
        ->where('id', $request->user()->id)
        ->count();
        if ($promo_codes->user_type == 'first_usage' && $count_user_usage != 0) {
            return response()->json([
                'faild' => 'This code only on first payment'
            ], 403);
        }
        if (!empty($promo_codes->user_usage) && $promo_codes->user_usage <= $count_user_usage) {
            return response()->json([
                'faild' => 'You have exceeded the maximum use promo code'
            ], 405);
        }

        if ($request->plan) {
            foreach ($request->plan as $plan) { 
                if ($promo_codes->promo_type == 'plan' && $promo_codes->{$plan['duration']}) {
                    if ($promo_codes->calculation_method == 'percentage') {
                        $discount += $plan['price'] * $promo_codes->amount / 100;
                    } 
                    else {
                        $discount += $promo_codes->amount;
                    }
                }
            }
        }

        if ($request->extra) {
            foreach ($request->extra as $extra) { 
                if ($promo_codes->promo_type == 'extra' && $promo_codes->{$extra['duration']}) {
                    if ($promo_codes->calculation_method == 'percentage') {
                        $discount += $extra['price'] * $promo_codes->amount / 100;
                    } 
                    else {
                        $discount += $promo_codes->amount;
                    }
                }
            }
        }

        if ($request->domain) {
            foreach ($request->domain as $domain) {
                if ($promo_codes->promo_type == 'domain') {
                    if ($promo_codes->calculation_method == 'percentage') {
                        $discount += $domain['price'] * $promo_codes->amount / 100;
                    } 
                    else {
                        $discount += $promo_codes->amount;
                    }
                }
            }
        }

        if ($promo_codes->promo_status == 'fixed') {
            $promo_codes->usage = $promo_codes->usage - 1;
            $promo_codes->save();
        }
        $promo_codes->users()->attach($request->user()->id);

        return response()->json([
            'discount' => $discount
        ]);
    }
}
