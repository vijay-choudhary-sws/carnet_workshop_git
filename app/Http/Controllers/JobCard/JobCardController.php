<?php

namespace App\Http\Controllers\JobCard;

use App\Http\Controllers\Controller;
use App\Image;
use App\JobCardCustomer;
use App\JobCardsCustomer;
use App\JobCardsDentMark;
use App\JobCardSparePart;
use App\NewJobCard;
use App\User;
use App\Vehicle;
use Carbon\Carbon;
use Illuminate\Http\Request;
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
        $jobCardsDentMark = JobCardsDentMark::where('jobcard_number', '10022111')->first();

        return view('new_jobcard.add', compact('country', 'jobCardsDentMark'));
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

        JobCardSparePart::where('jobcard_id',  $jobcard->id)->delete();

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


    public function addDentMark(): IlluminateViewView
    {
        $data['title'] = 'Dent Mark';
        return view('new_jobcard.dentmark', $data);
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
        $jobCardsDentMark->jobcard_number = '10022111';
        $jobCardsDentMark->file_id = $carImage->id;
        $jobCardsDentMark->save();


        // Return the saved data as JSON
        return response()->json([
            'message' => 'Image saved successfully',
            'image_path' => $carImage->image_path
        ]);
    }



    public function customerVoice(): IlluminateViewView
    {
        $data['title'] = 'Customer Voice';
        return view('new_jobcard.customer_voice', $data);
    }




    public function saveCustomerVoice(Request $request)
    {

        request()->validate([
            'customer_voice' => 'required',
        ]);
        $data = JobCardsCustomer::create([
            'jobcard_number' => '10022111',
            'customer_voice' => $request->customer_voice,
            'is_customer_voice' => 1,
        ]);
        return response()->json([
            'success' => 1,
            'message' => "Customer Voice Added Successfully."
        ]);
    }



    public function workNotes(): IlluminateViewView
    {
        $data['title'] = 'Work notes';
        return view('new_jobcard.work_notes', $data);
    }




    public function saveworkNotes(Request $request)
    {

        request()->validate([
            'work_notes' => 'required',
        ]);
        $data = JobCardsCustomer::create([
            'jobcard_number' => '10022111',
            'customer_voice' => $request->work_notes,
            'is_customer_voice' => 0,
        ]);
        return response()->json([
            'success' => 1,
            'message' => "Work Notes Added Successfully."
        ]);
    }



    public function addphoto(): IlluminateViewView
    {
        $data['title'] = 'Add Photo';
        return view('new_jobcard.addPhoto', $data);
    }


    // public function imageupload(Request $request)
    // {
    //     $type = $request->type;
    //     $path = $type . '_path';
    //     $name = $type . '_name';
    //     $file_path = $request->$path;

    //     // Check if files are uploaded
    //     if ($request->hasFile('outlet_images')) {
    //         $uploaded_files = $request->file('outlet_images');
    //         $uploaded_data = [];

    //         foreach ($uploaded_files as $file) {
    //             // Get file extension and generate a unique file name
    //             $ext = $file->getClientOriginalExtension();
    //             $destinationPath = public_path() . '/' . $file_path;
    //             $file_name = time() . "_" . uniqid() . "." . $ext;

    //             // Move file to the destination path
    //             $file->move($destinationPath, $file_name);

    //             // Save file information in the database
    //             $file_data = Image::create([
    //                 'path' => $file_name,
    //             ]);

    //             // Add uploaded file data to response
    //             $uploaded_data[] = [
    //                 'file_id' => $file_data->id,
    //                 'file_path' => asset($file_path . $file_data->file),
    //             ];
    //         }

    //         return response()->json(['status' => 1, 'uploaded_files' => $uploaded_data]);
    //     } else {
    //         return response()->json(['status' => 0, 'msg' => 'No files uploaded or file type not allowed']);
    //     }
    // }



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
            return response()->json(['status' => 1, 'file_id' => $file_ids, 'file_path' => $view]);
        } else {

            return response()->json(['status' => 0, 'msg' => 'File type not allowed']);
        }
    }



    //   public function imageupload(Request $request)
//   {
//       $type = $request->type;
//       $path = $type . '_path';
//       $name = $type . '_name';
//       $file_name = $request->$name;
//       $file_path = '/uploads/outlet_images/';
//       $file = $request->file('outlet_images');
//       $movedFile=$file_data=$file_ids=$files=array();
//       if (!empty($file)) {

    //               $destinationPath = public_path().'/'.$file_path;
//               foreach($file as $value)
//               {
//                   $ext = $value->getClientOriginalExtension();
//                   $file_name = time().rand(1,2000).".".$value->getClientOriginalExtension();
//                   $value->move($destinationPath,$file_name);
//                   $movedFile[] =  $file_name;  
//               }
//               $view = array();
//               foreach($movedFile as $values)
//               {
//                   $file_data= Image::create([
//                       'path'=>$values,
//                   ]);

    //                   // echo "<pre>";print_r($file_data);die;

    //                   $file_ids[]= $file_data->id;
//               //    $view = url('/uploads/outlet_images/'.$values); 
//               }


    //               $oldids= implode(',',$file_ids);

    //                  $oldIdarr = explode(',',$oldids);
//                   $allids = array_filter(array_merge($file_ids,$oldIdarr));
//                   $files = Image::whereIn('id', $oldIdarr)->get();
//                   $view  =  view('new_jobcard.outlet_imagesmultiple',['files'=>$files,'route' => $this->route])->render();

    //                 //   echo "<pre>";print_r($files);die;
//                return response()->json(['status' => 1, 'file_id' => $file_ids, 'file_path' => $view ]);
//       }else{ 

    //           return response()->json(['status' => 0, 'msg' => 'File type not allowed']);
//       }
//   }

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

        if (count($vehicles) > 0) {
            $html = view('new_jobcard.component.vehicle-option', compact('vehicles'))->render();
            return response()->json(['status' => 1, 'html' => $html]);
        }

        return response()->json(['status' => 0, 'msg' => "Vehicle Not Found. Please Add Vehicle."]);
    }

}


