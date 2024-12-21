<?php

namespace App\Observers;

use App\Models\Tutorial;

class TutorialObserver
{
    /**
     * Handle the Tutorial "created" event.
     */
    public function created(Tutorial $tutorial): void
    {
          // This Is After Creat New Activity
          $translation = ['locale'=>'en','key'=>'name','value'=>$tutorial->name];
          $tutorial->translations()->create($translation );
    }

    /**
     * Handle the Tutorial "updated" event.
     */
    public function updated(Tutorial $tutorial): void
    {
        //
    }

    /**
     * Handle the Tutorial "deleted" event.
     */
    public function deleted(Tutorial $tutorial): void
    {
        //
    }

    /**
     * Handle the Tutorial "restored" event.
     */
    public function restored(Tutorial $tutorial): void
    {
        //
    }

    /**
     * Handle the Tutorial "force deleted" event.
     */
    public function forceDeleted(Tutorial $tutorial): void
    {
        //
    }
}
