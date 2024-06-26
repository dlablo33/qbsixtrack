<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;
use Barryvdh\DomPDF\PDF;

class InvoiceMail extends Mailable
{
    use Queueable, SerializesModels;

    public $factura;
    public $pdf;

    public function __construct($factura, PDF $pdf)
    {
        $this->factura = $factura;
        $this->pdf = $pdf;
    }

    public function build()
    {
        return $this->view('invoice.remi-pdf')
                    ->subject('Factura #' . $this->factura->Numero_Factura)
                    ->attachData($this->pdf->output(), 'factura.pdf', [
                        'mime' => 'application/pdf',
                    ]);
    }
}

