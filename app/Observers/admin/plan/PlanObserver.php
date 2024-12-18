<?php

namespace App\Observers\admin\plan;

use App\Models\Plan;

class PlanObserver
{
    /**
     * Handle the Plan "created" event.
     */
    public function created(Plan $plan): void
    {
        //
         $translations =  $plan->translations;
            foreach ($translations as $langs) {
                if($langs->locale == 'en'){
               $plan->translations();
                $langs->value = $plan->name;
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
