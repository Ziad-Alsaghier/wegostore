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
        //
        if($user->plan == Null && $user->role == 'user'){
            $user->userStore()->create();
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
