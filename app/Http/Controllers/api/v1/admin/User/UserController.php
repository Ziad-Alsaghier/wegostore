<?php

namespace App\Http\Controllers\api\v1\admin\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Requests\api\v1\admin\user\UserRequest;
use App\Http\Requests\api\v1\admin\user\UpdateUserRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Support\Facades\Validator;
use App\UploadImage;

use App\Models\User;

class UserController extends Controller
{
    public function __construct(private User $user){}
    protected $userRequest = [
        'name',
        'email',
        'phone',
        'status',
    ];
    use UploadImage;
    // This Controller About Users 

    public function view(){
        Url: http://localhost/wegostore/public/admin/v1/users/view
        try {
            $user = $this->user
            ->where('role', 'user')
            ->get();
            return response()->json([
            'user.message'=>'Data Returned Successfully',
            'user'=>$user,
            ]);
        } catch (\Throwable $th) {
            return response()->json([
                'user.message'=>'Something Wrong In User',
                'error'=>$th->getMessage()
            ]);
        }
    }

    public function status(Request $request, $id){
        // users/status/{id}
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
        ->where('role', 'user')
        ->update([
            'status' => $request->status
        ]);

        return response()->json([
            'status' => $request->status ? 'active' : 'banned'
        ]);
    }

    public function create(UserRequest $request){
        // users/add
        // Keys
        // name, email, phone, status, password
        $userRequest = $request->only($this->userRequest);
        $userRequest['role'] = 'user';
        $userRequest['password'] = $request->password;
        $this->user
        ->create($userRequest);

        return response()->json([
            'success' => 'You add user success'
        ]);
    }

    public function modify(UpdateUserRequest $request, $id){
        // users/update/{id}
        // Keys
        // name, email, phone, status, password
        $user = $this->user
        ->where('id', $id)
        ->where('role', 'user')
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
            'success' => 'You update user success'
        ]);
    }

    public function delete($id){
        // users/delete/{id}
        $user = $this->user
        ->where('id', $id)
        ->where('role', 'user')
        ->first();
        $this->deleteImage($user->image);
        $user->delete();

        return response()->json([
            'success' => 'You delete user success'
        ]);
    }

    public function user_login($id){
        $user = $this->user
        ->where('id', $id)
        ->where('role', 'user')
        ->first();
        if (empty($user)) {
            return response()->json([
                'faild' => 'User not found'
            ], 404);
        }
        $user->generateToken($user);
        return response()->json([
            'success' => 'You login success',
            'user' => $user
        ]);
    }

    public function subscription(){
        Url : http://localhost/wegostore/public/admin/v1/users/subscription
        try {
            $user = $this->user->whereNotNull('plan_id')->get();
            return response()->json([
            'user.message'=>'Data Returned Successfully',
            'user'=>$user,
            ]);
        } catch (\Throwable $th) {
            return response()->json([
                'user.message'=>'Something Wrong In User',
                'error'=>$th->getMessage()
            ]);
        }
    }
}
