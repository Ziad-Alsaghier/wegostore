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
        $monthly = 0;
        $quarterly = 0;
        $semi_annual = 0;
        $yearly = 0;
        foreach ($orders as $order) {
            if (!empty($order->plans)) {
                $total += $order->plans->{$order->price_cycle} - 
                $order->plans->{'discount_' . $order->price_cycle};
                if ($order->price_cycle == 'monthly') {
                    $monthly += $order->plans->{$order->price_cycle} - 
                    $order->plans->{'discount_' . $order->price_cycle};
                }
                elseif ($order->price_cycle == 'quarterly') {
                    $quarterly += $order->plans->{$order->price_cycle} - 
                    $order->plans->{'discount_' . $order->price_cycle};
                }
                elseif ($order->price_cycle == 'semi_annual') {
                    $semi_annual += $order->plans->{$order->price_cycle} - 
                    $order->plans->{'discount_' . $order->price_cycle};
                }
                elseif ($order->price_cycle == 'yearly') {
                    $yearly += $order->plans->{$order->price_cycle} - 
                    $order->plans->{'discount_' . $order->price_cycle};
                }
            }
            if (!empty($order->domain)) {
                $total += $order->domain->price;
                $yearly += $total;
            }
            if (!empty($order->extra)) {
                $total += $order->extra->{$order->price_cycle} - 
                $order->extra->{'discount_' . $order->price_cycle}; 
                if ($order->price_cycle == 'monthly') {
                    $monthly += $order->extra->{$order->price_cycle} - 
                    $order->extra->{'discount_' . $order->price_cycle};
                }
                elseif ($order->price_cycle == 'quarterly') {
                    $quarterly += $order->extra->{$order->price_cycle} - 
                    $order->extra->{'discount_' . $order->price_cycle};
                }
                elseif ($order->price_cycle == 'semi_annual') {
                    $semi_annual += $order->extra->{$order->price_cycle} - 
                    $order->extra->{'discount_' . $order->price_cycle};
                }
                elseif ($order->price_cycle == 'yearly') {
                    $yearly += $order->extra->{$order->price_cycle} - 
                    $order->extra->{'discount_' . $order->price_cycle};
                }
            }
        }

        return [
            'total' => $total,
            'monthly' => $monthly,
            'quarterly' => $quarterly,
            'semi_annual' => $semi_annual,
            'yearly' => $yearly,
        ];
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
        $order_payment_items = $payments
        ->whereYear('created_at', now()->year)
		->get()
        ->pluck('orders');
        $order_payment = collect([]);
        foreach ($order_payment_items as $item) {
            $order_payment = $order_payment->merge($item);
        }
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
        $add_month = $currentDate->copy()->addMonthNoOverflow()->startOfMonth();
        $add_2_month = $currentDate->copy()->addMonthNoOverflow(2)->startOfMonth();
        $add_3_month = $currentDate->copy()->addMonthNoOverflow(3)->startOfMonth();
        $add_4_month = $currentDate->copy()->addMonthNoOverflow(4)->startOfMonth();
        $add_5_month = $currentDate->copy()->addMonthNoOverflow(5)->startOfMonth();
        $add_6_month = $currentDate->copy()->addMonthNoOverflow(6)->startOfMonth();
        $add_7_month = $currentDate->copy()->addMonthNoOverflow(7)->startOfMonth();
        $add_8_month = $currentDate->copy()->addMonthNoOverflow(8)->startOfMonth();
        $add_9_month = $currentDate->copy()->addMonthNoOverflow(9)->startOfMonth();
        $add_9_month = $currentDate->copy()->addMonthNoOverflow(9)->startOfMonth();
        $add_10_month = $currentDate->copy()->addMonthNoOverflow(10)->startOfMonth();
        $add_11_month = $currentDate->copy()->addMonthNoOverflow(11)->startOfMonth();
        $add_12_month = $currentDate->copy()->addMonthNoOverflow(12)->startOfMonth();
		$this_month = $currentDate->copy()->startOfMonth();
        $order_month = $this->orders
        ->where('expire_date', '<=', $add_month)
        ->where('expire_date', '>=', $this_month)
        ->with(['plans', 'domain', 'extra'])
        ->get();
        $order_2_month = $this->orders
        ->where('expire_date', '<=', $add_2_month)
        ->where('expire_date', '>=', $add_month)
        ->with(['plans', 'domain', 'extra'])
        ->get();
        $order_3_month = $this->orders
        ->where('expire_date', '<=', $add_3_month)
        ->where('expire_date', '>=', $add_2_month)
        ->with(['plans', 'domain', 'extra'])
        ->get();
        $order_4_month = $this->orders
        ->where('expire_date', '<=', $add_4_month)
        ->where('expire_date', '>=', $add_3_month)
        ->with(['plans', 'domain', 'extra'])
        ->get();
        $order_5_month = $this->orders
        ->where('expire_date', '<=', $add_5_month)
        ->where('expire_date', '>=', $add_4_month)
        ->with(['plans', 'domain', 'extra'])
        ->get();
        $order_6_month = $this->orders
        ->where('expire_date', '<=', $add_6_month)
        ->where('expire_date', '>=', $add_5_month)
        ->with(['plans', 'domain', 'extra'])
        ->get();
        $order_7_month = $this->orders
        ->where('expire_date', '<=', $add_7_month)
        ->where('expire_date', '>=', $add_6_month)
        ->with(['plans', 'domain', 'extra'])
        ->get();
        $order_8_month = $this->orders
        ->where('expire_date', '<=', $add_8_month)
        ->where('expire_date', '>=', $add_7_month)
        ->with(['plans', 'domain', 'extra'])
        ->get();
        $order_9_month = $this->orders
        ->where('expire_date', '<=', $add_9_month)
        ->where('expire_date', '>=', $add_8_month)
        ->with(['plans', 'domain', 'extra'])
        ->get();
        $order_10_month = $this->orders
        ->where('expire_date', '<=', $add_10_month)
        ->where('expire_date', '>=', $add_9_month)
        ->with(['plans', 'domain', 'extra'])
        ->get();
        $order_11_month = $this->orders
        ->where('expire_date', '<=', $add_11_month)
        ->where('expire_date', '>=', $add_10_month)
        ->with(['plans', 'domain', 'extra'])
        ->get();
        $order_12_month = $this->orders
        ->where('expire_date', '<=', $add_12_month)
        ->where('expire_date', '>=', $add_11_month)
        ->with(['plans', 'domain', 'extra'])
        ->get();
        $_month = $this->total($order_month);
        $_2_month = $this->total($order_2_month);
        $_3_month = $this->total($order_3_month);
        $_4_month = $this->total($order_4_month);
        $_5_month = $this->total($order_5_month);
        $_6_month = $this->total($order_6_month);
        $_7_month = $this->total($order_7_month);
        $_8_month = $this->total($order_8_month);
        $_9_month = $this->total($order_9_month);
        $_10_month = $this->total($order_10_month);
        $_11_month = $this->total($order_11_month);
        $_12_month = $this->total($order_12_month);

        $expected_revenue_month = $_month['total'];
        $monthly = $_month['monthly'];
        $quarterly_1 = $_month['quarterly'];
        $semi_1 = $_month['semi_annual'];
        $expected_revenue_2_month = $_2_month['total'] + $monthly;
        $monthly += $_2_month['monthly'];
        $quarterly_2 = $_2_month['quarterly'];
        $semi_2 = $_2_month['semi_annual'];
        $expected_revenue_3_month = $_3_month['total'] + $monthly;
        $quarterly_3 = $_3_month['quarterly'];
        $semi_3 = $_3_month['semi_annual'];
        $expected_revenue_4_month = $_4_month['total'] + $monthly + $quarterly_1;
        $quarterly_1 += $_4_month['quarterly'];
        $semi_4 = $_4_month['semi_annual'];
        $expected_revenue_5_month = $_5_month['total'] + $monthly + $quarterly_2;
        $semi_5 = $_5_month['semi_annual'];
        $expected_revenue_6_month = $_6_month['total'] + $monthly + $quarterly_3;
        $semi_6 = $_6_month['semi_annual'];
        $expected_revenue_7_month = $_7_month['total'] + $monthly + $quarterly_1 + $semi_1;
        $semi_1 += $_7_month['semi_annual'];
        $expected_revenue_8_month = $_8_month['total'] + $monthly + $quarterly_2 + $semi_2;
        $expected_revenue_9_month = $_9_month['total'] + $monthly + $quarterly_3 + $semi_3;
        $expected_revenue_10_month = $_10_month['total'] + $monthly + $quarterly_1 + $semi_4;
        $expected_revenue_11_month = $_11_month['total'] + $monthly + $quarterly_2 + $semi_5;
        $expected_revenue_12_month = $_12_month['total'] + $monthly + $quarterly_3 + $semi_6;

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
            'expected_revenue_2_month' => $expected_revenue_2_month,
            'expected_revenue_3_month' => $expected_revenue_3_month,
            'expected_revenue_4_month' => $expected_revenue_4_month,
            'expected_revenue_5_month' => $expected_revenue_5_month,
            'expected_revenue_6_month' => $expected_revenue_6_month,
            'expected_revenue_7_month' => $expected_revenue_7_month,
            'expected_revenue_8_month' => $expected_revenue_8_month,
            'expected_revenue_9_month' => $expected_revenue_9_month,
            'expected_revenue_10_month' => $expected_revenue_10_month,
            'expected_revenue_11_month' => $expected_revenue_11_month,
            'expected_revenue_12_month' => $expected_revenue_12_month,
            
            'subscripe_month' => count(array_unique($order_month->pluck('user_id')->toArray())),
            'subscripe_2_month' => count(array_unique($order_2_month->pluck('user_id')->toArray())),
            'subscripe_3_month' => count(array_unique($order_3_month->pluck('user_id')->toArray())),
            'subscripe_4_month' => count(array_unique($order_4_month->pluck('user_id')->toArray())),
            'subscripe_5_month' => count(array_unique($order_5_month->pluck('user_id')->toArray())),
            'subscripe_6_month' => count(array_unique($order_6_month->pluck('user_id')->toArray())),
            'subscripe_7_month' => count(array_unique($order_7_month->pluck('user_id')->toArray())),
            'subscripe_8_month' => count(array_unique($order_8_month->pluck('user_id')->toArray())),
            'subscripe_9_month' => count(array_unique($order_9_month->pluck('user_id')->toArray())),
            'subscripe_10_month' => count(array_unique($order_10_month->pluck('user_id')->toArray())),
            'subscripe_11_month' => count(array_unique($order_11_month->pluck('user_id')->toArray())),
            'subscripe_12_month' => count(array_unique($order_12_month->pluck('user_id')->toArray())),
        ]);
    }
}
