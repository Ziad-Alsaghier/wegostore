<?php

namespace App\Http\Controllers\api\v1\admin\profile;

use App\Http\Controllers\Controller;
use App\Http\Requests\api\v1\admin\profile\ProfileRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
class ProfileController extends Controller
{

    // This About Profile Admin
        public function modify(ProfileRequest $request){
            URL : http://localhost/wegostore/public/admin/v1/profile/update
            $modifyDataUser = $request->validated();
               $user = Auth::user(); // Get the authenticated user
                $user->name = $request->input('name');
                $user->email = $request->input('email');
                 if ($request->filled('password')) {
                 $user->password = bcrypt($request->input('password'));
                 }
                $user->phone = $request->input('phone');
                $user->save();
            return response()->json([
            'profile.message'=>'Profile Updated Successfully',
        ]);
        }


         
}
