<?php

namespace App\Observers\admin\store;

use App\Models\Store;

class StoreObserver
{
    /**
     * Handle the Store "created" event.
     */
    public function created(Store $store): void
    {
        //
    }

    /**
     * Handle the Store "updated" event.
     */
     public function updating(Store $store): void
     {
     // 
        
            if($store->status == 'in_progress'){
            $store->status = 'pending';
            $store->order->update(['order_status'=>'in_progress']);
            }else{
                $store->status = 'approved';
                $store->order->update(['order_status'=>'done']);
            }
     }
    public function updated(Store $store): void
    {
        //
    }

    /**
     * Handle the Store "deleted" event.
     */
    public function deleted(Store $store): void
    {
        //
    }

    /**
     * Handle the Store "restored" event.
     */
    public function restored(Store $store): void
    {
        //
    }

    /**
     * Handle the Store "force deleted" event.
     */
    public function forceDeleted(Store $store): void
    {
        //
    }
}
