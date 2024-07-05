<?php

namespace Elfcms\Shop\Observers;

use Elfcms\Shop\Models\ShopInvoice;
use Illuminate\Support\Facades\Log;

class ShopInvoiceObserver
{
    /**
     * Handle the ShopInvoice "created" event.
     */
    public function created(ShopInvoice $shopInvoice): void
    {
        $number = $shopInvoice->id;
        $date = $shopInvoice->created_at->format('ymd') ?? date('ymd');
        $control = array_sum(str_split($date . $number));
        while ($control >= 10) {
            $control = array_sum(str_split($control));
        }
        $number = 'RE-'.$date . $control . str_repeat('0', (5 - strlen((string)$shopInvoice->id))) . $number;
        $shopInvoice->update([
            'number' => $number
        ]);
    }

    /**
     * Handle the ShopInvoice "updated" event.
     */
    public function updated(ShopInvoice $shopInvoice): void
    {
        //
    }

    /**
     * Handle the ShopInvoice "deleted" event.
     */
    public function deleted(ShopInvoice $shopInvoice): void
    {
        //
    }

    /**
     * Handle the ShopInvoice "restored" event.
     */
    public function restored(ShopInvoice $shopInvoice): void
    {
        //
    }

    /**
     * Handle the ShopInvoice "force deleted" event.
     */
    public function forceDeleted(ShopInvoice $shopInvoice): void
    {
        //
    }
}
