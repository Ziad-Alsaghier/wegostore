<?php

namespace App\Observers;

use App\Models\TutorialGroup;

class TutorialGroupObserver
{
    /**
     * Handle the TutorialGroup "created" event.
     */
    public function created(TutorialGroup $tutorialGroup): void
    {
        //
            // This Is After Creat New Activity
            $translation = ['locale'=>'en','key'=>'name','value'=>$tutorialGroup->name];
            $tutorialGroup->translations()->create($translation );
    }

    /**
     * Handle the TutorialGroup "updated" event.
     */
    public function updated(TutorialGroup $tutorialGroup): void
    {
        //
    }

    /**
     * Handle the TutorialGroup "deleted" event.
     */
    public function deleted(TutorialGroup $tutorialGroup): void
    {
        //
    }

    /**
     * Handle the TutorialGroup "restored" event.
     */
    public function restored(TutorialGroup $tutorialGroup): void
    {
        //
    }

    /**
     * Handle the TutorialGroup "force deleted" event.
     */
    public function forceDeleted(TutorialGroup $tutorialGroup): void
    {
        //
    }
}
