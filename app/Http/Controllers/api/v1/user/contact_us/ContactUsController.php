<?php

namespace App\Http\Controllers\api\v1\user\contact_us;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\ContactUs;

class ContactUsController extends Controller
{
    public function __construct(private ContactUs $contact_us){}

    public function view(){
        // contact_us
        $data = $this->contact_us
        ->get();
        
        return response()->json([
            'data' => $data
        ]);
    }
}
