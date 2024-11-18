<?php

namespace App\Http\Controllers\api\v1\emails\upgrade;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\Mail;
use App\Mail\upgrade\ExpiredDayMail;
use App\Mail\upgrade\ExpiredWeekMail;

use App\Models\Order;
use App\Models\User;

class UpgradeController extends Controller
{
    public function __construct(private Order $orders, private User $users){}

    public function upgrade(){
        $add_week = Carbon::now()->addDays(7);
        $add_day = Carbon::now()->addDays(1);
        $orders = $this->orders
        ->with('users')
        ->get();
        $expire_after_week = $orders
        ->where('expire_date', $add_week);
        $expire_after_day = $orders
        ->where('expire_date', $add_day);
        foreach ($expire_after_week as $key => $item) {
            $data = [];
            if (!empty($item->extra_id)) {
                $data['type'] = 'extra';
            }
            elseif (!empty($item->plan_id)) {
                $data['type'] = 'plan';
            }
            Mail::to($request->user()->email)->send(new ExpiredWeekMail($data));
            Mail::to('wegotores@gmail.com')->send(new ExpiredWeekMail($data));
        }
        foreach ($expire_after_day as $key => $item) {
            Mail::to($request->user()->email)->send(new ExpiredDayMail($data));
            Mail::to('wegotores@gmail.com')->send(new ExpiredDayMail($data));
        }
        $expire_date = Carbon::now()->addYear();
    }
}
