<?php

namespace Elfcms\Shop\Http\Controllers;

use App\Http\Controllers\Controller;
use Barryvdh\DomPDF\Facade\Pdf;
use Elfcms\Elfcms\Events\SomeMailEvent;
use Elfcms\Elfcms\Models\EmailEvent;
use Elfcms\Shop\Models\ShopInvoice;
use Elfcms\Shop\Models\ShopInvoiceStatus;
use Elfcms\Shop\Models\ShopOrder;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\View;

class InvoiceController extends Controller
{
    public function set(ShopOrder $order)
    {
        $addDays = 30;
        $status = ShopInvoiceStatus::firstOrCreate(
            ['code' => 'N'],
            ['name' => 'New','is_complited'=>0]
        );
        $invoiceData = [
            'order_id' => $order->id,
            'customer_id' => $order->customer->id,
            'invoice_status_id' => $status->id,
            'start_sum' => $order->start_sum,
            'discount_sum' => $order->discount_sum,
            'clear_sum' => $order->clear_sum,
            'delivery_sum' => $order->delivery_sum,
            'result_sum' => $order->result_sum,
            'deadline' => now()->addDays($addDays),
        ];
        $invoice = ShopInvoice::create($invoiceData);
        if (!$invoice) {
            return redirect(route('admin.selling.orders',$order))->withErrors([__('shop::default.invoice_not_created')]);
        }
        $view = 'elfcms::admin.pdf.invoices.default';
        if (View::exists('pdf.invoices.default')) {
            $view = 'pdf.invoices.default';
        }
        elseif (View::exists('public.pdf.invoices.default')) {
            $view = 'public.pdf.invoices.default';
        }
        $destination = '/elfcms/pdf/invoices';
        if (!file_exists($destination)) {
            Storage::makeDirectory($destination);
        }
        $file = $destination . '/' . $invoice->number . '.pdf';
        $pdf = Pdf::loadView($view, [
            'order' => $order,
            'invoice' => $invoice
        ]);
        $pdf->save(file_path($file,true));
        $invoice->update(['file' => $file]);
        $event = EmailEvent::where('code','order-invoice')->first();
        if ($event) {
            event(new SomeMailEvent('order-invoice', ['to' => $order->customer->user->email, 'params' => ['order' => $order], 'attach'=>file_path($file,true)]));
        }
        return redirect(route('admin.selling.orders.show',$order))->with('success',__('shop::default.create_invoice_successfully'));
    }

    public function cancel(ShopInvoice $invoice) {
        $status = ShopInvoiceStatus::firstOrCreate(['code'=>'C'],['name'=>'Canceled','is_complited'=>1]);
        $invoice->update(['invoice_status_id' => $status->id]);
        return redirect(route('admin.selling.orders.show',$invoice->order));
    }

    public function pay(ShopInvoice $invoice) {
        $status = ShopInvoiceStatus::firstOrCreate(['code'=>'P'],['name'=>'Paid','is_complited'=>1]);
        $invoice->update(['invoice_status_id' => $status->id]);
        return redirect(route('admin.selling.orders.show',$invoice->order));
    }

}
