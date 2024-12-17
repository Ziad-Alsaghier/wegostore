<?php

namespace App\Http\Controllers\api\v1\admin\subscripe;

use App\Http\Controllers\Controller;
use App\Http\Resources\UserResource;
use Illuminate\Http\Request;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Support\Facades\Validator;
use Carbon\Carbon;

use App\Models\User;

class SubscriptionController extends Controller
{
    public function __construct(private User $user){}

    public function view(Request $request){
        // subscripe
            $locale = $request->query('locale', app()->getLocale());

        $users = $this->user->where('role', 'user')
        ->whereNotNull('plan_id')
        ->with('plan', function ($query) use ($locale) {
        $query->withLocale($locale);
        })->get();
        $users = UserResource::collection($users);

        return response()->json([
            'users' => $users
        ]);
    }

    public function add(Request $request){
        // subscripe/add
        // Keys
        // plan_id, user_id, package => [1, 3, 6, yearly]
        $validator = Validator::make($request->all(), [
            'plan_id' => 'required|exists:plans,id',
            'user_id' => 'required|exists:users,id',
            'package' => 'required|in:1,3,6,yearly'
        ]);
        if ($validator->fails()) { // if Validate Make Error Return Message Error
            return response()->json([
                'error' => $validator->errors(),
            ],400);
        }
        if ($request->package == 'yearly') {
            $expire_date = Carbon::now()->addYear();
        } else {
            $expire_date = Carbon::now()->addMonth(intval($request->package));
        }
        $user = $this->user
        ->where('id', $request->user_id)
        ->update([
            'plan_id' => $request->plan_id,
            'start_date' => date('Y-m-d'),
            'expire_date' => $expire_date,
            'package' => $request->package,
        ]);

        return response()->json([
            'success' => 'You subscripe success'
        ]);
    }

    public function modify(Request $request){
        // subscripe/update
        // Keys
        // plan_id, user_id, package => [1, 3, 6, yearly]
        $validator = Validator::make($request->all(), [
            'plan_id' => 'required|exists:plans,id',
            'user_id' => 'required|exists:users,id',
            'package' => 'required|in:1,3,6,yearly'
        ]);
        if ($validator->fails()) { // if Validate Make Error Return Message Error
            return response()->json([
                'error' => $validator->errors(),
            ],400);
        }
        if ($request->package == 'yearly') {
            $expire_date = Carbon::now()->addYear();
        } else {
            $expire_date = Carbon::now()->addMonth(intval($request->package));
        }
        $user = $this->user
        ->where('id', $request->user_id)
        ->update([
            'plan_id' => $request->plan_id,
            'start_date' => date('Y-m-d'),
            'expire_date' => $expire_date,
            'package' => $request->package,
        ]);

        return response()->json([
            'success' => 'You update subscripe success'
        ]);
    }

    public function delete($id){
        // /subscripe/delete/{id}
        $user = $this->user
        ->where('id', $id)
        ->update([
            'plan_id' => null,
            'start_date' => null,
            'expire_date' => null,
            'package' => null,
        ]);

        return response()->json([
            'success' => 'You delete subscripe success'
        ]);
    }
}
