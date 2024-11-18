<?php

namespace App\Http\Controllers\api\v1\admin\home;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Carbon\Carbon;

use App\Models\User;

class HomeController extends Controller
{
    public function __construct(private User $users){}

    public function home(){
        URL : https://login.wegostores.com/admin/v1/home
        $startOfMonth = Carbon::now()->startOfMonth()->format('Y-m-d H:i:s');
        $dateOfMonth = Carbon::now()->startOfMonth()->format('Y-m-d');
        $total_users = $this->users
        ->where('role', 'user')
        ->get();
        $users_this_month = $total_users->where('created_at', '>=', $startOfMonth);
        $total_subscriptions = $total_users->whereNotNull('plan_id')
        ->where('expire_date', '>=', date('Y-m-d'));
        $subscriptions_this_month = $total_subscriptions
        ->where('start_date', '>=', $dateOfMonth);

        return response()->json([
            'total_users' => $total_users->count(),
            'users_this_month' => $users_this_month->count(),
            'total_subscriptions' => $total_subscriptions->count(),
            'subscriptions_this_month' => $subscriptions_this_month->count(),
        ]);
    }
}
