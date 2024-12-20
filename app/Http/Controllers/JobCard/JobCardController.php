<?php

namespace App\Http\Controllers\JobCard;

use App\Http\Controllers\Controller;
use App\Image;
use App\JobCardCustomer;
use App\JobCardsCustomer;
use App\JobCardsDentMark;
use App\JobCardImage;
use App\JobCardsInspection;
use App\NewJobCard;
use App\User;  
use Illuminate\Http\Request;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\View\View as IlluminateViewView;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class JobCardController extends Controller
{
    protected $route;

    protected $single_heading;
    
    protected $notificationService;

    public function __construct()
    {
        $this->route = new \stdClass;
        $this->middleware('auth');
    }
    public function index()
    { 
        $jobcards = NewJobCard::all();
        return view('new_jobcard.list', compact('jobcards'));
    }
    public function add()
    { 
        $customers = User::where([['role', 'Customer'], ['soft_delete', 0]])->get();
        $country = DB::table('tbl_countries')->get()->toArray();
        $jobCardsDentMark = JobCardsDentMark::where('jobcard_number','JCN-BB4659B837')->first();
        $jobCardscustomervoice = JobCardsInspection::where('jobcard_number','JCN-BB4659B837')->where('is_customer_voice',1)->get();
        $jobCardsworknote = JobCardsInspection::where('jobcard_number','JCN-BB4659B837')->where('is_customer_voice',0)->get();
        $jobCardsaccessary = JobCardsInspection::where('jobcard_number','JCN-BB4659B837')->where('is_customer_voice',2)->get();
        $jobCardsImage = JobCardImage::where('job_card_number','JCN-BB4659B837')->get();



        return view('new_jobcard.add',compact('customers','country','jobCardsDentMark','jobCardscustomervoice','jobCardsworknote','jobCardsaccessary','jobCardsImage'));
    }
    public function store(Request $request)
    {
 
        $this->validate($request, [
            'jobcard_number' => 'required|string|max:255',
            'amount' => 'required|numeric|min:0',
            'discount' => 'required|integer|min:0',
            'final_amount' => 'required|numeric|min:0',
            'advance' => 'required|numeric|min:0',
            'balance_amount' => 'required|numeric|min:0',
            'km_reading' => 'required|integer|min:0',
            'fual_level' => 'required|integer|min:0',
            'supervisor' => 'required|integer|min:0',
        ]);

        return response()->json(['status' => 1]);
    }

    public function edit($id)
    {
        $accessory = NewJobCard::find($id);
        $units = Unit::all();
        $sparePartLabels = SparePartLabel::select('id', 'title')->where('spare_part_type', 1)->get();

        return view('saas.accessory.edit', compact('accessory', 'units', 'sparePartLabels'));
    }

    public function update(AccessoryRequest $request)
    {

        // $count = NewJobCard::where([['name', '=', $request->name], ['part_number', '=', $request->part_number], ['id', '!=', $request->id]])->count();

        // if ($count == 0) {
        $sparePartLabel = SparePartLabel::firstOrCreate(
            ['title' => $request->name],
            ['title' => $request->name, 'spare_part_type' => 1]
        );
        $accessoryStock = NewJobCard::find($request->id)->first('stock');
        $accessory = NewJobCard::find($request->id);
        if (!empty($accessory)) {

            $accessory->label_id = $sparePartLabel->id;

            if (!empty($request->image)) {
                $accessory->image = uploadFile($request->image, '/uploads/accessory/') ?? '';
            }

            $accessory->unit_id = $request->unit_id;
            $accessory->brand = $request->brand;
            $accessory->suitable_for = $request->suitable_for;
            $accessory->price = $request->price;
            $accessory->discount = $request->discount;
            $accessory->stock = $request->stock;
            $accessory->description = $request->description;
            $accessory->save();


            $stockHistory = new StockHistory;
            $stockHistory->label_id = $sparePartLabel->id;
            $stockHistory->spare_part_id = $accessory->id;
            $stockHistory->category = 1;
            $stockHistory->user_id = Auth::user()->id;
            $stockHistory->last_stock = $accessoryStock->stock;
            $stockHistory->current_stock = $accessory->stock;
            $stockHistory->stock_type = 'addition';
            $stockHistory->remarks = "Stock added by spare part vendor.";
            $stockHistory->save();

            return redirect()->route('accessory.list')->with('message', 'Accessory Updated Successfully');
        }
        return redirect()->back()->with('message', 'Error! Something went wrong.');

        // } else {
        //   return redirect()->route('accessory.edit' . $id)->with('message', 'Duplicate Data');
        // }
    }

    public function destory($id)
    {
        NewJobCard::find($id)->delete();

        return redirect()->route('accessory.list')->with('message', 'Accessory Deleted Successfully');
    }

    public function destroyMultiple(Request $request)
    {
        $ids = $request->input('ids');

        NewJobCard::whereIn('id', $ids)->delete();

        return redirect()->route('accessory.list')->with('message', 'Accessory Deleted Successfully');
    }


    public function addDentMark(Request $request): View
    {   
        $title = 'Dent Mark';    
        $jobcard_numbers = $request->input('jobcard_number'); 
        $jobCardsDentImage = JobCardsDentMark::with('Image')->where('jobcard_number','JCN-BB4659B837')->first(); 
        return view('new_jobcard.dentmark',compact('title','jobcard_numbers','jobCardsDentImage')); 
    }


    
public function deleteDentMark(Request $request)
{

    JobCardsDentMark::where('id',$request->dentimageId)->delete();
    Image::where('id',$request->imageId)->delete();
    
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

    $jobCardsInspection = JobCardsInspection::where('jobcard_number','JCN-BB4659B837')->where('is_customer_voice',1)->get()->toArray();

    $jobCardsInspectionselect = JobCardsInspection::where('is_customer_voice',1)->get();
 
    // echo "<pre>";print_r($jobCardsInspection);die;
    return view('new_jobcard.customer_voice', compact('title','jobcard_numbers','jobCardsInspection','jobCardsInspectionselect'));
}


public function addField(Request $request) {


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
            JobCardsInspection::where('jobcard_number','JCN-BB4659B837')->where('is_customer_voice',1)->delete(); 
            foreach($request->customer_voice as $customer_voice){ 
            $data = JobCardsInspection::create([
                // 'jobcard_number' => $request->jobcard_numbers,    
                'jobcard_number' => 'JCN-BB4659B837',    
                'customer_voice' => $customer_voice,
                'is_customer_voice' => 1,
            ]); 
        }
            return response()->json([
                'success'=> 1,
                'message'=>"Customer Voice Added Successfully."
            ]);
        }


        
    public function workNotes(Request $request): View 
    {
        $jobcard_numbers = $request->input('jobcard_number');  
        $title = 'Work Notes';   
    
        $jobCardsInspection = JobCardsInspection::where('jobcard_number','JCN-BB4659B837')->where('is_customer_voice',0)->get()->toArray();
     
        return view('new_jobcard.work_notes', compact('title','jobcard_numbers','jobCardsInspection'));
    }
        
 

    public function addFieldWorkNote(Request $request) {


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

            JobCardsInspection::where('jobcard_number','JCN-BB4659B837')->where('is_customer_voice',0)->delete(); 
        
            foreach($request->work_notes as $work_note){ 
               JobCardsInspection::create([
                    // 'jobcard_number' => $request->jobcard_numbers,    
                    'jobcard_number' => 'JCN-BB4659B837',    
                    'customer_voice' => $work_note,
                    'is_customer_voice' => 0,
                ]); 
            } 

         $countworknotes =  JobCardsInspection::where('jobcard_number','JCN-BB4659B837')->where('is_customer_voice',0)->get()->count(); 

            return response()->json([
                'success'=> 1,
                'message'=>"Work Notes Added Successfully",
                'countworknotes'=>$countworknotes
            ]);
        } 


       
public function addphoto(Request $request): View

{
    $jobcard_number = $request->input('jobcard_number'); 
    $title =  'Add Photo';
    $jobcard_numbers =  $jobcard_number;


    return view('new_jobcard.addPhoto', compact('title','jobcard_numbers'));
}

        public function imageupload(Request $request)
        {
            $type = $request->type;
            $path = $type . '_path';
            $name = $type . '_name';
            $file_name = $request->$name;
            $file_path = '/uploads/gumasta/';
            $file = $request->file('gumasta_images');
            $movedFile=$file_data=$file_ids=$files=array();
            if (!empty($file)) {
                
                    $destinationPath = public_path().'/'.$file_path;
                    foreach($file as $value)
                    {
                        $ext = $value->getClientOriginalExtension();
                        $file_name = time().rand(1,2000).".".$value->getClientOriginalExtension();
                        $value->move($destinationPath,$file_name);
                        $movedFile[] =  $file_name;  
                    }
                    $view = array();
                    foreach($movedFile as $values)
                    {
                        $file_data= Image::create([
                            'path'=>$values,
                        ]);
                        
                        // echo "<pre>";print_r($file_data);die;
                        
                        $file_ids[]= $file_data->id;
                    //    $view = url('/uploads/gumasta/'.$values); 
                    }
                    
                    
                    $oldids= implode(',',$file_ids);
                 
                       $oldIdarr = explode(',',$oldids);
                        $allids = array_filter(array_merge($file_ids,$oldIdarr));
                        $files = Image::whereIn('id', $allids)->get();
                        $view  =  view('new_jobcard.outlet_imagesmultiple',['files'=>$files,'route' => $this->route])->render();
                     return response()->json(['status' => 1, 'message' => 'Image uploded Successfully', 'file_id' => implode(',',$file_ids), 'file_path' => $view ]);
            }else{ 
    
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
                'success'=> 1,
                'message'=>"Photos Added Successfully"
            ]);
        } 

        
        
    public function accessories(Request $request): View 
    {
        $jobcard_numbers = $request->input('jobcard_number');  
        $title = 'Accessories';   
    
        $jobCardsInspection = JobCardsInspection::where('jobcard_number','JCN-BB4659B837')->where('is_customer_voice',2)->get()->toArray();
     
        return view('new_jobcard.accessories', compact('title','jobcard_numbers','jobCardsInspection'));
    }
        
        
    
    public function addFieldAccessories(Request $request) {


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

            JobCardsInspection::where('jobcard_number','JCN-BB4659B837')->where('is_customer_voice',2)->delete(); 
        
            foreach($request->accessories as $accessories){ 
               JobCardsInspection::create([
                    // 'jobcard_number' => $request->jobcard_numbers,    
                    'jobcard_number' => 'JCN-BB4659B837',    
                    'customer_voice' => $accessories,
                    'is_customer_voice' => 2,
                ]); 
            } 


            return response()->json([
                'success'=> 1,
                'message'=>"Accessories Added Successfully"
            ]);
        } 


    }
 