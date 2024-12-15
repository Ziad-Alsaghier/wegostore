<?php

namespace App\Observers\plan;

use App\Models\Plan;

class PlanObserver
{
    /**
     * Handle the Plan "created" event.
     */
    public function created(Plan $plan): void
    {
        //
    }

    /**
     * Handle the Plan "updated" event.
     */
    public function updated(Plan $plan): void
    {
        //
    }

    /**
     * Handle the Plan "deleted" event.
     */
    public function deleted(Plan $plan): void
    {
        //
    }

    /**
     * Handle the Plan "restored" event.
     */
    public function restored(Plan $plan): void
    {
        //
    }

    /**
     * Handle the Plan "force deleted" event.
     */
    public function forceDeleted(Plan $plan): void
    {
        //
    }
}
