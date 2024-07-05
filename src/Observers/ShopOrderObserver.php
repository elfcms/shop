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
        $number = $shopOrder->id;
        $date = $shopOrder->created_at->format('ymd') ?? date('ymd');
        $control = array_sum(str_split($date . $number));
        while ($control >= 10) {
            $control = array_sum(str_split($control));
        }
        $number = $date . $control . str_repeat('0', (5 - strlen((string)$shopOrder->id))) . $number;
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
