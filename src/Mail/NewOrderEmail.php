<?php

namespace Elfcms\Shop\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\View;

class NewOrderEmail extends Mailable
{
    use Queueable, SerializesModels;

    public  $order, $view;

    /**
     * Create a new message instance.
     */
    public function __construct($order, $view = 'order.new')
    {
        $this->order = $order;
        $this->view = $view;
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'New Order Email',
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
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
        return new Content(
            view: $view,
        );
    }

    /**
     * Get the attachments for the message.
     *
     * @return array<int, \Illuminate\Mail\Mailables\Attachment>
     */
    public function attachments(): array
    {
        return [];
    }
}
