<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class SendInvoiceMail extends Mailable
{
    use Queueable, SerializesModels;

    public $data;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($data)
    {
        $this->data = $data;
    }

   
    public function build()
    {
        
        $subject = $this->data['subject'];
        $view = $this->data['view'];
        $filePath = public_path('uploads/invoice_pdfs/' . $this->data['attachData']);
        $attachName = $this->data['attachName'];

        $this->data = [
            'customer' => $this->data['customerName'],
            'vehicle' => $this->data['vehicle'],
            'number' => $this->data['number'],
            'decline_url' => $this->data['decline_url'],
            'accept_url' => $this->data['accept_url'],
        ];

        return $this->subject($subject)
            ->view($view)
            ->attach($filePath, [ 
                'as' => $attachName,
                'mime' => 'application/pdf',
            ])
            ->with('data', $this->data);
    }
}
