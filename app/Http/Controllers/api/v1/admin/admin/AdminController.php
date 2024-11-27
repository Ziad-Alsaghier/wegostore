<?php

namespace App\Http\Controllers\api\v1\admin\admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Support\Facades\Validator;
use App\UploadImage;

use App\Models\User;
use App\Models\AdminPosition;

class AdminController extends Controller
{
    public function __construct(private User $user, private AdminPosition $admin_postions){}
    protected $userRequest = [
        'name',
        'email',
        'phone',
        'status',
    ];
    use UploadImage;
    // This Controller About Users 

    public function view(){
        Url: http://localhost/wegostore/public/admin/v1/admins/view
        try {
            $admin = $this->user
            ->where('role', 'admin')
            ->get();
            $admin_postions = $this->admin_postions
            ->get();
            return response()->json([
                'admins.message'=>'Data Returned Successfully',
                'admins'=>$admin,
                'admin_postions' => $admin_postions,
            ]);
        } catch (\Throwable $th) {
            return response()->json([
                'admins.message'=>'Something Wrong In Admin',
                'error'=>$th->getMessage()
            ]);
        }
    }

    public function status(Request $request, $id){
        // admins/status/{id}
        // Keys
        // status
        $validator = Validator::make($request->all(), [
            'status' => 'boolean|required',
        ]);
        if ($validator->fails()) { // if Validate Make Error Return Message Error
            return response()->json([
                'error' => $validator->errors(),
            ],400);
        }
        $this->user
        ->where('id', $id)
        ->where('role', 'admin')
        ->update([
            'status' => $request->status
        ]);

        return response()->json([
            'status' => $request->status ? 'active' : 'banned'
        ]);
    }

    public function create(UserRequest $request){
        // admins/add
        // Keys
        // name, email, phone, status, password
        $userRequest = $request->only($this->userRequest);
        $userRequest['role'] = 'admin';
        $userRequest['password'] = $request->password;
        $this->user
        ->create($userRequest);

        return response()->json([
            'success' => 'You add admin success'
        ]);
    }

    public function modify(UpdateUserRequest $request, $id){
        // admins/update/{id}
        // Keys
        // name, email, phone, status, password
        $user = $this->user
        ->where('id', $id)
        ->where('role', 'admin')
        ->first();
        if (!empty($request->password)) {
            $user->password = bcrypt($request->password) ?? null;
        }
        $user->name = $request->name ?? null;
        $user->email = $request->email ?? null;
        $user->phone = $request->phone ?? null;
        $user->status = $request->status ?? null;
        $user->save();

        return response()->json([
            'success' => 'You update admin success'
        ]);
    }

    public function delete($id){
        // admins/delete/{id}
        $user = $this->user
        ->where('id', $id)
        ->where('role', 'admin')
        ->first();
        $this->deleteImage($user->image);
        $user->delete();

        return response()->json([
            'success' => 'You delete admin success'
        ]);
    }
}
