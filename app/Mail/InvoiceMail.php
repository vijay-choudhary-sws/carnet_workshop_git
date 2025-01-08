<?php

namespace App\Mail;

use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class InvoiceMail extends Mailable
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
        $title = $this->data['title'];
        $logo = $this->data['logo'];
        $customer = $this->data['customer'];
        $newjobcard = $this->data['newjobcard'];
        $vehicles = $this->data['vehicles'];
        $jobCardSpareParts = $this->data['jobCardSpareParts'];
        $jobCardExtraCharges = $this->data['jobCardExtraCharges'];
        $jobCardCustomerVoice = $this->data['jobCardCustomerVoice'];
        $jobCardAccessories = $this->data['jobCardAccessories'];
        $exitNote = $this->data['exitNote'];

        $this->data = [
            'customer' => getUserFullName($customer->id),
            'vehicle' => $vehicles->modelname,
            'number' => $vehicles->number_plate,
            'logo' => public_path('general_setting/' . $logo->logo_image),
        ];

        $data = compact('title', 'logo', 'customer', 'newjobcard', 'vehicles', 'jobCardSpareParts', 'jobCardExtraCharges','jobCardCustomerVoice','jobCardAccessories','exitNote');

        $pdf = PDF::loadView('new_jobcard.pdf', $data)
        ->setPaper('a4', 'portrait');
        
        return $this->subject('Vehicle Service Completed')
            ->view('mail.invoice-mail')
            ->attachData($pdf->output(), 'Invoice_'.$newjobcard->jobcard_numer.'.pdf', [
                'mime' => 'application/pdf',
            ])
            ->with('data', $this->data);
    }
}
