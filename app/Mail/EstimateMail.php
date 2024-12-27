<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class EstimateMail extends Mailable
{
    use Queueable, SerializesModels;

    public $data;

    /**
     * Create a new message instance.
     */
    public function __construct($data)
    {
        $this->data = $data;
    }

    /**
     * Build the message.
     */
    public function build()
    {
        $pdf = \PDF::loadView('mail.estimate-pdf', ['data' => $this->data]);

        return $this->subject('Estimate for Your Request')
            ->view('mail.estimate')
            ->attachData($pdf->output(), 'estimate.pdf', [
                'mime' => 'application/pdf',
            ])
            ->with('data', $this->data);
    }
}
