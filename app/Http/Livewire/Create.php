<?php

namespace App\Http\Livewire;


use Livewire\Component;
use Auth;
use App\User;
use App\Branch;
use DB;

class Create extends Component
{
    public $employee = [];
    public $customer = [];
    public $code = [];
    public $country = [];
    public $onlycustomer = [];
    public $vehical_brand = [];
    public $vehical_type = [];
    public $fuel_type = [];
    public $color = [];
    public $model_name = [];
    public $tbl_custom_fields = [];
    public $branchDatas = [];
    public $repairCategoryList = [];
    public $timezone = [];
    public $vehicles = [];
    public function render()
    { 
        $this->getData();

        return view('livewire.create');
    }


    public function getData()
	{  
		$last_order = DB::table('tbl_services')->latest()->where('sales_id', '=', null)->get()->first();

        if (!empty($last_order)) {

            $last_full_job_number = $last_order->job_no;
            $last_job_number_digit = substr($last_full_job_number, 1);
            $new_number = "J" . str_pad($last_job_number_digit + 1, 6, 0, STR_PAD_LEFT);
        } else {
            $new_number = 'J000001';
        }

        $code = $new_number;

        //Customer add
        $customer = DB::table('users')->where([['role', 'Customer'], ['soft_delete', 0]])->get()->toArray();
        $country = DB::table('tbl_countries')->get()->toArray();
        $onlycustomer = DB::table('users')->where([['role', '=', 'Customer'], ['id', '=', Auth::User()->id]])->first();

        //vehicle add
        $vehical_type = DB::table('tbl_vehicle_types')->where('soft_delete', '=', 0)->get()->toArray();
        $vehical_brand = DB::table('tbl_vehicle_brands')->where('soft_delete', '=', 0)->get()->toArray();
        $fuel_type = DB::table('tbl_fuel_types')->where('soft_delete', '=', 0)->get()->toArray();
        $color = DB::table('tbl_colors')->where('soft_delete', '=', 0)->get()->toArray();
        $model_name = DB::table('tbl_model_names')->where('soft_delete', '=', 0)->get()->toArray();

        //Custom Field Data
        $tbl_custom_fields = DB::table('tbl_custom_fields')->where([['form_name', '=', 'service'], ['always_visable', '=', 'yes'], ['soft_delete', '=', 0]])->get()->toArray();

        $currentUser = User::where([['soft_delete', 0], ['id', '=', Auth::User()->id]])->orderBy('id', 'DESC')->first();
        // $adminCurrentBranch = BranchSetting::where('id', '=', 1)->first();
        if (isAdmin(Auth::User()->role_id)) {

            $branchDatas = Branch::where('soft_delete', '=', 0)->get();
            $employee = DB::table('users')->where([['role', 'employee'], ['soft_delete', 0]])->get()->toArray();
        } elseif (getUsersRole(Auth::user()->role_id) == 'Customer') {
            $branchDatas = Branch::where('soft_delete', '=', 0)->get();
            $employee = DB::table('users')->where([['role', 'employee'], ['soft_delete', 0]])->get()->toArray();
        } else {
            $branchDatas = Branch::where('id', $currentUser->branch_id)->where('soft_delete', '=', 0)->get();
            $employee = DB::table('users')->where([['role', 'employee'], ['soft_delete', 0], ['branch_id', $currentUser->branch_id]])->get()->toArray();
        }

        $repairCategoryList = DB::table('table_repair_category')->where([['soft_delete', "=", 0]])->get()->toArray();


        $this->employee = $employee;
        $this->customer = $customer;
        $this->code = $code;
        $this->country = $country;
        $this->onlycustomer = $onlycustomer;
        $this->vehical_brand = $vehical_brand;
        $this->vehical_type = $vehical_type;
        $this->fuel_type = $fuel_type;
        $this->color = $color;
        $this->model_name = $model_name;
        $this->tbl_custom_fields = $tbl_custom_fields;
        $this->branchDatas = $branchDatas;
        $this->repairCategoryList = $repairCategoryList;


        $this->timezone = Auth::User()->timezone;
		
		
	}

	public function get_vehicle_name($cus_id)
    {
       

        $vehicles = getVehicles1($cus_id);

		$this->vehicles = collect($vehicles)->map(function ($vehicle) {
			$vehicleParts = explode('/', $vehicle);
			return (object) [
				'vehicle_id' => $vehicleParts[3] ?? null,
				'name' => $vehicle,
			];
		});
		
    }
}
