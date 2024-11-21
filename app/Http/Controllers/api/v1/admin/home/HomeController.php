<?php

namespace App\Http\Controllers\api\v1\admin\home;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Carbon\Carbon;

use App\Models\User;
use App\Models\Order;

class HomeController extends Controller
{
    public function __construct(private User $users, private Order $orders){}

    public function home(){
        URL : https://login.wegostores.com/admin/v1/home
        $startOfMonth = Carbon::now()->startOfMonth()->format('Y-m-d H:i:s');
        $dateOfMonth = Carbon::now()->startOfMonth()->format('Y-m-d');
        $total_users = $this->users
        ->where('role', 'user')
        ->with(['plan'])
        ->get();
        $users_this_month = $total_users->where('created_at', '>=', $startOfMonth);
        $total_subscriptions = $this->users
        ->where('role', 'user')
        ->with(['plan'])
        ->whereNotNull('plan_id')
        ->where('expire_date', '>=', date('Y-m-d'))
        ->get();
        foreach ($total_subscriptions as $item) {
            $order_item = $this->orders
            ->where('plan_id', $item->plan_id)
            ->where('user_id', $item->id)
            ->where('expire_date', '>=', date('Y-m-d'))
            ->whereHas('payment', function($query) {
                $query->where('status', 'approved');
            })
            ->orderByDesc('id')
            ->first();
            if (!empty($order_item)) {
                $item->price = $order_item->price_item;
            }
            else{
                $item->price = null;
            }
        }
        $subscriptions_this_month = $total_subscriptions
        ->where('start_date', '>=', $dateOfMonth);

        return response()->json([
            'total_users' => $total_users->count(),
            'users_this_month' => $users_this_month->count(),
            'total_subscriptions' => $total_subscriptions->count(),
            'subscriptions_this_month' => $subscriptions_this_month->count(),
            
            'users' => $total_users,
            'usersThisMonth' => $users_this_month,
            'subscriptions' => $total_subscriptions,
            'subscriptionsThisMonth' => $subscriptions_this_month,
        ]);
    }
}
