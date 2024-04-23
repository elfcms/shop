<?php

namespace Elfcms\Shop\Observers;

use Elfcms\Shop\Models\ShopOrder;
use Illuminate\Support\Facades\Log;

class ShopOrderObserver
{
    /**
     * Handle the ShopOrder "created" event.
     */
    public function created(ShopOrder $shopOrder): void
    {
        $number = date('ym-d-') . str_repeat('0', (6 - strlen((string)$shopOrder->id))) . $shopOrder->id;
        $shopOrder->update([
            'number' => $number
        ]);
    }

    /**
     * Handle the ShopOrder "updated" event.
     */
    public function updated(ShopOrder $shopOrder): void
    {
        //
    }

    /**
     * Handle the ShopOrder "deleted" event.
     */
    public function deleted(ShopOrder $shopOrder): void
    {
        //
    }

    /**
     * Handle the ShopOrder "restored" event.
     */
    public function restored(ShopOrder $shopOrder): void
    {
        //
    }

    /**
     * Handle the ShopOrder "force deleted" event.
     */
    public function forceDeleted(ShopOrder $shopOrder): void
    {
        //
    }
}
