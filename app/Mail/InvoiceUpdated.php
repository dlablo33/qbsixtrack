<?php


namespace App\Mail;

use App\Invoice;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class InvoiceUpdated extends Mailable
{
    use Queueable, SerializesModels;

    public $invoice;

    public function __construct(Invoice $invoice)
    {
        $this->invoice = $invoice;
    }

    public function build()
    {
        return $this->view('emails.invoice_updated')
            ->subject('Invoice Updated')
            ->with(['invoice' => $this->invoice]);
    }
}
