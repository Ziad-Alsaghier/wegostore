<?php

namespace App\Http\Controllers\api\v1\user\profile;

use App\Http\Controllers\Controller;
use App\Http\Requests\api\v1\user\profile\ProfileRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ProfileController extends Controller
{
    //
    public function modify(ProfileRequest $request){
        $user = $request->user(); // Get Old Data of User
          $modifyDataUser = $request->validated(); // Get  Name Request Form User
                 if ($request->filled('password')) { // If Request Password
                 $modifyDataUser['password'] = bcrypt($request->input('password'));
                }
                $user->update($modifyDataUser);
            return response()->json([
            'profile.message'=>'Profile Updated Successfully',
        ]);
    }
}
