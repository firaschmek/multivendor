<?php

namespace App\Mail;

use App\Models\Order;
use App\Models\Vendor;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class VendorNewOrderMail extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    public Order $order;
    public Vendor $vendor;
    public $vendorItems;

    public function __construct(Order $order, Vendor $vendor, $vendorItems)
    {
        $this->order = $order;
        $this->vendor = $vendor;
        $this->vendorItems = $vendorItems;
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'طلب جديد #' . $this->order->order_number,
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.vendor-new-order',
        );
    }

    public function attachments(): array
    {
        return [];
    }
}
