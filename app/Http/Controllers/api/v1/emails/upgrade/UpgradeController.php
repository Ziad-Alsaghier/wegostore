<?php

namespace App\Http\Controllers\api\v1\emails\upgrade;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\Mail; 
use App\Mail\upgrade\ExpiredWeekMail;

use App\Models\Order;
use App\Models\User;

class UpgradeController extends Controller
{

    public function upgrade(){
    }
}
