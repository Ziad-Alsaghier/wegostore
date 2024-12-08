<?php

namespace App\Http\Controllers\api\v1\admin\home;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Carbon\Carbon;

use App\Models\User;
use App\Models\Order;
use App\Models\Payment;

class HomeController extends Controller
{
    public function __construct(private User $users, private Order $orders,
    private Payment $payments){}

    public function total($orders){
        $total = 0;
        foreach ($orders as $order) {
            if (!empty($order->plans)) {
                $total += $order->plans->{$order->price_cycle} - 
                $order->plans->{'discount_' . $order->price_cycle};
            }
            if (!empty($order->domain)) {
                $total += $order->domain->price;
            }
            if (!empty($order->extra)) {
                $total += $order->extra->{$order->price_cycle} - 
                $order->extra->{'discount_' . $order->price_cycle}; 
            }
        }

        return $total;
    }

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
        $pending_payments = $this->payments
        ->where('status', 'pending')
        ->count();
        $pending_orders = $this->orders
        ->where('order_status', 'pending')
        ->whereNull('plan_id')
        ->count();
        $in_progress_orders = $this->orders
        ->where('order_status', 'in_progress')
        ->whereNull('plan_id')
        ->count();
        $payments = $this->payments
        ->where('status', 'approved')
        ->with('orders');
        $payments_at_year = $payments
        ->whereYear('created_at', now()->year)
        ->sum('amount');
        $order_payment = $payments
        ->whereYear('created_at', now()->year)
		->get()
        ->pluck('orders');
        $payment_one_month = $order_payment
        ->where('price_cycle', 'monthly')
        ->sum('price_item');
        $payment_3_month = $order_payment
        ->where('price_cycle', 'quarterly')
        ->sum('price_item');
        $payment_6_month = $order_payment
        ->where('price_cycle', 'semi_annual')
        ->sum('price_item');
        $payment_year = $order_payment
        ->where('price_cycle', 'yearly')
        ->sum('price_item');
        $currentDate = Carbon::now();
        $add_month = $currentDate->addMonth();
        $add_3_month = $currentDate->addMonth(3);
        $add_6_month = $currentDate->addMonth(6);
        $add_year = $currentDate->addYear();
        $order_month = $this->orders
        ->where('expire_date', '<=', $add_month)
        ->where('expire_date', '>=', date('Y-m-d'))
        ->with(['plans', 'domain', 'extra'])
        ->get();
        $order_3_month = $this->orders
        ->where('expire_date', '<=', $add_3_month)
        ->where('expire_date', '>=', date('Y-m-d'))
        ->with(['plans', 'domain', 'extra'])
        ->get();
        $order_6_month = $this->orders
        ->where('expire_date', '<=', $add_6_month)
        ->where('expire_date', '>=', date('Y-m-d'))
        ->with(['plans', 'domain', 'extra'])
        ->get();
        $order_year = $this->orders
        ->where('expire_date', '<=', $add_year)
        ->where('expire_date', '>=', date('Y-m-d'))
        ->with(['plans', 'domain', 'extra'])
        ->get();
        $expected_revenue_month = $this->total($order_month);
        $expected_revenue_3_month = $this->total($order_3_month);
        $expected_revenue_6_month = $this->total($order_6_month);
        $expected_revenue_year = $this->total($order_year);

        return response()->json([
            'total_users' => $total_users->count(),
            'users_this_month' => $users_this_month->count(),
            'total_subscriptions' => $total_subscriptions->count(),
            'subscriptions_this_month' => $subscriptions_this_month->count(),
            
            'users' => $total_users,
            'usersThisMonth' => $users_this_month,
            'subscriptions' => $total_subscriptions,
            'subscriptionsThisMonth' => $subscriptions_this_month,

            'pending_payments' => $pending_payments,
            'pending_orders' => $pending_orders,
            'in_progress_orders' => $in_progress_orders,

            'payments' => $payments->get(),
            'payments_at_year' => $payments_at_year,
            'payment_one_month' => $payment_one_month,
            'payment_3_month' => $payment_3_month,
            'payment_6_month' => $payment_6_month,
            'payment_year' => $payment_year,
            
            'expected_revenue_month' => $expected_revenue_month,
            'expected_revenue_3_month' => $expected_revenue_3_month,
            'expected_revenue_6_month' => $expected_revenue_6_month,
            'expected_revenue_year' => $expected_revenue_year,
        ]);
    }
}
