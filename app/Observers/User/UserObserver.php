<?php

namespace App\Observers\User;

use App\Models\StoreUser;
use App\Models\User;

class UserObserver
{
    /**
     * Handle the User "created" event.
     */
    public function creating(User $user): void
    {
        // This Is Ovrrited Attrebuted When Make Process
        $user->password = bcrypt($user->password);
    }
    public function created(User $user): void
    {
        // When Creating and Request plan Is Null Make Demo Request
        if($user->requestDemo == true ){
            $user->userDemoRequest()->create(['user_id'=>$user->id]);
        }
    }

    /**
     * Handle the User "updated" event.
     */
    public function updated(User $user): void
    {
        //
    }

    /**
     * Handle the User "deleted" event.
     */
    public function deleted(User $user): void
    {
        //
    }

    /**
     * Handle the User "restored" event.
     */
    public function restored(User $user): void
    {
        //
    }

    /**
     * Handle the User "force deleted" event.
     */
    public function forceDeleted(User $user): void
    {
        //
    }
}
