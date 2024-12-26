<?php

namespace App\Http\Controllers\Api;

use App\JobCardsInspection;
use App\JobCardSparePart;
use App\Models\Companie;
use App\NewJobCard;
use App\Role;
use App\Role_user;
use Illuminate\Http\Request;
use App\User;
use App\Vehicle;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;
use Twilio\Rest\Client;
use DB;

class JobCardController extends BaseController
{

    public function searchVehicle(Request $request)
    {
        $success['vehicle'] = Vehicle::where('number_plate', 'like', '%' . $request->number_plate . '%')->get();
        return $this->sendResponse($success, 'Vehicles Show successfully.');
    }



    public function addcustomer(Request $request)
    {


        $validator = Validator::make($request->all(), [
            'vehicle_number' => 'required|unique:tbl_vehicles,number_plate',
            'chassis_number' => 'required|unique:tbl_vehicles,chassisno',
            'engine_number' => 'required|unique:tbl_vehicles,engineno',
            'meter_reading' => 'required',
            'vehicle_brand' => 'required',
            'vehicle_type' => 'required',
            'model' => 'required',
            'fuel_type' => 'required',
            'model_year' => 'required',
            'no_of_gear' => 'required',
            'date_of_manufacturing' => 'required',
            'gear_box' => 'required',
            'gear_box_no' => 'required',
            'engine_size' => 'required',
            'key_no' => 'required',
            'engine' => 'required',
            // customer detail
            'name' => 'required|string',
            'lastname' => 'required|string',
            'mobile' => 'required|regex:/^([0-9\s\-\+\(\)]*)$/|min:10|unique:users,mobile_no',
            'email' => 'required|unique:users,email',
            'address' => 'required',
            'gender' => 'required',
            'password' => 'required|string|min:8|confirmed',
            'country_id' => 'required',
            'state_id' => 'required',
            'city_id' => 'required',
        ]);

        if ($validator->fails()) {
            $errors = collect($validator->errors())->map(function ($error) {
                return $error[0]; // Only return the first error
            });
            return $this->sendResponse(null, 'Validation Error.', $errors, 200, false);
        }



        $getRoleId = Role::where('role_name', '=', 'Customer')->first();

        $dobs = $request->date_of_birth;
        if (getDateFormat() == 'm-d-Y') {
            $dob = date('Y-m-d', strtotime(str_replace('-', '/', $dobs)));
        } else {
            $dob = date('Y-m-d', strtotime($dobs));
        }

        $customer = new User;
        $customer->name = $request->name;
        $customer->lastname = $request->lastname;
        $customer->mobile_no = $request->mobile;
        $customer->email = $request->email;
        $customer->address = $request->address;
        $customer->gender = $request->gender;
        $customer->birth_date = $dobs;
        $customer->password = bcrypt($request->password);
        $customer->country_id = $request->country_id;
        $customer->state_id = $request->state_id;
        $customer->city_id = $request->city_id;
        $customer->role = "Customer";
        $customer->role_id = $getRoleId->id;
        $customer->language = "en";
        $customer->timezone = "UTC";
        $customer->save();


        if ($customer->save()) {
            $currentUserId = $customer->id;

            $role_user_table = new Role_user;
            $role_user_table->user_id = $currentUserId;
            $role_user_table->role_id = $getRoleId->id;
            $role_user_table->save();
        }

        $domm = $request->date_of_manufacturing;

        $dom = '';
        if (!empty($domm)) {
            if (getDateFormat() == 'm-d-Y') {
                $dom = date('Y-m-d', strtotime(str_replace('-', '/', $domm)));
            } else {
                $dom = date('Y-m-d', strtotime($domm));
            }
        }

        $vehical = new Vehicle;
        $vehical->chassisno = $request->chassis_number;
        $vehical->vehiclebrand_id = $request->vehicle_brand;
        $vehical->modelyear = $request->model_year;
        $vehical->fuel_id = $request->fuel_type;
        $vehical->modelname = $request->model;
        $vehical->odometerreading = $request->meter_reading;
        $vehical->engineno = $request->engine_number;
        $vehical->number_plate = $request->vehicle_number;
        $vehical->vehicletype_id = $request->vehicle_type;
        $vehical->customer_id = $customer->id;
        $vehical->nogears = $request->no_of_gear;
        $vehical->dom = $dom;
        $vehical->gearbox = $request->gear_box;
        $vehical->gearboxno = $request->gear_box_no;
        $vehical->enginesize = $request->engine_size;
        $vehical->keyno = $request->gear_no;
        $vehical->engine = $request->engine;
        $vehical->save();

        return $this->sendResponse(
            ['customer' => $customer, 'vehicle' => $vehical],
            'Customer Created successfully.',
            null,
            200
        );
    }


    public function vehicleType()
    {
        $success['vehicle_type'] = DB::table('tbl_vehicle_types')->where('soft_delete', 0)->get();
        return $this->sendResponse($success, 'Vehicle Type Show Successfully.');
    }

    public function vehicleBrand()
    {

        $success['vehicle_brand'] = DB::table('tbl_vehicle_brands')->where('soft_delete', 0)->get();

        return $this->sendResponse($success, 'Vehicle Brand Show Successfully.');
    }

    public function vehicleModel(Request $request)
    {

        $success['vehicle_model'] = DB::table('tbl_model_names')->where([['brand_id', '=', $request->model_id], ['soft_delete', '=', 0]])->get()->toArray();

        return $this->sendResponse($success, 'Vehicle Model Show Successfully.');
    }


    public function vehicleFuelType()
    {

        $success['vehicle_fuel_type'] = DB::table('tbl_fuel_types')->where('soft_delete', 0)->get(); 
        return $this->sendResponse($success, 'Vehicle Fuel Type Show Successfully.');
    }


    public function searchAccessories(Request $request)
    { 
        $success['accessories'] = JobCardsInspection::where('customer_voice', 'LIKE', '%' . $request->accessories . '%')
            ->where('is_customer_voice', 2)
            ->take(5)
            ->groupBy('customer_voice')
            ->get();

        return $this->sendResponse($success, 'Accessory Show Successfully.');
    }


    public function addaccessories(Request $request)
    {

                
        $validator = Validator::make($request->all(), [
            'accessories' => 'required', 
        ]);

        if ($validator->fails()) {
            $errors = collect($validator->errors())->map(function ($error) {
                return $error[0]; // Only return the first error
            });
            return $this->sendResponse(null, 'Validation Error.', $errors, 200, false);
        }

        $success['accessories'] = JobCardsInspection::create([
            'jobcard_number' => $request->jobcard_number,
            'customer_voice' => $request->accessories,
            'is_customer_voice' => 2,
        ]);

        return $this->sendResponse($success, 'Accessory Created Successfully.');
    }


    public function searchCustomerVoice(Request $request)
    {
        $success['customer_voice'] = JobCardsInspection::where('customer_voice', 'LIKE', '%' . $request->customer_voice . '%')
            ->where('is_customer_voice', 1)
            ->take(5)
            ->groupBy('customer_voice')
            ->get();

        return $this->sendResponse($success, 'Customer Voice Show Successfully.');
    }

    public function addCustomerVoice(Request $request)
    {

        $validator = Validator::make($request->all(), [
            'customer_voice' => 'required', 
        ]);

        if ($validator->fails()) {
            $errors = collect($validator->errors())->map(function ($error) {
                return $error[0]; // Only return the first error
            });
            return $this->sendResponse(null, 'Validation Error.', $errors, 200, false);
        }


        $success['customer_voice'] = JobCardsInspection::create([
            'jobcard_number' => $request->jobcard_number,
            'customer_voice' => $request->customer_voice,
            'is_customer_voice' => 1,
        ]);

        return $this->sendResponse($success, 'Customer Voice Created Successfully.');
    }


    public function searchWorkNotes(Request $request)
    {
        $success['customer_voice'] = JobCardsInspection::where('customer_voice', 'LIKE', '%' . $request->work_notes . '%')
            ->where('is_customer_voice', 0)
            ->take(5)
            ->groupBy('customer_voice')
            ->get();

        return $this->sendResponse($success, 'Customer Voice Show Successfully.');
    }

    public function addWorkNotes(Request $request)
    {

        $validator = Validator::make($request->all(), [
            'work_notes' => 'required', 
        ]);

        if ($validator->fails()) {
            $errors = collect($validator->errors())->map(function ($error) {
                return $error[0]; // Only return the first error
            });
            return $this->sendResponse(null, 'Validation Error.', $errors, 200, false);
        }


        $success['work_notes'] = JobCardsInspection::create([
            'jobcard_number' => $request->jobcard_number,
            'customer_voice' => $request->work_notes,
            'is_customer_voice' => 0,
        ]);

        return $this->sendResponse($success, 'Work Notes Created Successfully.');
    }
    public function storeJobcard(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'job_card_number' => 'required|string|max:255',
            'customer_id' => 'required|integer',
            'vehicle_id' => 'required|integer',
            'km_reading' => 'required|integer',
            'fual_level' => 'required|string',
            'sparepart.sparepart_id' => 'required|array',
            'sparepart.price' => 'required|array',
            'sparepart.qty' => 'required|array',
            'sparepart.discount' => 'required|array',
        ]);
    
        if ($validator->fails()) {
            $errors = collect($validator->errors())->map(function ($error) {
                return $error[0];
            });
            return $this->sendResponse(null, 'Validation Error.', $errors, 200, false);
        }
    
        $vehicleId = $request->vehicle_id;
        $customer = User::with([
            'vehicles' => function ($query) use ($vehicleId) {
                $query->where('id', $vehicleId);
            }
        ])->find($request->customer_id); // Fixed: customer_id instead of customer_name
    
        if (!$customer) {
            return $this->sendResponse(null, 'Customer not found.', [], 404, false);
        }
    
        if ($customer->vehicles->isEmpty()) {
            return $this->sendResponse(null, 'Vehicle not found for the given customer.', [], 404, false);
        }
    
        $vehicle = $customer->vehicles->first();
    
        $jobcard = new NewJobCard; 
        $jobcard->jobcard_number = $request->job_card_number;
        $jobcard->customer_id = $request->customer_id;
        $jobcard->customer_name = strtoupper($customer->name . ' ' . $customer->lastname);
        $jobcard->vehicle_id = $request->vehicle_id;
        $jobcard->vehical = $vehicle->modelname;
        $jobcard->vehical_number = $vehicle->number_plate;
        $jobcard->entry_date = Carbon::now();
        $jobcard->amount = $request->total_amount;
        $jobcard->final_amount = $request->final_amount;
        $jobcard->advance = $request->advance;
        $jobcard->discount = $request->discount;
        $jobcard->balance_amount = $request->balance_amount;
        $jobcard->supervisor_id = $request->supervisor;
        $jobcard->km_runing = $request->km_reading;
        $jobcard->fual_level = $request->fual_level; 
        $jobcard->delivery_date = $request->delivery_date; 
        $jobcard->delivery_time = $request->delivery_time; 
        $jobcard->save();
    
        $sparePartsData = [];
        foreach ($request->sparepart['sparepart_id'] as $index => $sparepartId) {
            $sparePartsData[] = [
                'jobcard_id' => $jobcard->id,
                'stock_label_id' => $sparepartId,
                'price' => $request->sparepart['price'][$index],
                'quantity' => $request->sparepart['qty'][$index],
                'total_amount' => $request->sparepart['price'][$index]*$request->sparepart['qty'][$index],
                'discount' => $request->sparepart['discount'][$index],
                'final_amount' => $request->sparepart['price'][$index]*$request->sparepart['qty'][$index]-$request->sparepart['discount'][$index],
                'machanic_id' => $request->mechanic_id,
                'created_at' => now(),
                'updated_at' => now(),
            ];
        }

        // echo "<pre>";print_r($sparePartsData);die;

        $jobCardSparePart = JobCardSparePart::insert($sparePartsData);

        $loobsData = [];
        foreach ($request->loobs['loobs_id'] as $index => $loobId) {
            $loobsData[] = [
                'jobcard_id' => $jobcard->id,
                'stock_label_id' => $loobId,
                'price' => $request->loobs['price'][$index],
                'quantity' => $request->loobs['qty'][$index],
                'total_amount' => $request->loobs['price'][$index]*$request->loobs['qty'][$index],
                'discount' => $request->loobs['discount'][$index],
                'final_amount' => $request->loobs['price'][$index]*$request->loobs['qty'][$index]-$request->loobs['discount'][$index],
                'machanic_id' => $request->mechanic_id,
                'created_at' => now(),
                'updated_at' => now(),
            ];
        }

        $jobCardloobs = JobCardSparePart::insert($loobsData);

        $toolsData = [];
        foreach ($request->tools['tools_id'] as $index => $toolsId) {
            $toolsData[] = [
                'jobcard_id' => $jobcard->id,
                'stock_label_id' => $toolsId,
                'price' => $request->tools['price'][$index],
                'quantity' => $request->tools['qty'][$index],
                'total_amount' => $request->tools['price'][$index]*$request->tools['qty'][$index],
                'discount' => $request->tools['discount'][$index],
                'final_amount' => $request->tools['price'][$index]*$request->tools['qty'][$index]-$request->tools['discount'][$index],
                'machanic_id' => $request->mechanic_id,
                'created_at' => now(),
                'updated_at' => now(),
            ];
        }

        $jobCardSparePart = JobCardSparePart::insert($toolsData);
    
        return $this->sendResponse($jobcard, 'Job Card Created Successfully.');
    }
    
}
