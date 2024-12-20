<?php

namespace App\Http\Controllers\JobCard;

use App\Http\Controllers\Controller;
use App\Image;
use App\JobCardCustomer;
use App\JobCardsCustomer;
use App\JobCardsDentMark;
use App\JobCardSparePart;
use App\JobCardImage;
use App\JobCardsInspection;
use App\NewJobCard;
use App\User;
use App\Vehicle;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\View\View as IlluminateViewView;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class JobCardController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth');
    }
    public function index()
    {
        $jobcards = NewJobCard::all();
        return view('new_jobcard.list', compact('jobcards'));
    }
    public function add()
    {
        $country = DB::table('tbl_countries')->get()->toArray();
        $jobCardsDentMark = JobCardsDentMark::where('jobcard_number', 'JCN-BB4659B837')->first();
        $jobCardscustomervoice = JobCardsInspection::where('jobcard_number', 'JCN-BB4659B837')->where('is_customer_voice', 1)->get();
        $jobCardsworknote = JobCardsInspection::where('jobcard_number', 'JCN-BB4659B837')->where('is_customer_voice', 0)->get();
        $jobCardsaccessary = JobCardsInspection::where('jobcard_number', 'JCN-BB4659B837')->where('is_customer_voice', 2)->get();
        $jobCardsImage = JobCardImage::where('job_card_number', 'JCN-BB4659B837')->get();

        return view('new_jobcard.add', compact('customers', 'country', 'jobCardsDentMark', 'jobCardscustomervoice', 'jobCardsworknote', 'jobCardsaccessary', 'jobCardsImage'));
    }

    public function store(Request $request)
    {

        // echo "<pre>";print_r($request->all());die;
        $this->validate(
            $request,
            [
                'jobcard_number' => 'required|string|max:255',
                'customer_name' => 'required|exists:users,id',
                'vehicle_id' => 'required|exists:tbl_vehicles,id',
                'total_amount' => 'required|numeric|min:1',
                'total_discount' => 'required|numeric|min:0',
                'final_amount' => 'required|numeric|min:1',
                'advance' => 'required|numeric|min:0',
                'balance_amount' => 'required|numeric|min:1',
                'km_reading' => 'required|integer|min:1',
                'fual_level' => 'required|integer|min:1',
                'supervisor' => 'required|integer|min:1',
                'jobcard_item_id.*' => 'required|integer|min:1',
                'jobcard_quantity.*' => 'required|integer|min:1',
                'jobcard_price.*' => 'required|numeric|min:1',
                'jobcard_total_amount.*' => 'required|numeric|min:1',
                'jobcard_discount.*' => 'required|numeric|min:0',
                'jobcard_final_amount.*' => 'required|numeric|min:1',
                'employee.*' => 'required|integer|min:1',
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

        // echo count($items);die;
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
        // $jobcard->entry_date = Carbon::now();
        $jobcard->amount = $request->total_amount;
        $jobcard->discount = $request->total_discount;
        $jobcard->final_amount = $request->final_amount;
        $jobcard->advance = $request->advance;
        $jobcard->balance_amount = $request->balance_amount;
        $jobcard->km_runing = $request->km_reading;
        $jobcard->fual_level = $request->fual_level;
        $jobcard->supervisor_id = $request->supervisor;
        $jobcard->save();

        if (count($items) > 0) {
            foreach ($items as $itemKey => $itemValue) {
                $item = new JobCardSparePart;
                $item->jobcard_id = $jobcard->id;
                $item->stock_label_id = $itemValue;
                $item->stock_label_name = "test";
                $item->quantity = $qty[$itemKey];
                $item->price = $price[$itemKey];
                $item->total_amount = $totalAmount[$itemKey];
                $item->discount = $discount[$itemKey];
                $item->final_amount = $finalAmount[$itemKey];
                $item->machanic_id = $machanic[$itemKey];
                $item->save();
            }
        }
        return response()->json(['status' => 1]);
    }

    public function edit($id)
    {
        $jobcard = NewJobCard::with('jobCardSpareParts')->find($id);
        $employee = User::where(['role' => 'employee', 'soft_delete' => 0])->get();
        $country = DB::table('tbl_countries')->get()->toArray();
        $jobCardsDentMark = JobCardsDentMark::where('jobcard_number', '10022111')->first();

        return view('new_jobcard.edit', compact('jobcard', 'employee', 'country', 'jobCardsDentMark'));
    }

    public function update(Request $request)
    {
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
                'balance_amount' => 'required|numeric|min:1',
                'km_reading' => 'required|integer|min:1',
                'fual_level' => 'required|integer|min:1',
                'supervisor' => 'required|integer|min:1',
                'jobcard_item_id.*' => 'required|integer|min:1',
                'jobcard_quantity.*' => 'required|integer|min:1',
                'jobcard_price.*' => 'required|numeric|min:1',
                'jobcard_total_amount.*' => 'required|numeric|min:1',
                'jobcard_discount.*' => 'required|numeric|min:0',
                'jobcard_final_amount.*' => 'required|numeric|min:1',
                'employee.*' => 'required|integer|min:1',
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

        // echo count($items);die;
        $customer = User::with([
            'vehicles' => function ($qry) use ($vehicleId) {
                $qry->where('id', $vehicleId);
            }
        ])->find($request->customer_name);

        $jobcard = NewJobCard::find($request->id);
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
        $jobcard->save();

        JobCardSparePart::where('jobcard_id', $jobcard->id)->delete();

        if (count($items) > 0) {
            foreach ($items as $itemKey => $itemValue) {
                $item = new JobCardSparePart;
                $item->jobcard_id = $jobcard->id;
                $item->stock_label_id = $itemValue;
                $item->stock_label_name = "test";
                $item->quantity = $qty[$itemKey];
                $item->price = $price[$itemKey];
                $item->total_amount = $totalAmount[$itemKey];
                $item->discount = $discount[$itemKey];
                $item->final_amount = $finalAmount[$itemKey];
                $item->machanic_id = $machanic[$itemKey];
                $item->save();
            }
        }

        return response()->json(['status' => 1]);
    }

    public function destory($id)
    {
        NewJobCard::find($id)->delete();

        return redirect()->route('newjobcard.list')->with('message', 'Accessory Deleted Successfully');
    }

    public function destroyMultiple(Request $request)
    {
        $ids = $request->input('ids');

        NewJobCard::whereIn('id', $ids)->delete();

        return redirect()->route('newjobcard.list')->with('message', 'Accessory Deleted Successfully');
    }



    public function addDentMark(Request $request): View
    {
        $title = 'Dent Mark';
        $jobcard_numbers = $request->input('jobcard_number');
        $jobCardsDentImage = JobCardsDentMark::with('Image')->where('jobcard_number', 'JCN-BB4659B837')->first();
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

        // Store the image in the storage folder
        $path = Storage::put('public/images/' . $fileName, $image);

        // Save the image path to the database
        $carImage = new Image();
        $carImage->path = 'storage/images/' . $fileName;
        $carImage->save();

        $jobCardsDentMark = new JobCardsDentMark();
        $jobCardsDentMark->jobcard_number = 'JCN-BB4659B837';
        $jobCardsDentMark->file_id = $carImage->id;
        $jobCardsDentMark->save();


        // Return the saved data as JSON
        return response()->json([
            'message' => 'Image saved successfully',
            'image_path' => $carImage->image_path
        ]);
    }


    public function customerVoice(Request $request): View
    {
        $jobcard_numbers = $request->input('jobcard_number');
        $title = 'Customer Voice';

        $jobCardsInspection = JobCardsInspection::where('jobcard_number', 'JCN-BB4659B837')->where('is_customer_voice', 1)->get()->toArray();


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
        JobCardsInspection::where('jobcard_number', 'JCN-BB4659B837')->where('is_customer_voice', 1)->delete();
        foreach ($request->customer_voice as $customer_voice) {
            $data = JobCardsInspection::create([
                // 'jobcard_number' => $request->jobcard_numbers,    
                'jobcard_number' => 'JCN-BB4659B837',
                'customer_voice' => $customer_voice,
                'is_customer_voice' => 1,
            ]);
        }
        return response()->json([
            'success' => 1,
            'message' => "Customer Voice Added Successfully."
        ]);
    }



    public function workNotes(Request $request): View
    {
        $jobcard_numbers = $request->input('jobcard_number');
        $title = 'Work Notes';

        $jobCardsInspection = JobCardsInspection::where('jobcard_number', 'JCN-BB4659B837')->where('is_customer_voice', 0)->get()->toArray();

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

        $request()->validate([
            'work_notes' => 'required',
        ]);

        JobCardsInspection::where('jobcard_number', 'JCN-BB4659B837')->where('is_customer_voice', 0)->delete();

        foreach ($request->work_notes as $work_note) {
            JobCardsInspection::create([
                // 'jobcard_number' => $request->jobcard_numbers,    
                'jobcard_number' => 'JCN-BB4659B837',
                'customer_voice' => $work_note,
                'is_customer_voice' => 0,
            ]);
        }

        $countworknotes = JobCardsInspection::where('jobcard_number', 'JCN-BB4659B837')->where('is_customer_voice', 0)->get()->count();

        return response()->json([
            'success' => 1,
            'message' => "Work Notes Added Successfully",
            'countworknotes' => $countworknotes
        ]);
    }


    public function addphoto(Request $request): View
    {
        $jobcard_number = $request->input('jobcard_number');
        $title = 'Add Photo';
        $jobcard_numbers = $jobcard_number;


        return view('new_jobcard.addPhoto', compact('title', 'jobcard_numbers'));
    }

    public function imageupload(Request $request)
    {
        $type = $request->type;
        $path = $type . '_path';
        $name = $type . '_name';
        $file_name = $request->$name;
        $file_path = '/uploads/gumasta/';
        $file = $request->file('gumasta_images');
        $movedFile = $file_data = $file_ids = $files = array();
        if (!empty($file)) {

            $destinationPath = public_path() . '/' . $file_path;
            foreach ($file as $value) {
                $ext = $value->getClientOriginalExtension();
                $file_name = time() . rand(1, 2000) . "." . $value->getClientOriginalExtension();
                $value->move($destinationPath, $file_name);
                $movedFile[] = $file_name;
            }
            $view = array();
            foreach ($movedFile as $values) {
                $file_data = Image::create([
                    'path' => $values,
                ]);

                // echo "<pre>";print_r($file_data);die;

                $file_ids[] = $file_data->id;
                //    $view = url('/uploads/gumasta/'.$values); 
            }


            $oldids = implode(',', $file_ids);

            $oldIdarr = explode(',', $oldids);
            $allids = array_filter(array_merge($file_ids, $oldIdarr));
            $files = Image::whereIn('id', $allids)->get();
            $view = view('new_jobcard.outlet_imagesmultiple', ['files' => $files, 'route' => $this->route])->render();
            return response()->json(['status' => 1, 'message' => 'Image uploded Successfully', 'file_id' => implode(',', $file_ids), 'file_path' => $view]);
        } else {

            return response()->json(['status' => 0, 'msg' => 'File type not allowed']);
        }
    }


    public function saveimageform(Request $request)
    {
        $data = JobCardImage::create([
            'job_card_number' => 'JCN-BB4659B837',
            'image_id' => $request->images_id,
        ]);
        return response()->json([
            'success' => 1,
            'message' => "Photos Added Successfully"
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

    public function accessories(Request $request): View
    {
        $jobcard_numbers = $request->input('jobcard_number');
        $title = 'Accessories';

        $jobCardsInspection = JobCardsInspection::where('jobcard_number', 'JCN-BB4659B837')->where('is_customer_voice', 2)->get()->toArray();

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

        JobCardsInspection::where('jobcard_number', 'JCN-BB4659B837')->where('is_customer_voice', 2)->delete();

        foreach ($request->accessories as $accessories) {
            JobCardsInspection::create([
                // 'jobcard_number' => $request->jobcard_numbers,    
                'jobcard_number' => 'JCN-BB4659B837',
                'customer_voice' => $accessories,
                'is_customer_voice' => 2,
            ]);
        }


        return response()->json([
            'success' => 1,
            'message' => "Accessories Added Successfully"
        ]);
    }


    public function getVehicle(Request $request)
    {

        $id = $request->customer_id;
        $vehicles = Vehicle::where('customer_id', $id)->get();

        if (count($vehicles) > 0) {
            $html = view('new_jobcard.component.vehicle-option', compact('vehicles'))->render();
            return response()->json(['status' => 1, 'html' => $html]);
        }

        return response()->json(['status' => 0, 'msg' => "Vehicle Not Found. Please Add Vehicle."]);
    }

}


