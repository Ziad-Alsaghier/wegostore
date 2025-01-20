<?php

namespace App\Http\Controllers\api\v1\user\promo_code;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Requests\api\v1\user\promo_code\PromoCodeRequest;

use App\Models\PromoCode;
use App\Models\Order;

class PromoCodeController extends Controller
{
    public function __construct(private PromoCode $promo_codes, private Order $orders){}

    public function promo_code(PromoCodeRequest $request){
        // promocode
        // Keys
        // code, plan[{plan_id, duration, price, price_discount, fees}], 
        // extra[{extra_id, duration, price, , price_discount, fees}], 
        // domain[{domain_id, price, , price_discount}]
        $promo_codes = $this->promo_codes
        ->where('code', $request->code)
        ->where('start_date', '<=', date('Y-m-d'))
        ->where('end_date', '>=', date('Y-m-d'))
        ->first();
        $total = 0;
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
                if (empty($request->user()->plan_id)) {
                    $total +=  $plan['fees'];
                }
                if ($promo_codes->promo_type == 'plan') {
                    if ($promo_codes->calculation_method == 'percentage') {
                        $total += $plan['price'] - $plan['price'] * $promo_codes->{$plan['duration']} / 100;
                    } 
                    else {
                        $total += $plan['price'] - $promo_codes->{$plan['duration']};
                    }
                }
                else{
                    $total += $plan['price_discount'];
                }
            }
        }

        if ($request->extra) {
            foreach ($request->extra as $extra) {
                $extras = $this->orders
                ->where('extra_id', $extra['extra_id'])
                ->where('user_id', $request->user()->id)
                ->whereHas('payment', function($query){
                    $query->where('status', '!=', 'rejected');
                })
                ->first();
                if (empty($extras)) {
                    $total +=  $extra['fees'];
                }
                if ($promo_codes->promo_type == 'extra') {
                    if ($promo_codes->calculation_method == 'percentage') {
                        $total += $extra['price']  - $extra['price'] * $promo_codes->{$extra['duration']} / 100;
                    } 
                    else {
                        $total += $extra['price'] - $promo_codes->{$extra['duration']};
                    }
                }
                else{
                    $total += $plan['price_discount'];
                }
            }
        }

        if ($request->domain) {
            foreach ($request->domain as $domain) {
                if ($promo_codes->promo_type == 'domain') {
                    if ($promo_codes->calculation_method == 'percentage') {
                        $total += $domain['price'] - $domain['price'] * $promo_codes->amount / 100;
                    } 
                    else {
                        $total += $domain['price'] - $promo_codes->amount;
                    }
                }
                else{
                    $total += $plan['price_discount'];
                }
            }
        }

        if ($promo_codes->promo_status == 'fixed') {
            $promo_codes->usage = $promo_codes->usage - 1;
            $promo_codes->save();
        }
        $promo_codes->users()->attach($request->user()->id);

        return response()->json([
            'total' => $total
        ]);
    }
}
