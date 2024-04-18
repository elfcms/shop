<?php

namespace Elfcms\Shop\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\View;

class NewOrderEmail extends Mailable
{
    use Queueable, SerializesModels;

    public  $order, $view;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($order, $view = 'order.new')
    {
        $this->order = $order;
        $this->view = $view;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        $view = $this->view;
        if (View::exists('emails.shop.' . $this->view)) {
            $view = 'emails.shop.' . $this->view;
        }
        if (View::exists('shop.emails.shop.' . $this->view)) {
            $view = 'shop.emails.shop.' . $this->view;
        }
        if (View::exists('shop::emails.shop.' . $this->view)) {
            $view = 'shop::emails.shop.' . $this->view;
        }
        if (View::exists('elfcms.emails.shop.' . $this->view)) {
            $view = 'elfcms.emails.shop.' . $this->view;
        }
        if (View::exists('elfcms.shop.emails.shop.' . $this->view)) {
            $view = 'elfcms.shop.emails.shop.' . $this->view;
        }
        if (View::exists('elfcms.modules.shop.emails.shop.' . $this->view)) {
            $view = 'elfcms.modules.shop.emails.shop.' . $this->view;
        }
        if (View::exists('elfcms::shop.emails.shop.' . $this->view)) {
            $view = 'elfcms::shop.emails.shop.' . $this->view;
        }

        return $this->view($view,['order'=>$this->order]);
    }
}
