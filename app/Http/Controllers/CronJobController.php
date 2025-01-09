<?php

namespace App\Http\Controllers;

use App\JobCardExtraCharge;
use App\JobCardsInspection;
use App\JobCardSparePart;
use App\Mail\SendInvoiceMail;
use App\NewJobCard;
use App\SendJobCardPdfMail;
use App\User;
use App\Vehicle;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Mail;

class CronJobController extends Controller
{
    public function generatePDF(Request $request)
    {
        $data = SendJobCardPdfMail::select('id', 'jobcard_id', 'template_type')
            ->whereHas('jobCard', function ($query) {
                $query->whereNotNull('id');
            })
            ->where(['is_generated' => 0, 'is_send' => 0])->take(10)->get();

        $i = 1;
        if (count($data) > 0) {
            foreach ($data as $value) {
                $id = $value->jobcard_id;
                $templateType = $value->template_type;

                $newjobcard = NewJobCard::with(['exitNote' => fn($query) => $query->select('note', 'new_job_card_id')])
                    ->where('id', $id)->first();

                if (!$newjobcard) {
                    continue;
                }

                $customer = User::select('id', 'name', 'lastname', 'mobile_no', 'email', 'address')
                    ->where('id', $newjobcard->customer_id)->first();
                $vehicles = Vehicle::select('id', 'number_plate', 'chassisno', 'modelname', 'vehiclebrand_id')
                    ->where('id', $newjobcard->vehicle_id)->first();
                $jobCardSpareParts = JobCardSparePart::where('jobcard_id', $id)->get();
                $jobCardExtraCharges = JobCardExtraCharge::where('jobcard_id', $id)->get();

                $inspections = JobCardsInspection::select('customer_voice', 'is_customer_voice')
                    ->where('jobcard_number', $newjobcard->jobcard_number)
                    ->whereIn('is_customer_voice', [1, 0])
                    ->get();
                $jobCardCustomerVoice = $inspections->where('is_customer_voice', 1);
                $jobCardAccessories = $inspections->where('is_customer_voice', 0);

                $title = 'View Invoice';
                $logo = DB::table('tbl_settings')->first();

                $data = compact('title', 'logo', 'customer', 'newjobcard', 'vehicles', 'jobCardSpareParts', 'jobCardExtraCharges', 'jobCardCustomerVoice', 'jobCardAccessories');

                $template = $templateType == 1 || $templateType == 2 ? 'new_jobcard.pdf' : 'new_jobcard.pdf.estimate-invoice';
                $filePrefix = $templateType == 1 || $templateType == 2 ? 'Invoice_' : 'EstimateInvoice_';

                $pdf = Pdf::loadView($template, $data)->setPaper('a4', 'portrait');

                $fileName = $filePrefix . $newjobcard->jobcard_number . '_' . time() . $i++ . '.pdf';
                $path = public_path('uploads/invoice_pdfs/' . $fileName);

                $pdf->save($path);

                SendJobCardPdfMail::where('id', $value->id)->update([
                    'path' => $fileName,
                    'is_generated' => 1,
                ]);
            }
        }

        return true;
    }

    public function sendMail()
    {
        $data = SendJobCardPdfMail::select('id', 'jobcard_id', 'template_type', 'path')
            ->whereHas('jobCard', function ($query) {
                $query->whereNotNull('id');
            })
            ->where(['is_generated' => 1, 'is_send' => 0])
            ->whereNotNull('path')
            ->take(10)->get();
        if (count($data) > 0) {
            $err = [];
            foreach ($data as $value) {
                $id = $value->jobcard_id;
                $templateType = $value->template_type;

                $newjobcard = NewJobCard::with(['exitNote' => fn($query) => $query->select('note', 'new_job_card_id')])
                    ->where('id', $id)->first();

                if (!$newjobcard) {
                    continue;
                }

                $customer = User::select('id', 'name', 'lastname', 'mobile_no', 'email', 'address')
                    ->where('id', $newjobcard->customer_id)->first();
                $vehicles = Vehicle::select('id', 'number_plate', 'modelname')
                    ->where('id', $newjobcard->vehicle_id)->first();

                $subject = $templateType == 1 || $templateType == 2 ? 'Vehicle Service Completed' : 'Estimation mail for your vehicle ' . $vehicles->modelname . ' service';
                $view = $templateType == 1 || $templateType == 2 ? 'mail.invoice-mail' : 'mail.estimate';
                $attachData = $value->path;
                $attachName = $templateType == 1 || $templateType == 2 ? 'Invoice_' . $newjobcard->jobcard_number . '.pdf' : 'EstimateInvoice_' . $newjobcard->jobcard_number . '.pdf';
                $customerName = getUserFullName($newjobcard->customer_id);
                $vehicle = $vehicles->modelname;
                $number = $vehicles->number_plate;
                $decline_url = route('estimate.decline', ['status' => 0, 'id' => base64_encode($newjobcard->id), 'userId' => base64_encode(base64_encode($newjobcard->customer_id))]);
                $accept_url = route('estimate.accept', ['status' => 1, 'id' => base64_encode($newjobcard->id), 'userId' => base64_encode(base64_encode($newjobcard->customer_id))]);

                $data = compact('subject', 'view', 'attachData', 'attachName', 'customerName', 'vehicle', 'number', 'decline_url', 'accept_url');

                if (!empty($customer->email)) {
                    try {
                        Mail::to($customer->email)->send(new SendInvoiceMail($data));

                        $filePath = public_path('uploads/invoice_pdfs/' . $attachData);
                        
                        SendJobCardPdfMail::where('id', $value->id)->delete();

                        if (File::exists($filePath)) {
                            File::delete($filePath);
                        }

                    } catch (\Exception $e) {
                        $err[] = 'Mail sending failed for Job Card ID: ' . $value->id . '. Error: ' . $e->getMessage();
                    }
                }
            }
        }
        if(count($err) > 0){
            return $err;
        }
        return true;
    }
}
