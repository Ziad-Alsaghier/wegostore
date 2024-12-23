<?php

use App\Http\Controllers\api\v1\admin\payment\PaymentPaymobController;
use App\Http\Controllers\api\v1\user\profile\ProfileController;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\api\v1\user\SignUpController;

use App\Http\Controllers\api\v1\user\store\StoreController;

use App\Http\Controllers\api\v1\user\subscription\SubscriptionController;

use App\Http\Controllers\api\v1\user\extra\ExtraController;


use App\Http\Controllers\api\v1\user\cart\CartController;

use App\Http\Controllers\api\v1\user\payment\PaymentController;

use App\Http\Controllers\api\v1\user\tutorial\TutorialController;

use App\Http\Controllers\api\v1\user\requestDemo\RequestDemoController;

use App\Http\Controllers\api\v1\user\my_service\MyServiceController;

use App\Http\Controllers\api\v1\user\promo_code\PromoCodeController;

use App\Http\Controllers\api\v1\user\contact_us\ContactUsController;
use App\Http\Controllers\api\v1\user\domain\DomainController;
use App\Http\Controllers\api\v1\user\welcome_offer\WelcomeOfferController;

Route::prefix('/v1')->group(function () {
    Route::withoutMiddleware(['IsUser','auth:sanctum'])->group(function () { // This All Route out Of Middleware User
        Route::controller(SignUpController::class)->group(function (){ // Sign Up Routes
            Route::post(uri:'signUp', action:"signUp"); // POST /sign1Up
            Route::post(uri:'signUp/code', action:"code"); // POST /sign1Up/code
            Route::post(uri:'signUp/resend_code', action:"resend_code"); // POST /sign1Up/code
        });
    });
    Route::prefix('/profile')->group(function () {
        Route::controller(ProfileController::class)->group(function () {
            Route::get(uri:'/',action:'view')->name(name:'update.view');
            Route::post(uri:'/update',action:'modify')->name(name:'update.profile');
        });
    });
    Route::prefix('/contact_us')->group(function () {
        Route::controller(ContactUsController::class)->group(function () {
            Route::get(uri:'/',action:'view')->name(name:'contact_us.view');
        });
    });
    Route::prefix('/welcome_offer')->group(function () {
        Route::controller(WelcomeOfferController::class)->group(function () {
            Route::get(uri:'/',action:'view')->name(name:'welcome_offer.view');
        });
    });
    Route::prefix('/my_service')->group(function () {
        Route::controller(MyServiceController::class)->group(function () {
            Route::get(uri:'/',action:'my_service')->name(name:'myService.my_service');
        });
    });
    Route::prefix('/promocode')->group(function () {
        Route::controller(PromoCodeController::class)->group(function () {
            Route::post(uri:'/',action:'promo_code')->name(name:'promo_code.promo_code');
        });
    });
    Route::prefix('/store')->group(function () {
        Route::controller(StoreController::class)->group(function () {
            Route::get(uri:'/',action:'stores')->name(name:'stores.view');
            Route::post(uri:'/add',action:'make_store')->name(name:'stores.make_store');
            Route::put(uri:'/delete/{id}',action:'delete_store')->name(name:'stores.delete_store');
        });
    });
    Route::prefix('/subscription')->group(function () {
        Route::controller(SubscriptionController::class)->group(function () {
            Route::get(uri:'/',action:'plans')->name(name:'subscription.view');
            Route::get(uri:'/payment_methods',action:'payment_methods')->name(name:'subscription.payment_methods');
            Route::post(uri:'/buy_plan',action:'buy_plan')->name(name:'subscription.buy_plan');
        });
    });
    Route::prefix('/domains')->group(function () {
        Route::controller(DomainController::class)->group(function () {
            Route::get('/my_domains', action: 'my_domains')->name('domains.my_domains');

            // Domain Requests (pending, or approval required)
            Route::get('/domain_request', 'domain_request')->name('domains.domain_request');

            // Add a new domain (create store and subdomain automatically)
            Route::post('/add_domain', 'add_domain')->name('domains.add_domain');
        });
    });
    Route::prefix('/extra')->group(function () {
        Route::controller(ExtraController::class)->group(function () {
            Route::get(uri:'/',action:'view')->name(name:'extra.view');

        });
    });
    Route::prefix('/cart')->group(function () {
        Route::controller(CartController::class)->group(function () {
            Route::post(uri:'/',action:'payment')->name(name:'cart.payment');
            Route::post('pending', 'check_pending');
        });
    });
    Route::prefix('/payment')->group(function () {
        Route::controller(PaymentController::class)->group(function () {
            Route::get(uri:'/history',action:'history')->name(name:'payment.history');
        });
    });
    Route::prefix('/tutorial')->group(function () {
        Route::controller(TutorialController::class)->group(function () {
            Route::get(uri:'/',action:'tutorials')->name(name:'tutorial.tutorials');
        });
    });
    //  Start Request Demo
        Route::prefix('demoRequest')->group(function () {
            Route::post('/create',[RequestDemoController::class,'store'] );
            Route::get('/show',[RequestDemoController::class,'view'] );
        });
    //  End  Request Demo
        Route::prefix('payment')->group(function () {
        Route::any('/credit',[PaymentPaymobController::class, 'credit']);
        Route::get('/callback',[PaymentPaymobController::class, 'callback'])->withoutMiddleware(['IsUser','auth:sanctum']);
    });

})->middleware('IsUser');
