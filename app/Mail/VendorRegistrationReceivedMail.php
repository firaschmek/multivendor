<?php

namespace App\Mail;

use App\Models\Vendor;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class VendorRegistrationReceivedMail extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    public Vendor $vendor;

    public function __construct(Vendor $vendor)
    {
        $this->vendor = $vendor;
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'تم استلام طلب التسجيل كبائع',
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.vendor-registration-received',
        );
    }

    public function attachments(): array
    {
        return [];
    }
}
