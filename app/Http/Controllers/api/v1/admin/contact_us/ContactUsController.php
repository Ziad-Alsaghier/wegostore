<?php

namespace App\Http\Controllers\api\v1\admin\contact_us;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\ContactUs;

class ContactUsController extends Controller
{
    public function __construct(private ContactUs $contact_us){}
    protected $contactRequest = [
        'email',
        'phone',
        'watts_app',
    ];

    public function view(){
        // contact_us
        $data = $this->contact_us
        ->get();
        
        return response()->json([
            'data' => $data
        ]);
    }

    public function create(Request $request){
        // contact_us/add
        // Keys
        // email, phone, watts_app
        $contactRequest = $request->only($this->contactRequest);
        $data = $this->contact_us
        ->create($contactRequest);

        return response()->json([
            'success' => $data
        ]);
    }

    public function modify(Request $request, $id){
        // contact_us/update/{id}
        // Keys
        // email, phone, watts_app
        $contactRequest = $request->only($this->contactRequest);
        $data = $this->contact_us
        ->where('id', $id)
        ->first();
        $data->update($contactRequest);

        return response()->json([
            'success' => $data
        ]);
    }

    public function delete($id){
        // contact_us/delete/{id}
        $data = $this->contact_us
        ->where('id', $id)
        ->delete();

        return response()->json([
            'success' => 'You delete data success'
        ]);
    }
}
