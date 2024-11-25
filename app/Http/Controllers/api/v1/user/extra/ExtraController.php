<?php

namespace App\Http\Controllers\api\v1\user\extra;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\Extra;
use App\Models\Order;

class ExtraController extends Controller
{
    public function __construct(private Extra $extra, private Order $order){}

    public function view(){
        // extra
        $extra = $this->extra
        ->get();
        $orders = $this->order
        ->whereNotNull('extra_id')
        ->whereNull('expire_date')
        ->where('user_id', auth()->user()->id)
        ->whereHas('payment', function($query){
            $query->where('status', '!=', 'rejected');
        })
        ->orWhereNotNull('extra_id')
        ->where('expire_date', '>=', date('Y-m-d'))
        ->whereHas('payment', function($query){
            $query->where('status', '!=', 'rejected');
        })
        ->where('user_id', auth()->user()->id)
        ->pluck('extra_id');
        foreach ($extra as $item) {
            if ($orders->contains($item->id)) {
                $item->my_extra = true;
            }
            else{ 
                $item->my_extra = false;
            }
        }

        return response()->json([
            'extras' => $extra
        ]);
    }
}
