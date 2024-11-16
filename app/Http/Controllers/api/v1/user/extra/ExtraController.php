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

        return response()->json([
            'extras' => $extra
        ]);
    }
}
