<?php

namespace App\Http\Controllers\JobCard;

use App\Http\Controllers\Controller;
use App\Image;
use App\JobCardCustomer;
use App\JobCardsCustomer;
use App\JobCardsDentMark;
use App\NewJobCard;
use App\User;  
use Illuminate\Http\Request;
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
        $jobCardsDentMark = JobCardsDentMark::where('jobcard_number','10022111')->first();

        return view('new_jobcard.add',compact('customers','country','jobCardsDentMark'));
    }
    public function store(Request $request)
    {

        echo "<pre>";print_r($request->all());die;
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


    public function addDentMark(): IlluminateViewView
    {   
        $data['title'] = 'Dent Mark';  
        return view('new_jobcard.dentmark',$data); 
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
        return view('new_jobcard.customer_voice',$data); 
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
                'success'=> 1,
                'message'=>"Customer Voice Added Successfully."
            ]);
        }


        
    public function workNotes(): IlluminateViewView
    {   
        $data['title'] = 'Work notes';  
        return view('new_jobcard.work_notes',$data); 
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
                'success'=> 1,
                'message'=>"Work Notes Added Successfully."
            ]);
        }
       


        public function addphoto(): IlluminateViewView
        {   
            $data['title'] = 'Add Photo';  
            return view('new_jobcard.addPhoto',$data); 
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
                     return response()->json(['status' => 1, 'file_id' => $file_ids, 'file_path' => $view ]);
            }else{ 
    
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
        
    }
 

