<?php

namespace App\Observers;

use App\Models\Extra;
use Illuminate\Support\Facades\Log;

class ExtraObserver
{
    /**
     * Handle the Extra "created" event.
     */
    public function created(Extra $extra): void
    {
        //
        
    }

    /**
     * Handle the Extra "updated" event.
     */
    public function updating(Extra $extra): void
    {
        //
        $translations =  $extra->translations;
            foreach ($translations as $langs) {
                if($langs->locale == 'en'){
               $extra->translations();
                $langs->value = $extra->name;
            }
                # code...
            }
    }

    /**
     * Handle the Extra "deleted" event.
     */
    public function deleted(Extra $extra): void
    {
        //
    }

    /**
     * Handle the Extra "restored" event.
     */
    public function restored(Extra $extra): void
    {
        //
    }

    /**
     * Handle the Extra "force deleted" event.
     */
    public function forceDeleted(Extra $extra): void
    {
        //
    }
}
