<?php

namespace App\Http\Controllers\api\v1\user\welcome_offer;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\WelcomeOffer;
class WelcomeOfferController extends Controller
{
    public function __construct(private WelcomeOffer $welcome_offer){}

    public function view(){
        // welcome_offer
        $welcome_offer = $this->welcome_offer
        ->with('plan')
        ->orderByDesc('id')
        ->first();
        
        return response()->json([
            'welcome_offer' => $welcome_offer
        ]);
    }
}
