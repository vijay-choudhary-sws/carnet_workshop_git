<?php

namespace App\Http\Controllers\JobCard;

use App\Http\Controllers\Controller;
use App\{ExitNote, Image, JobCardExtraCharge, JobCardsDentMark, JobCardSparePart, JobCardImage, JobCardsInspection, NewJobCard, ProductStock, SendJobCardPdfMail, Setting, SparePartLabel, User, Vehicle};
use App\Mail\EstimateMail;
use App\Mail\InvoiceMail;
use Illuminate\Http\Request;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\{Auth, DB, Storage, Mail};
use Illuminate\Support\Str;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;

class JobCardController extends Controller
{
    protected $route;

    protected $single_heading;

    protected $notificationService;

    public function __construct()
    {
        $this->route = new \stdClass;
    }
    public function index()
    {
        $jobcardsCount = NewJobCard::where('user_id', Auth::id())->get();
        if (isset($_GET['type'])) {
            $type = $_GET['type'];

            if ($_GET['type'] == "") {
                $jobcards = NewJobCard::where('user_id', Auth::id())->orderBy('id', 'DESC')->get();
            } else {
                $jobcards = NewJobCard::where(['status' => $type, 'user_id' => Auth::id()])->orderBy('id', 'DESC')->get();
            }
        } else {
            $jobcards = NewJobCard::where('user_id', Auth::id())->orderBy('id', 'DESC')->get();
        }

        return view('new_jobcard.list', compact('jobcards', 'jobcardsCount'));
    }
    public function add()
    {
        $customers = User::where([['role', 'Customer'], ['soft_delete', 0]])->get();
        $supervisors = User::where([['role', 'supportstaff'], ['soft_delete', 0]])->get();
        $country = DB::table('tbl_countries')->get()->toArray();
        //vehicle add
        $vehical_type = DB::table('tbl_vehicle_types')->where('soft_delete', '=', 0)->get()->toArray();
        $vehical_brand = DB::table('tbl_vehicle_brands')->where('soft_delete', '=', 0)->get()->toArray();
        $fuel_type = DB::table('tbl_fuel_types')->where('soft_delete', '=', 0)->get()->toArray();
        $color = DB::table('tbl_colors')->where('soft_delete', '=', 0)->get()->toArray();
        $model_name = DB::table('tbl_model_names')->where('soft_delete', '=', 0)->get()->toArray();

        return view('new_jobcard.add', compact('country', 'vehical_type', 'vehical_brand', 'fuel_type', 'color', 'model_name', 'supervisors'));
    }
    public function store(Request $request)
    {

        $this->validate(
            $request,
            [
                'jobcard_number' => 'required|string|max:255',
                'customer_name' => 'required|exists:users,id',
                'vehicle_id' => 'required|exists:tbl_vehicles,id',
                // 'total_amount' => 'required|numeric|min:1',
                // 'total_discount' => 'required|numeric|min:0',
                // 'final_amount' => 'required|numeric|min:1',
                // 'advance' => 'required|numeric|min:0',
                // 'balance_amount' => 'required|numeric',
                'km_reading' => 'required|integer|min:0',
                'fual_level' => 'required|integer|min:1',
                'supervisor' => 'required|integer',
                // 'jobcard_item_id.*' => 'required|integer|min:1',
                // 'jobcard_quantity.*' => 'required|integer|min:1',
                // 'jobcard_price.*' => 'required|numeric|min:1',
                // 'jobcard_total_amount.*' => 'required|numeric|min:1',
                // 'jobcard_discount.*' => 'required|numeric|min:0',
                // 'jobcard_final_amount.*' => 'required|numeric|min:1',
                // 'employee.*' => 'required|integer|min:1',
                // 'label.*' => 'required|string|max:255',
                // 'charge.*' => 'required|numeric|min:1',
            ],
            [
                'customer_name.required' => 'The Customer Name field is required.',
                'customer_name.exists' => 'The selected Customer Name is invalid.',
                'vehicle_id.required' => 'The Vehicle field is required.',
                'vehicle_id.exists' => 'The selected Vehicle is invalid.',
            ]
        );
        // $items = $request->jobcard_item_id ?? 0;
        // $qty = $request->jobcard_quantity ?? 0;
        // $price = $request->jobcard_price ?? 0;
        // $totalAmount = $request->jobcard_total_amount ?? 0;
        // $discount = $request->jobcard_discount ?? 0;
        // $finalAmount = $request->jobcard_final_amount ?? 0;
        // $machanic = $request->employee ?? 0;
        $vehicleId = $request->vehicle_id ?? 0;
        // echo "<pre>";print_r($machanic);die;
        $customer = User::with([
            'vehicles' => function ($qry) use ($vehicleId) {
                $qry->where('id', $vehicleId);
            }
        ])->find($request->customer_name);
        $jobcard = new NewJobCard;
        $jobcard->jobcard_number = $request->jobcard_number;
        $jobcard->customer_id = $request->customer_name;
        $jobcard->customer_name = strtoupper($customer->name . ' ' . $customer->lastname);
        $jobcard->vehicle_id = $vehicleId;
        $jobcard->vehical = $customer->vehicles->first()->modelname;
        $jobcard->vehical_number = $customer->vehicles->first()->number_plate;
        $jobcard->entry_date = Carbon::now();
        $jobcard->amount = $request->total_amount ?? 0;
        $jobcard->discount = $request->total_discount ?? 0;
        $jobcard->final_amount = $request->final_amount ?? 0;
        $jobcard->advance = $request->advance ?? 0;
        $jobcard->balance_amount = $request->balance_amount ?? 0;
        $jobcard->km_runing = $request->km_reading;
        $jobcard->fual_level = $request->fual_level;
        $jobcard->supervisor_id = $request->supervisor;
        $jobcard->sms_alert = $request->sms_alert ?? 0;
        $jobcard->delivery_date = $request->delivery_date;
        $jobcard->delivery_time = $request->delivery_time;
        $jobcard->user_id = $request->user()->id;
        $jobcard->save();
        // if (!empty($items)) {
        //     if (count($items) > 0) {
        //         foreach ($items as $itemKey => $itemValue) {
        //             $labelName = SparePartLabel::select('title')->find($itemValue);
        //             $item = new JobCardSparePart;
        //             $item->jobcard_id = $jobcard->id;
        //             $item->stock_label_id = $itemValue;
        //             $item->stock_label_name = $labelName->title;
        //             $item->quantity = $qty[$itemKey];
        //             $item->price = $price[$itemKey];
        //             $item->total_amount = $totalAmount[$itemKey];
        //             $item->discount = $discount[$itemKey];
        //             $item->final_amount = $finalAmount[$itemKey];
        //             $item->machanic_id = $machanic[$itemKey];
        //             $item->save();
        //         }
        //     }
        // }

        // if (isset($request->label)) {
        //     foreach ($request->label as $key => $label) {
        //         $extraCharge = new JobCardExtraCharge;
        //         $extraCharge->jobcard_id = $jobcard->id;
        //         $extraCharge->label = $label;
        //         $extraCharge->charges = $request->charge[$key];
        //         $extraCharge->save();
        //     }
        // }

        return response()->json(['status' => 1]);
    }

    public function edit($id)
    {
        $jobcard = NewJobCard::with('jobCardSpareParts')->find($id);
        $employee = User::where(['role' => 'employee', 'soft_delete' => 0])->get();
        $supervisors = User::where([['role', 'supportstaff'], ['soft_delete', 0]])->get();
        $country = DB::table('tbl_countries')->get()->toArray();

        $jobCardsDentMark = JobCardsDentMark::where('jobcard_number', $jobcard->jobcard_number)->first();
        $jobCardscustomervoice = JobCardsInspection::where('jobcard_number', $jobcard->jobcard_number)->where('is_customer_voice', 1)->get();
        $jobCardsworknote = JobCardsInspection::where('jobcard_number', $jobcard->jobcard_number)->where('is_customer_voice', 0)->get();
        $jobCardsaccessary = JobCardsInspection::where('jobcard_number', $jobcard->jobcard_number)->where('is_customer_voice', 2)->get();
        $jobCardsImage = JobCardImage::where('job_card_number', $jobcard->jobcard_number)->first('image_id');
        $jobCardExtraCharges = JobCardExtraCharge::where('jobcard_id', $jobcard->id)->get();

        // echo "<prE>";print_r($jobCardExtraCharges->toArray());die;
        //vehicle add
        $vehical_type = DB::table('tbl_vehicle_types')->where('soft_delete', '=', 0)->get()->toArray();
        $vehical_brand = DB::table('tbl_vehicle_brands')->where('soft_delete', '=', 0)->get()->toArray();
        $fuel_type = DB::table('tbl_fuel_types')->where('soft_delete', '=', 0)->get()->toArray();
        $color = DB::table('tbl_colors')->where('soft_delete', '=', 0)->get()->toArray();
        $model_name = DB::table('tbl_model_names')->where('soft_delete', '=', 0)->get()->toArray();

        return view('new_jobcard.edit', compact('jobcard', 'employee', 'country', 'jobCardsDentMark', 'jobCardsworknote', 'jobCardsaccessary', 'jobCardsImage', 'jobCardscustomervoice', 'vehical_type', 'vehical_brand', 'fuel_type', 'color', 'model_name', 'jobCardExtraCharges', 'supervisors'));
    }

    public function update(Request $request)
    {
        $jobcard = NewJobCard::find($request->id);

        $jobstatus = $jobcard->status;
        // echo "<pre>";print_r($request->all());die;

        if ($jobcard->status == 2) {

            $this->validate(
                $request,
                [
                    'id' => 'required|exists:new_job_cards,id',
                    'final_discount' => 'required|numeric|min:0',
                    'final_paid' => 'required|numeric|min:0',
                    'balance_amount' => 'required|in:0|numeric',
                ],
                [
                    'final_discount.required' => "Discount Field is required.",
                    'final_paid.required' => "Pay Field is required.",
                    'final_discount.numeric' => "Discount Field should be a valid number.",
                    'final_paid.numeric' => "Pay Field should be a valid number.",
                    'final_discount.min' => "Discount Field should be a valid number.",
                    'final_paid.min' => "Pay Field should be a valid number.",
                    'balance_amount.in' => 'The Pending amount must be zero.',
                    'balance_amount.required' => 'The Pending amount is required.',
                    'balance_amount.numeric' => 'The Pending amount must be a number.',
                ]
            );

            $jobcard->final_discount = $request->final_discount;
            $jobcard->final_paid = $request->final_paid;
            $jobcard->amount = $request->total_amount;
            $jobcard->discount = $request->total_discount;
            $jobcard->final_amount = $request->final_amount;
            $jobcard->advance = $request->advance;
            $jobcard->balance_amount = $request->balance_amount;
            $jobcard->status = 3;
            $jobcard->save();

            $SendJobCardPdfMail = new SendJobCardPdfMail;
            $SendJobCardPdfMail->jobcard_id = $jobcard->id;
            $SendJobCardPdfMail->template_type = 2;
            $SendJobCardPdfMail->save();

            return response()->json(['status' => 1]);
        }

        // echo "<pre>";print_r($request->all());die;
        $this->validate(
            $request,
            [
                'id' => 'required|exists:new_job_cards,id',
                'jobcard_number' => 'required|string|max:255',
                'customer_name' => 'required|exists:users,id',
                'vehicle_id' => 'required|exists:tbl_vehicles,id',
                'total_amount' => 'required|numeric|min:1',
                'total_discount' => 'required|numeric|min:0',
                'final_amount' => 'required|numeric|min:1',
                'advance' => 'required|numeric|min:0',
                'balance_amount' => 'required|numeric',
                'km_reading' => 'required|integer|min:0',
                'fual_level' => 'required|integer|min:1',
                'supervisor' => 'required|integer',
                'jobcard_item_id.*' => 'required|integer|min:1',
                'jobcard_quantity.*' => 'required|integer|min:1',
                'jobcard_price.*' => 'required|numeric|min:1',
                'jobcard_total_amount.*' => 'required|numeric|min:1',
                'jobcard_discount.*' => 'required|numeric|min:0',
                'jobcard_final_amount.*' => 'required|numeric|min:1',
                'employee.*' => 'required|integer|min:1',
                'label.*' => 'required|string|max:255',
                'charge.*' => 'required|numeric|min:1',
            ],
            [
                'customer_name.required' => 'The Customer Name field is required.',
                'customer_name.exists' => 'The selected Customer Name is invalid.',
                'vehicle_id.required' => 'The Vehicle field is required.',
                'vehicle_id.exists' => 'The selected Vehicle is invalid.',
            ]
        );


        $items = $request->jobcard_item_id;
        $qty = $request->jobcard_quantity;
        $price = $request->jobcard_price;
        $totalAmount = $request->jobcard_total_amount;
        $discount = $request->jobcard_discount;
        $finalAmount = $request->jobcard_final_amount;
        $machanic = $request->employee;
        $vehicleId = $request->vehicle_id;

        $customer = User::with([
            'vehicles' => function ($qry) use ($vehicleId) {
                $qry->where('id', $vehicleId);
            }
        ])->find($request->customer_name);

        $error = false;
        $decreaseStockId = [];

        if ($jobstatus == 1 && $request->status == 2 && !empty($items)) {
            foreach ($items as $itemKey => $itemValue) {
                $stock = ProductStock::where(['label_id' => $itemValue, 'price' => $price[$itemKey], 'user_id' => Auth::id()])->first();

                if($stock){
                    if($stock->stock < $qty[$itemKey]){
                        $error = true;
                    }else{
                        $decreaseStockId[] = [$stock->id,$qty[$itemKey]];
                    }
                }else{
                    $error = true;
                }
            }
        }

        if($error){
            return response()->json(['status' => 0, 'msg' => "Stock is not available for this order. <br> Add stock and try again."]); 
        }

        if (!empty($jobcard)) {
            $jobcard->customer_id = $request->customer_name;
            $jobcard->customer_name = strtoupper($customer->name . ' ' . $customer->lastname);
            $jobcard->vehicle_id = $vehicleId;
            $jobcard->vehical = $customer->vehicles->first()->modelname;
            $jobcard->vehical_number = $customer->vehicles->first()->number_plate;
            $jobcard->amount = $request->total_amount;
            $jobcard->discount = $request->total_discount;
            $jobcard->final_amount = $request->final_amount;
            $jobcard->advance = $request->advance;
            $jobcard->balance_amount = $request->balance_amount;
            $jobcard->km_runing = $request->km_reading;
            $jobcard->fual_level = $request->fual_level;
            $jobcard->supervisor_id = $request->supervisor;
            if ($request->status != 0) {
                $jobcard->status = $request->status;
            }
            if (!empty($request->step)) {
                $jobcard->step = $request->step;
            }
            $jobcard->sms_alert = $request->sms_alert;
            $jobcard->delivery_date = $request->delivery_date;
            $jobcard->delivery_time = $request->delivery_time;
            $jobcard->save();
            JobCardSparePart::where('jobcard_id', $jobcard->id)->delete();
            if (!empty($items)) {
                if (count($items) > 0) {
                    foreach ($items as $itemKey => $itemValue) {
                        $labelName = SparePartLabel::select('title')->find($itemValue);
                        $item = new JobCardSparePart;
                        $item->jobcard_id = $jobcard->id;
                        $item->stock_label_id = $itemValue;
                        $item->stock_label_name = $labelName->title;
                        $item->quantity = $qty[$itemKey];
                        $item->price = $price[$itemKey];
                        $item->total_amount = $totalAmount[$itemKey];
                        $item->discount = $discount[$itemKey];
                        $item->final_amount = $finalAmount[$itemKey];
                        $item->save();
                    }
                }
            }

            JobCardExtraCharge::where('jobcard_id', $jobcard->id)->delete();

            if (isset($request->label)) {
                foreach ($request->label as $key => $label) {
                    $extraCharge = new JobCardExtraCharge;
                    $extraCharge->jobcard_id = $jobcard->id;
                    $extraCharge->label = $label;
                    $extraCharge->charges = $request->charge[$key];
                    $extraCharge->machanic_id = $machanic[$key];
                    $extraCharge->save();
                }
            }
            if(count($decreaseStockId) > 0){
                foreach ($decreaseStockId as $keyValue) {
                    $productStock = ProductStock::find($keyValue[0]);
                    $productStock->stock = $productStock->stock - $keyValue[1];
                    $productStock->save();
                }
            }
            
            if ($request->step == 1 || ($jobstatus == 1 && $request->status == 2)) {
                $SendJobCardPdfMail = new SendJobCardPdfMail;
                $SendJobCardPdfMail->jobcard_id = $jobcard->id;
                $SendJobCardPdfMail->template_type = $request->step == 1 ? 0 : 1;
                $SendJobCardPdfMail->save();
            }
            return response()->json(['status' => 1]);
        }
        return response()->json(['status' => 0, 'msg' => "Details Not Found."]);
    }

    public function destory($id)
    {
        NewJobCard::find($id)->delete();

        return redirect()->route('newjobcard.list')->with('message', 'Jobcard Deleted Successfully');
    }

    public function destroyMultiple(Request $request)
    {
        $ids = $request->input('ids');

        NewJobCard::whereIn('id', $ids)->delete();

        return redirect()->route('newjobcard.list')->with('message', 'Jobcard Deleted Successfully');
    }


    public function addDentMark(Request $request): View
    {
        $title = 'Dent Mark';
        $jobcard_numbers = $request->input('jobcard_number');
        $jobCardsDentImage = JobCardsDentMark::with('Image')->where('jobcard_number', $jobcard_numbers)->first();
        return view('new_jobcard.dentmark', compact('title', 'jobcard_numbers', 'jobCardsDentImage'));
    }



    public function deleteDentMark(Request $request)
    {

        JobCardsDentMark::where('id', $request->dentimageId)->delete();
        Image::where('id', $request->imageId)->delete();

        return response()->json([
            'message' => 'Image Deleted successfully',
        ]);
    }


    public function saveMarkedImage(Request $request)
    {

        // Get the Base64 image data from the request
        $imageData = $request->input('image_data');

        // Remove the prefix (data:image/png;base64,)
        $imageData = str_replace('data:image/png;base64,', '', $imageData);
        $imageData = str_replace(' ', '+', $imageData);

        // Decode the Base64 string into binary data
        $image = base64_decode($imageData);

        // Generate a unique file name
        $fileName = 'marked_car_' . str::random(10) . '.png';


        // Define the path to store the image in the public folder
        $path = public_path('uploads/' . $fileName);

        // Store the image in the public/images folder
        file_put_contents($path, $image);


        // Save the image path to the database
        $carImage = new Image();
        $carImage->path = 'uploads/' . $fileName;
        $carImage->save();

        $jobCardsDentMark = new JobCardsDentMark();
        $jobCardsDentMark->jobcard_number = $request->jobcard_no;
        $jobCardsDentMark->file_id = $carImage->id;
        $jobCardsDentMark->save();


        // Return the saved data as JSON
        return response()->json([
            'message' => 'Image saved successfully',
            'image_path' => $carImage->image_path,
            'jobCardsDentMark' => $jobCardsDentMark->count(),
        ]);
    }


    public function customerVoice(Request $request): View
    {
        $jobcard_numbers = $request->input('jobcard_number');
        $title = 'Customer Voice';

        $jobCardsInspection = JobCardsInspection::where('jobcard_number', $jobcard_numbers)->where('is_customer_voice', 1)->get()->toArray();

        $jobCardsInspectionselect = JobCardsInspection::where('is_customer_voice', 1)->get();

        // echo "<pre>";print_r($jobCardsInspection);die;
        return view('new_jobcard.customer_voice', compact('title', 'jobcard_numbers', 'jobCardsInspection', 'jobCardsInspectionselect'));
    }


    public function addField(Request $request)
    {


        $newfield = '<div class="mb-3 col-lg-12 dynamic-field">
    <div class="row">
    <div class="col-11">
      <label for="validationCustom01" class="form-label">Customer Voice</label><br> 
                    <input type="text" class="form-control" name="customer_voice[]" placeholder="Customer Voice">
    </div>
     <div class="col-1 mt-4 ">
     <button type="button" class="btn btn-danger btn-sm remove-field rounded text-white border-white" style="margin-top: 5px;" fdprocessedid="dak07"><i class="fa fa-trash" aria-hidden="true"></i></button>
    </div>
    </div>
    </div>';

        return response()->json([
            'success' => 1,
            'newfield' => $newfield
        ]);
    }
    public function addCustomerView(Request $request)
    {
        // Sanitize input to prevent XSS attacks
        $value = $request->value;

        // Build the HTML for the new field
        $newfield = '
        <div class="mb-3 col-lg-12 dynamic-field">
            <div class="row">
                <div class="col-11">
                    <label for="validationCustom01" class="form-label">Customer Voice</label><br> 
                    <input type="text" class="form-control" name="customer_voice[]" value="' . $value . '" placeholder="Customer Voice">
                </div>
                <div class="col-1 mt-4">
                    <button type="button" class="btn btn-danger btn-sm remove-field rounded text-white border-white" style="margin-top: 5px;">
                        <i class="fa fa-trash" aria-hidden="true"></i>
                    </button>
                </div>
            </div>
        </div>';

        // Return the JSON response
        return response()->json([
            'success' => 1,
            'newfield' => $newfield,
        ]);
    }


    public function saveCustomerVoice(Request $request)
    {
        request()->validate([
            'customer_voice' => 'required',
        ]);
        JobCardsInspection::where('jobcard_number', $request->jobcard_numbers)->where('is_customer_voice', 1)->delete();
        foreach ($request->customer_voice as $customer_voice) {
            $data = JobCardsInspection::create([
                'jobcard_number' => $request->jobcard_numbers,
                'customer_voice' => $customer_voice,
                'is_customer_voice' => 1,
            ]);
        }

        $cardinspiration = JobCardsInspection::where('jobcard_number', $request->jobcard_numbers)->where('is_customer_voice', 1)->get()->count();


        return response()->json([
            'success' => 1,
            'message' => "Customer Voice Added Successfully.",
            'message' => "Customer Voice Added Successfully.",
            'cardinspiration' => $cardinspiration,
        ]);
    }



    public function workNotes(Request $request): View
    {
        $jobcard_numbers = $request->input('jobcard_number');
        $title = 'Work Notes';

        $jobCardsInspection = JobCardsInspection::where('jobcard_number', $jobcard_numbers)->where('is_customer_voice', 0)->get()->toArray();

        return view('new_jobcard.work_notes', compact('title', 'jobcard_numbers', 'jobCardsInspection'));
    }



    public function addFieldWorkNote(Request $request)
    {


        $newfield = '<div class="mb-3 col-lg-12 dynamic-field">
        <div class="row">
        <div class="col-11">
          <label for="validationCustom01" class="form-label">Work Note</label><br> 
                        <input type="text" class="form-control" name="work_notes[]" placeholder="Enter Work Note">
        </div>
         <div class="col-1 mt-4 ">
         <button type="button" class="btn btn-danger btn-sm remove-field rounded text-white border-white" style="margin-top: 5px;" fdprocessedid="dak07"><i class="fa fa-trash" aria-hidden="true"></i></button>
        </div>
        </div>
        </div>';

        return response()->json([
            'success' => 1,
            'newfield' => $newfield
        ]);
    }


    public function saveworkNotes(Request $request)
    {

        request()->validate([
            'work_notes' => 'required',
        ]);

        JobCardsInspection::where('jobcard_number', $request->jobcard_numbers)->where('is_customer_voice', 0)->delete();

        foreach ($request->work_notes as $work_note) {
            JobCardsInspection::create([
                'jobcard_number' => $request->jobcard_numbers,
                'customer_voice' => $work_note,
                'is_customer_voice' => 0,
            ]);
        }

        $countworknotes = JobCardsInspection::where('jobcard_number', $request->jobcard_numbers)->where('is_customer_voice', 0)->get()->count();

        return response()->json([
            'success' => 1,
            'message' => "Work Notes Added Successfully",
            'workNoteCount' => $countworknotes
        ]);
    }



    public function addphoto(Request $request): View
    {
        $jobcard_number = $request->input('jobcard_number');
        $title = 'Add Photo';
        $jobcard_numbers = $jobcard_number;

        $jobCardImage = JobCardImage::where('job_card_number', $jobcard_number)->first();


        $files = Image::whereIn('id', explode(',', @$jobCardImage->image_id))->get();
        $view = view('new_jobcard.outlet_imagesmultiple', ['files' => $files, 'route' => $this->route])->render();



        return view('new_jobcard.addPhoto', compact('title', 'jobcard_numbers', 'view', 'jobCardImage'));
    }

    public function imageUpload(Request $request)
    {
        if (!$request->has('type') || !$request->hasFile('gumasta_images')) {
            return response()->json(['status' => 0, 'msg' => 'File type not allowed']);
        }

        $type = $request->type;
        $filePath = '/uploads/gumasta/';
        $files = $request->file('gumasta_images');
        $movedFiles = [];
        $fileIds = [];

        $destinationPath = public_path($filePath);

        // Move files and save their paths
        foreach ($files as $file) {
            $fileName = time() . rand(1, 2000) . '.' . $file->getClientOriginalExtension();
            $file->move($destinationPath, $fileName);
            $movedFiles[] = $fileName;
        }

        // Store file data in the database and collect IDs
        foreach ($movedFiles as $fileName) {
            $fileData = Image::create(['path' => $fileName]);
            $fileIds[] = $fileData->id;
        }

        // Handle existing image IDs if `main_id` is provided
        if ($request->main_id) {
            $jobCardImage = JobCardImage::find($request->main_id);
            $existingIds = $jobCardImage ? explode(',', $jobCardImage->image_id) : [];
            $fileIds = array_unique(array_merge($fileIds, $existingIds));

            // Update the JobCardImage with the new list of image IDs
            $jobCardImage->update(['image_id' => implode(',', $fileIds)]);
        }

        // Fetch all images for rendering
        $files = Image::whereIn('id', $fileIds)->get();
        $view = view('new_jobcard.outlet_imagesmultiple', ['files' => $files, 'route' => $this->route])->render();

        return response()->json([
            'status' => 1,
            'message' => 'Image uploaded successfully',
            'file_id' => implode(',', $fileIds),
            'file_path' => $view,
        ]);
    }

    public function multiimagedelete(Request $request)
    {

        $id = $request->id;
        $ids = explode(',', $request->ids);
        $ids = array_values(array_diff($ids, array($id)));

        return response()->json([
            'success' => 1,
            'message' => "Photos Deleted Successfully",
            'ids' => $ids
        ]);


    }



    public function saveimageform(Request $request)
    {
        // echo "<pre>";print_r($request->all());die;
        if ($request->main_id) {
            $jobCard = JobCardImage::find($request->main_id);
            $jobCard->image_id = $request->images_id;
            $jobCard->save();
        } else {
            $data = JobCardImage::create([
                'job_card_number' => $request->jobcard_numbers,
                'image_id' => $request->images_id,
            ]);
        }

        return response()->json([
            'success' => 1,
            'message' => "Photos Added Successfully",
            'imageId' => count(explode(',', $request->images_id)),
        ]);
    }

    public function accessories(Request $request): View
    {
        $jobcard_numbers = $request->input('jobcard_number');
        $title = 'Accessories';

        $jobCardsInspection = JobCardsInspection::where('jobcard_number', $jobcard_numbers)->where('is_customer_voice', 2)->get()->toArray();

        return view('new_jobcard.accessories', compact('title', 'jobcard_numbers', 'jobCardsInspection'));
    }



    public function addFieldAccessories(Request $request)
    {


        $newfield = '<div class="mb-3 col-lg-12 dynamic-field">
        <div class="row">
        <div class="col-11">
          <label for="validationCustom01" class="form-label">Accessories</label><br> 
          <input type="text" class="form-control" name="accessories[]" placeholder="Enter Accessories">
        </div>
         <div class="col-1 mt-4 ">
         <button type="button" class="btn btn-danger btn-sm remove-field rounded text-white border-white" style="margin-top: 5px;" fdprocessedid="dak07"><i class="fa fa-trash" aria-hidden="true"></i></button>
        </div>
        </div>
        </div>';

        return response()->json([
            'success' => 1,
            'newfield' => $newfield
        ]);
    }



    public function saveAccessories(Request $request)
    {

        request()->validate([
            'accessories' => 'required',
        ]);

        JobCardsInspection::where('jobcard_number', $request->jobcard_numbers)->where('is_customer_voice', 2)->delete();

        foreach ($request->accessories as $accessories) {
            JobCardsInspection::create([
                'jobcard_number' => $request->jobcard_numbers,
                'customer_voice' => $accessories,
                'is_customer_voice' => 2,
            ]);
        }

        $accessaryCount = JobCardsInspection::where('jobcard_number', $request->jobcard_numbers)->where('is_customer_voice', 2)->count();


        return response()->json([
            'success' => 1,
            'message' => "Accessories Added Successfully",
            "accessaryCount" => $accessaryCount,
        ]);
    }

    public function getData(Request $request)
    {
        $query = $request->get('query');
        $page = $request->get('page', 1);
        $limit = 10; // Number of results per page
        // Fetch data based on the query
        $data = User::with('vehicles')->where('name', 'LIKE', '%' . $query . '%')
            ->orWhere('mobile_no', 'LIKE', '%' . $query . '%')
            ->orWhereHas('vehicles', function ($qry) use ($query) {
                $qry->where('number_plate', 'LIKE', '%' . $query . '%');
            })
            ->skip(($page - 1) * $limit)
            ->take($limit)
            ->get();
        // Format data for Select2
        $results = [];
        foreach ($data as $item) {
            $results[] = [
                'id' => $item->id, // The value of the option
                'text' => $item->name . ($item->mobile_no ? ' - ' . $item->mobile_no : '') . ($item->vehicles?->first()?->number_plate ? ' - ' . $item->vehicles->first()->number_plate : ''), // The text to display
            ];
        }
        // Check if there are more results for pagination
        $totalCount = User::where('name', 'LIKE', '%' . $query . '%')
            ->orWhere('mobile_no', 'LIKE', '%' . $query . '%')
            ->orWhereHas('vehicles', function ($qry) use ($query) {
                $qry->where('number_plate', 'LIKE', '%' . $query . '%');
            })->count();
        $more = ($page * $limit) < $totalCount;
        return response()->json([
            'results' => $results,
            'pagination' => ['more' => $more],
        ]);
    }

    public function getVehicle(Request $request)
    {
        $id = $request->customer_id;
        $vehicles = Vehicle::where('customer_id', $id)->get();
        // echo "<pre>";print_r($vehicles->toArray());die;
        if (count($vehicles) > 0) {
            $html = view('new_jobcard.component.vehicle-option', compact('vehicles'))->render();
            return response()->json(['status' => 1, 'html' => $html]);
        }
        return response()->json(['status' => 0, 'msg' => "Vehicle Not Found. Please Add Vehicle."]);
    }

    public function addCustomer(Request $request)
    {
        return response()->json(['status' => 0, 'msg' => "Vehicle Not Found. Please Add Vehicle."]);
    }


    public function viewInvoice(Request $request): View
    {


        $newjobcard = NewJobCard::where('id', $request->id)->first();
        $customers = User::where('id', $newjobcard->customer_id)->first();
        $vehicles = Vehicle::where('id', $newjobcard->vehicle_id)->first();
        $jobCardSpareParts = JobCardSparePart::where('jobcard_id', $request->id)->get();
        $jobCardExtraCharges = JobCardExtraCharge::where('jobcard_id', $request->id)->get();


        // echo "<pre>";print_r($jobCardSpareParts[0]->User->display_name);die;

        $title = 'View Invoice';
        $logo = DB::table('tbl_settings')->first();
        return view('new_jobcard.invoice', compact('title', 'logo', 'customers', 'newjobcard', 'vehicles', 'jobCardSpareParts', 'jobCardExtraCharges'));
    }

    public function viewPDF(Request $request)
    {


        $newjobcard = NewJobCard::where('id', $request->id)->first();
        $customers = User::where('id', $newjobcard->customer_id)->first();
        $vehicles = Vehicle::where('id', $newjobcard->vehicle_id)->first();
        $jobCardSpareParts = JobCardSparePart::where('jobcard_id', $request->id)->get();


        $title = 'View Invoice';
        $logo = DB::table('tbl_settings')->first();
        return view('new_jobcard.invoice', compact('title', 'logo', 'customers', 'newjobcard', 'vehicles', 'jobCardSpareParts'));
    }


    // public function downloadInvoice(Request $request)
    // {
    //     $newjobcard = NewJobCard::where('id', $request->id)->first();
    //     $customers = User::where('id', $newjobcard->customer_id)->first();
    //     $vehicles = Vehicle::where('id', $newjobcard->vehicle_id)->first();
    //     $jobCardSpareParts = JobCardSparePart::where('jobcard_id', $request->id)->get();

    //     $title = 'View Invoice';
    //     $logo = DB::table('tbl_settings')->first();

    //     // Prepare data for the PDF
    //     $data = compact('title', 'logo', 'customers', 'newjobcard', 'vehicles', 'jobCardSpareParts');

    //     // Load the view and convert to PDF
    //      $pdf = PDF::loadView('new_jobcard.pdf', $data)->setPaper([0, 0, 595.28, 1000]);;
    //     // Download the generated PDF
    //     return $pdf->download('invoice_' . $newjobcard->id . '.pdf');

    // }

    public function downloadInvoice(Request $request)
    {

        $newjobcard = NewJobCard::with(['exitNote' => function ($query) {
            $query->select('note', 'new_job_card_id');
        }])->where('id', $request->id)->first();
        $customer = User::select('name','lastname','mobile_no','email','address')->where('id', $newjobcard->customer_id)->first();
        $vehicles = Vehicle::select('number_plate','chassisno','modelname','vehiclebrand_id')->where('id', $newjobcard->vehicle_id)->first();
        $jobCardSpareParts = JobCardSparePart::where('jobcard_id', $request->id)->get();
        $jobCardExtraCharges = JobCardExtraCharge::where('jobcard_id', $request->id)->get();

        $inspections = JobCardsInspection::select('customer_voice','is_customer_voice')->where('jobcard_number', $newjobcard->jobcard_number)->whereIn('is_customer_voice', [1,0])->get();
        $jobCardCustomerVoice = $inspections->where('is_customer_voice', 1);
        $jobCardAccessories = $inspections->where('is_customer_voice', 0);

        // echo "<pre>";print_r($exitNote?->toArray());die;
        $title = 'View Invoice';
        $logo = DB::table('tbl_settings')->first();

        $data = compact('title', 'logo', 'customer', 'newjobcard', 'vehicles', 'jobCardSpareParts', 'jobCardExtraCharges','jobCardCustomerVoice','jobCardAccessories');

        $pdf = PDF::loadView('new_jobcard.pdf', $data)
        ->setPaper('a4', 'portrait');

        if ($request->type) {
            return $pdf->stream('invoice_' . $newjobcard->jobcard_number . '.pdf');
        }
        return $pdf->download('invoice_' . $newjobcard->jobcard_number . '.pdf');

    }


    public function addextrafields(Request $request)
    {

        $price = $request->price ?? 0;
        $title = $request->title ?? '';

        $employee = User::where(['role' => 'employee', 'soft_delete' => 0])->get();

        $html = view('new_jobcard.component.add-other-charges', compact('employee','price','title'))->render();

        return response()->json([
            'success' => 1,
            'newfield' => $html
        ]);
    }


    public function downloadMechanicSheet(Request $request)
    {

        $newjobcard = NewJobCard::where('id', $request->id)->first();
        $customers = User::where('id', $newjobcard->customer_id)->first();
        $vehicles = Vehicle::where('id', $newjobcard->vehicle_id)->first();
        $jobCardSpareParts = JobCardSparePart::where('jobcard_id', $request->id)->get();

        $jobCardExtraCharges = JobCardExtraCharge::where('jobcard_id', $request->id)->get();
        $jobCardCustomerVoice = JobCardsInspection::where('jobcard_number', $newjobcard->jobcard_number)->where('is_customer_voice', 1)->get();
        $jobCardAccessories = JobCardsInspection::where('jobcard_number', $newjobcard->jobcard_number)->where('is_customer_voice', 0)->get();

        $title = 'View Invoice';
        $logo = DB::table('tbl_settings')->first();

        $data = compact('title', 'logo', 'customers', 'newjobcard', 'vehicles', 'jobCardSpareParts', 'jobCardExtraCharges', 'jobCardCustomerVoice', 'jobCardAccessories');

        $pdf = PDF::loadView('new_jobcard.pdf.mechanic_sheet', $data)
            ->setPaper('a4', 'portrait');

        if ($request->type) {
            return $pdf->stream('machanicSheet_' . $newjobcard->jobcard_number . '.pdf');
        }
        return $pdf->download('machanicSheet_' . $newjobcard->jobcard_number . '.pdf');
    }

    public function downloadEstimateInvoice(Request $request)
    {
        $newjobcard = NewJobCard::where('id', $request->id)->first();
        $customer = User::where('id', $newjobcard->customer_id)->first();
        $vehicles = Vehicle::where('id', $newjobcard->vehicle_id)->first();
        $jobCardSpareParts = JobCardSparePart::where('jobcard_id', $request->id)->get();

        $jobCardExtraCharges = JobCardExtraCharge::where('jobcard_id', $request->id)->get();
        $jobCardCustomerVoice = JobCardsInspection::where('jobcard_number', $newjobcard->jobcard_number)->where('is_customer_voice', 1)->get();
        $jobCardAccessories = JobCardsInspection::where('jobcard_number', $newjobcard->jobcard_number)->where('is_customer_voice', 2)->get();

        // echo "<pre>";print_r($jobCardSpareParts->toArray());die;
        $title = 'View Invoice';
        $logo = DB::table('tbl_settings')->first();

        $data = compact('title', 'logo', 'customer', 'newjobcard', 'vehicles', 'jobCardSpareParts', 'jobCardExtraCharges', 'jobCardCustomerVoice', 'jobCardAccessories');

        $pdf = PDF::loadView('new_jobcard.pdf.estimate-invoice', $data)
            ->setPaper('a4', 'portrait');

        if ($request->type) {
            return $pdf->stream('estimate_invoice_' . $newjobcard->jobcard_number . '.pdf');
        }

        return $pdf->download('estimate_invoice_' . $newjobcard->jobcard_number . '.pdf');
    }
    public function downloadOpenInvoice(Request $request)
    {

        $newjobcard = NewJobCard::where('id', $request->id)->first();
        $customer = User::where('id', $newjobcard->customer_id)->first();
        $vehicles = Vehicle::where('id', $newjobcard->vehicle_id)->first();
        $jobCardSpareParts = JobCardSparePart::where('jobcard_id', $request->id)->get();

        $jobCardExtraCharges = JobCardExtraCharge::where('jobcard_id', $request->id)->get();
        $jobCardCustomerVoice = JobCardsInspection::where('jobcard_number', $newjobcard->jobcard_number)->where('is_customer_voice', 1)->get();
        $jobCardAccessories = JobCardsInspection::where('jobcard_number', $newjobcard->jobcard_number)->where('is_customer_voice', 2)->get();

        // echo "<pre>";print_r($jobCardSpareParts->toArray());die;
        $title = 'View Invoice';
        $logo = DB::table('tbl_settings')->first();

        $data = compact('title', 'logo', 'customer', 'newjobcard', 'vehicles', 'jobCardSpareParts', 'jobCardExtraCharges', 'jobCardCustomerVoice', 'jobCardAccessories');

        $pdf = PDF::loadView('new_jobcard.pdf.invoice', $data)
            ->setPaper('a4', 'portrait');

        return $pdf->download('invoice' . $newjobcard->jobcard_number . '.pdf');
    }

    public function sendInvoiceMail($id)
    {
        
        $newjobcard = NewJobCard::with(['exitNote' => function ($query) {
            $query->select('note', 'new_job_card_id');
        }])->where('id', $id)->first();

        if (!empty($newjobcard)) {
            if($newjobcard->status == 2 || $newjobcard->status == 3){

                $customer = User::select('id','name','lastname','mobile_no','email','address')->where('id', $newjobcard->customer_id)->first();
                $vehicles = Vehicle::select('number_plate','chassisno','modelname','vehiclebrand_id')->where('id', $newjobcard->vehicle_id)->first();
                $jobCardSpareParts = JobCardSparePart::where('jobcard_id', $id)->get();
                $jobCardExtraCharges = JobCardExtraCharge::where('jobcard_id', $id)->get();
        
                $inspections = JobCardsInspection::select('customer_voice','is_customer_voice')->where('jobcard_number', $newjobcard->jobcard_number)->whereIn('is_customer_voice', [1,0])->get();
                $jobCardCustomerVoice = $inspections->where('is_customer_voice', 1);
                $jobCardAccessories = $inspections->where('is_customer_voice', 0);
        
                $exitNote = $newjobcard->exitNote; 
        
                $title = 'View Invoice';
                $logo = DB::table('tbl_settings')->first();
        
                $data = compact('title', 'logo', 'customer', 'newjobcard', 'vehicles', 'jobCardSpareParts', 'jobCardExtraCharges','jobCardCustomerVoice','jobCardAccessories','exitNote');
        
                Mail::to($customer->email)->send(new InvoiceMail($data));
    
                return response()->json(['status' => 1, 'msg' => "The invoice has been sent to the customer's mail successfully!"]);
            }else{

                $customer = User::where('id', $newjobcard->customer_id)->first();
                $vehicles = Vehicle::where('id', $newjobcard->vehicle_id)->first();
                $jobCardSpareParts = JobCardSparePart::where('jobcard_id', $id)->get();

                $jobCardExtraCharges = JobCardExtraCharge::where('jobcard_id', $id)->get();
                $jobCardCustomerVoice = JobCardsInspection::where('jobcard_number', $newjobcard->jobcard_number)->where('is_customer_voice', 1)->get();
                $jobCardAccessories = JobCardsInspection::where('jobcard_number', $newjobcard->jobcard_number)->where('is_customer_voice', 2)->get();
                
                $title = 'View Invoice';
                $logo = DB::table('tbl_settings')->first();
                
                $data = compact('title', 'logo', 'customer', 'newjobcard', 'vehicles', 'jobCardSpareParts', 'jobCardExtraCharges', 'jobCardCustomerVoice', 'jobCardAccessories');
                
                Mail::to($customer->email)->send(new EstimateMail($data));
                
                return response()->json(['status' => 1, 'msg' => "The estimate has been sent to the customer's mail successfully!"]);
            }
        }
        return response()->json(['status' => 0, 'msg' => "Something wrong found! Please try again."]);

    }

    public function estimateConfirmReject(Request $request)
    {

        $status = $request->status;
        $id = base64_decode($request->id);
        $userId = base64_decode(base64_decode($request->userId));

        $newjobcard = NewJobCard::where(['id' => $id, 'status' => 0, 'customer_id' => $userId])->first();

        // echo "<pre>";print_r($newjobcard);die;
        if ($newjobcard) {
            $user_id = $newjobcard->customer_id;

            Auth::loginUsingId($user_id);

            $customer = User::find($newjobcard->customer_id);
            $vehicles = Vehicle::find($newjobcard->vehicle_id);
            $jobCardSpareParts = JobCardSparePart::where('jobcard_id', $id)->get();

            $jobCardExtraCharges = JobCardExtraCharge::where('jobcard_id', $id)->get();
            $jobCardCustomerVoice = JobCardsInspection::where('jobcard_number', $newjobcard->jobcard_number)->where('is_customer_voice', 1)->get();
            $jobCardAccessories = JobCardsInspection::where('jobcard_number', $newjobcard->jobcard_number)->where('is_customer_voice', 2)->get();

            $title = 'View Invoice';
            $logo = Setting::first();
            // echo "<pre>";print_r($jobCardSpareParts->toArray());die;

            $data = compact('title', 'logo', 'customer', 'newjobcard', 'vehicles', 'jobCardSpareParts', 'jobCardExtraCharges', 'jobCardCustomerVoice', 'jobCardAccessories', 'status');


            return view('new_jobcard.estimate.estimate-confirm-reject', $data);
        }

        return view('new_jobcard.estimate.thankyou');
    }

    public function changeStatus(Request $request)
    {

        $id = $request->id;
        $status = $request->status == 1 ? 1 : 0;
        $newjobcard = NewJobCard::where(['id' => $id, 'status' => 0, 'customer_id' => $request->user()->id])->first();
        if ($newjobcard) {
            $newjobcard->status = $status;
            $newjobcard->save();
            return response()->json(['status' => 1]);
        }

        return response()->json(['status' => 0, 'msg' => "Something went wrong. Please contact the administrator."]);
    }
}
