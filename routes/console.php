<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Carbon\Carbon;
use Illuminate\Support\Facades\Mail; 
use App\Mail\upgrade\ExpiredWeekMail;

use App\Models\Order;

use Illuminate\Support\Facades\Schedule;
use App\Http\Controllers\api\v1\emails\upgrade\UpgradeController;
 
Schedule::call(function () {
    $add_week = Carbon::now()->addDays(7)->format('Y-m-d');
    $add_day = Carbon::now()->addDays(1)->format('Y-m-d');
    $orders = Order::
    with('users')
    ->get();
    $expire_after_week = $orders
    ->where('expire_date', $add_week);
    $expire_after_day = $orders
    ->where('expire_date', $add_day);
    foreach ($expire_after_week as $key => $item) {
        $data = [];
        if (!empty($item->extra_id)) {
            $data['service_name'] = $item->extra->name;
            $data['type'] = 'extra';
        }
        elseif (!empty($item->plan_id)) {
            $data['service_name'] = $item->plans->name;
            $data['type'] = 'plan';
        }
        elseif (!empty($item->domain_id)) {
            $data['service_name'] = $item->domain->name;
            $data['type'] = 'domain';
        }
        else {
            $data['service_name'] = '';
            $data['type'] = '';
        }
        $data['user'] = $item->users;
        $data['time'] = 'Week';
        $data['role'] = 'user';
        Mail::to($item->users->email)->send(new ExpiredWeekMail($data));
        $data['role'] = 'admin';
        Mail::to('wegotores@gmail.com')->send(new ExpiredWeekMail($data));
    }
    foreach ($expire_after_day as $key => $item) {
        $data = [];
        if (!empty($item->extra_id)) {
            $data['service_name'] = $item->extra->name;
            $data['type'] = 'extra';
        }
        elseif (!empty($item->plan_id)) {
            $data['service_name'] = $item->plans->name;
            $data['type'] = 'plan';
        }
        elseif (!empty($item->domain_id)) {
            $data['service_name'] = $item->domain->name;
            $data['type'] = 'domain';
        }
        else {
            $data['service_name'] = '';
            $data['type'] = '';
        }
        $data['user'] = $item->users;
        $data['time'] = 'Day';
        $data['role'] = 'user';
        Mail::to($item->users->email)->send(new ExpiredWeekMail($data));
        $data['role'] = 'admin';
        Mail::to('wegotores@gmail.com')->send(new ExpiredWeekMail($data));
    }
    $expire_date = Carbon::now()->addYear();

    return true;
})->dailyAt('13:00');

// Artisan::command('inspire', function () {
//     $this->comment(Inspiring::quote());
// })->purpose('Display an inspiring quote')->hourly();
