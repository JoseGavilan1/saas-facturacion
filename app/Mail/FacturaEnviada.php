<?php

namespace App\Mail;

use App\Models\Factura;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Attachment;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;
use Barryvdh\DomPDF\Facade\Pdf;

class FacturaEnviada extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(public Factura $factura) {}

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: "Factura Emitida: {$this->factura->numero}",
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'pdf.factura', // Reutilizamos la vista que ya creamos
        );
    }

    public function attachments(): array
    {
        // Generamos el PDF en tiempo real para adjuntarlo
        $pdf = Pdf::loadView('pdf.factura', ['factura' => $this->factura]);

        return [
            Attachment::fromData(fn () => $pdf->output(), "factura-{$this->factura->numero}.pdf")
                ->withMime('application/pdf'),
        ];
    }
}
