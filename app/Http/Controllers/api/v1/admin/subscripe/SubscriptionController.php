<?php

namespace App\Http\Controllers\api\v1\admin\subscripe;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\User;

class SubscriptionController extends Controller
{
    public function __construct(private User $user){}

    public function view(){
        $users = $this->user
        ->where('role', 'user')
        ->whereNotNull('plan_id')
        ->get();

        return response()->json([
            'users' => $users
        ]);
    }
}
