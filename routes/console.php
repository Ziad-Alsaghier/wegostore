<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;

use Illuminate\Support\Facades\Schedule;
use App\Http\Controllers\api\v1\emails\upgrade\UpgradeController;
 
Schedule::call(function () {
    $upgrade = new UpgradeController();
    $upgrade->upgrade();
})->daily();

// Artisan::command('inspire', function () {
//     $this->comment(Inspiring::quote());
// })->purpose('Display an inspiring quote')->hourly();
