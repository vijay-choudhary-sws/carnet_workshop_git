<?php

namespace App\Mail;

use Barryvdh\DomPDF\Facade\Pdf;
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
        $title = $this->data['title'];
        $logo = $this->data['logo'];
        $customer = $this->data['customer'];
        $newjobcard = $this->data['newjobcard'];
        $vehicles = $this->data['vehicles'];
        $jobCardSpareParts = $this->data['jobCardSpareParts'];
        $jobCardExtraCharges = $this->data['jobCardExtraCharges'];
        $jobCardCustomerVoice = $this->data['jobCardCustomerVoice'];
        $jobCardAccessories = $this->data['jobCardAccessories'];

        $this->data = [
            'customer' => getUserFullName($customer->id),
            'vehicle' => $vehicles->modelname,
            'decline_url' => route('estimate.decline', ['status' => 0,'id' => base64_encode($newjobcard->id),'userId' =>  base64_encode(base64_encode($customer->id))]),
            'accept_url' => route('estimate.accept', ['status' => 1,'id' => base64_encode($newjobcard->id),'userId' =>  base64_encode(base64_encode($customer->id))]),
        ];

        $data = compact('title', 'logo', 'customer', 'newjobcard', 'vehicles', 'jobCardSpareParts', 'jobCardExtraCharges', 'jobCardCustomerVoice', 'jobCardAccessories');
        $pdf = PDF::loadView('new_jobcard.pdf.estimate-invoice', $data)
            ->setPaper([0, 0, 595.28, 1000]);

        return $this->subject('Estimation mail for your vehicle ' . $vehicles->modelname . ' service')
            ->view('mail.estimate')
            ->attachData($pdf->output(), 'estimate.pdf', [
                'mime' => 'application/pdf',
            ])
            ->with('data', $this->data);
    }
}
