<?php

use App\Http\Controllers\api\v1\admin\demoRequest\DemoRequestController;
use App\Http\Controllers\api\v1\admin\order\OrderController;
use App\Http\Controllers\api\v1\admin\payment\PaymentController;
use App\Http\Controllers\api\v1\admin\payment\PaymentMethodController;
use App\Http\Controllers\api\v1\admin\payment\PaymentPaymobController;
use App\Http\Controllers\api\v1\admin\plan\PlanController;
use App\Http\Controllers\api\v1\admin\profile\ProfileController;
use App\Http\Controllers\api\v1\admin\store\StoreController;
use App\Http\Controllers\api\v1\admin\extra\ExtraController;
use App\Http\Controllers\api\v1\admin\domain\DomainController;
use App\Http\Controllers\api\v1\admin\promoCode\PromoCodeController;
use App\Http\Controllers\api\v1\admin\User\UserController;
use App\Http\Controllers\api\v1\admin\subscripe\SubscriptionController;
use App\Http\Controllers\api\v1\admin\home\HomeController;
use App\Http\Controllers\api\v1\admin\tutorial_group\TutorialGroupController;
use App\Http\Controllers\api\v1\admin\tutorial\TutorialController;
use App\Http\Controllers\api\v1\admin\activity\ActivityController;
use App\Http\Controllers\api\v1\admin\admin\AdminController;
use App\Http\Controllers\api\v1\admin\welcome_offer\WelcomeOfferController;
use App\Http\Controllers\api\v1\admin\contact_us\ContactUsController;
use App\servic\PaymentPaymob;
use App\services\PleskService;
use Illuminate\Support\Facades\Route;

Route::prefix('/v1')->group(function () {
    // Route::withoutMiddleware()->group(function () { // When Need Make any Request Without Middleware

    // });
    Route::controller(HomeController::class)->prefix('home')->group(function () {
        Route::get('/', 'home')->name('home.home'); // Store Home
    });

Route::controller(DomainController::class)->prefix('domains')->group(function () {
    Route::get('/', 'domains_pending')->name('domains.domains_pending');
    Route::put('approve/{id}', 'approve_domain')->name('domains.approve_domain');
    Route::put('rejected/{id}', 'rejected_domain')->name('domains.rejected_domain');
});

Route::controller(WelcomeOfferController::class)->prefix('welcome_offer')
->group(function () {
    Route::get('/', 'view')->name('welcome_offer.view');
    Route::post('/add', 'create')->name('welcome_offer.create');
    Route::post('/update/{id}', 'modify')->name('welcome_offer.modify');
    Route::delete('/delete/{id}', 'delete')->name('welcome_offer.delete');
});

Route::controller(ContactUsController::class)->prefix('contact_us')
->group(function () {
    Route::get('/', 'view')->name('contact_us.view');
    Route::post('/add', 'create')->name('contact_us.create');
    Route::post('/update/{id}', 'modify')->name('contact_us.modify');
    Route::delete('/delete/{id}', 'delete')->name('contact_us.delete');
});

Route::controller(SubscriptionController::class)->prefix('subscripe')
->group(function () {
    Route::get('/', 'view')->name('subscripe.view');
    Route::post('/add', 'add')->name('subscripe.add');
    Route::post('/update', 'modify')->name('subscripe.modify');
    Route::delete('/delete/{id}', 'delete')->name('subscripe.delete');
});

    Route::controller(ActivityController::class)->prefix('activity')->group(function () {
        Route::get('/', 'view')->name('activity.view');
        Route::post('/add', 'store')->name('activity.store');
        Route::post('/update/{id}', 'modify')->name('activity.modify');
        Route::delete('/delete/{id}', 'delete')->name('activity.delete');
    });

    Route::controller(ProfileController::class)->prefix('profile')->group(function () {
        Route::put('update/', 'modify')->name('modify.update');
    });

    Route::prefix('payment')->group(function () {// -Payments
            // Start Payment Method
        Route::controller(PaymentMethodController::class)->group(function () {
            Route::post('method/create/', 'store')->name('store.paymentMethod'); // Store Payment Method
            Route::get('method/show/', 'show')->name('show.paymentMethod'); // Show payment Method
            Route::post('method/update/', 'modify')->name('modify.paymentMethod'); // update payment Method
            Route::delete('method/delete/{paymentMethod_id}', 'destroy')->name('destroy.paymentMethod'); // Destroy payment Method
        });
        // Start Payment
        Route::controller(PaymentController::class)->group(function () {
            Route::get('show/pending', 'bindPayment')->name('payment.show');// Show Payment Pending
            Route::get('show/history', 'historyPayment')->name('payment.show'); // Show Payment History
            Route::post('approve/{id}', 'approve_payment')->name('payment.approve');// Approve Payment
            Route::post('rejected/{id}', 'rejected_payment')->name('payment.rejected');// Rejected Payment
        });
    });
    // End Payment


    Route::controller(OrderController::class)->prefix('order')->group(function () {
        Route::get('show/pending', 'bindOrder')->name('payment.show'); // Show Payment Pending
        Route::get('show', 'all_orders')->name('payment.show'); // Show Payment Pending
        Route::post('update/{id}', 'process_action')->name('payment.show'); // Show Payment Pending
    });
    // Start Plan
    Route::controller(PlanController::class)->prefix('plan')->group(function () {
        Route::post('create/', 'store')->name('store.plan');
        Route::post('update/', 'modify')->name('update.plan');
        Route::get('show/', 'show')->name('show.plan')->withoutMiddleware(['api', 'IsAdmin', 'auth:sanctum']);
        Route::delete('delete/{plan_id}', 'destroy')->name('show.plan');
        Route::get('/', 'upgrade')->name('upgrade.plan');
    });
    // End Plan
    // Start Extra
    Route::prefix('extra')->group(function () {
        Route::get('/show', [ExtraController::class, 'view']);
        Route::post('/create', [ExtraController::class, 'store']);
        Route::post('/update/{id}', [ExtraController::class, 'modify']);
        Route::delete('/delete/{id}', [ExtraController::class, 'delete']);
        Route::put('/included/{extra}', [ExtraController::class, 'included']);
    });
    // End Extra
    // Start Promo Code
    Route::prefix('promoCode')->group(function () {
        Route::get('/show', [PromoCodeController::class, 'view']);
        Route::post('/create', [PromoCodeController::class, 'store']);
        Route::post('/update/{id}', [PromoCodeController::class, 'modify']);
        Route::delete('/delete/{id}', [PromoCodeController::class, 'delete']);
    });
    // End  Promo Code

    // Start User
    Route::prefix('users')->group(function () {
        // users/view
        Route::get('/view', [AdminController::class, 'view']);
        Route::put('/status/{id}', [AdminController::class, 'status']);
        Route::post('/add', [AdminController::class, 'create']);
        Route::post('/update/{id}', [AdminController::class, 'modify']);
        Route::delete('/delete/{id}', [AdminController::class, 'delete']);
    });
    // End  User

    // Start User
    Route::prefix('users')->group(function () {
        // users/view
        Route::get('/view', [UserController::class, 'view']);
        Route::put('/status/{id}', [UserController::class, 'status']);
        Route::post('/add', [UserController::class, 'create']);
        Route::post('/update/{id}', [UserController::class, 'modify']);
        Route::delete('/delete/{id}', [UserController::class, 'delete']);
        Route::get('/user_login/{id}', [UserController::class, 'user_login']);
    });
    // End  User

    Route::prefix('demoRequest')->group(function () {
        Route::get('/show', [DemoRequestController::class, 'view']);
        Route::post('/approved/{id}', [DemoRequestController::class, 'approved']);
    });
    Route::domain('{store}.wegostores.com')->group(function () {
        Route::controller(StoreController::class)->prefix('store')->group(function () {
            Route::post('approve', 'store_approve')->name('store.update'); // Store approval
            Route::get('show/pending', 'showPending')->name('show.stores'); // Show pending stores
            Route::get('show/showApproved', 'showApproved')->name('store.show_approved'); // Show approved stores
            Route::get('deleted_stores', 'deleted_stores')->name('store.deleted_stores'); // Show deleted stores
            Route::delete('delete/{store}', 'delete')->name('store.delete'); // Delete store
            Route::post('edit/{store}', 'edit')->name('store.edit'); // Edit store
            Route::get('show/approve', 'show_approve')->name('store.show_approve'); // Show approve-related data
        });
    });

    Route::controller(TutorialGroupController::class)->prefix('tutorial_group')->group(function () {
        Route::get('/', 'view')->name('tutorial_group.view');
        Route::post('/add', 'create')->name('tutorial_group.create');
        Route::post('/update/{id}', 'modify')->name('tutorial_group.update');
        Route::delete('delete/{id}', 'delete')->name('tutorial_group.delete');
    });
    Route::controller(TutorialController::class)->prefix('tutorial')->group(function () {
        Route::post('/add', 'create')->name('tutorial.create');
        Route::post('/update/{id}', 'modify')->name('tutorial.update');
        Route::delete('delete/{id}', 'delete')->name('tutorial.delete');
    });



    
});
