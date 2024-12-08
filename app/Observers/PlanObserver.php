<?php

namespace App\Observers;

use App\Models\Plan;
use Illuminate\Support\Facades\Log;

class PlanObserver
{
    /**
     * Handle the Plan "created" event.
     */
    public function created(Plan $plan): void
    {
          Log::info('Observer triggered for plan: ' . $plan->id);
        if (isset($plan->translationsData) && is_array($plan->translationsData)) {
            foreach ($plan->translationsData as $translation) {
                 $plan->translations()->create($translation);
            }
        }
        
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
