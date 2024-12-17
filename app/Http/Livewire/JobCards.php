<?php

namespace App\Http\Livewire;

use Auth;
use App\User;
use App\Sale;
use App\Point;
use App\Color;
use App\Branch;
use App\Service;
use App\Vehicle;
use App\Setting;
use App\Product;
use App\Invoice;
use App\Washbay;
use App\Gatepass;
use App\Updatekey;
use App\JobcardDetail;
use App\BranchSetting;
use App\AccountTaxRate;
use App\tbl_service_pros;
use App\CheckoutCategory;
use Livewire\Component;
use Illuminate\Support\Facades\Gate;
use DB;

class JobCards extends Component
{
    public $services = [];
    public $available = [];
    public function render()
    { 
        $this->List();

        return view('livewire.job-cards');
    }


    public function List()
	{ 
		$currentUser = User::where([['soft_delete', 0], ['id', '=', Auth::User()->id]])->orderBy('id', 'DESC')->first();
		// $adminCurrentBranch = BranchSetting::where('id', '=', 1)->first();

		if (!isAdmin(Auth::User()->role_id)) {
			if (getUsersRole(Auth::User()->role_id) == "Customer") {
				if (!empty($request->free)) {
					$services = Service::orderBy('service_date', 'asc')
						->where([['job_no', 'like', 'J%'], ['service_type', '=', 'free']])
						->where('customer_id', '=', Auth::User()->id)
						->where('soft_delete', '=', 0)
						->whereNotIn('quotation_modify_status', [1])
						->get();
				} elseif (!empty($request->paid)) {
					$services = Service::orderBy('service_date', 'asc')
						->where([['job_no', 'like', 'J%'], ['service_type', '=', 'paid']])
						->where('customer_id', '=', Auth::User()->id)
						->where('soft_delete', '=', 0)
						->whereNotIn('quotation_modify_status', [1])
						->get();
				} elseif (!empty($request->repeatjob)) {
					$services = Service::orderBy('service_date', 'asc')
						->where([['job_no', 'like', 'J%'], ['service_category', '=', 'repeat job']])
						->where('customer_id', '=', Auth::User()->id)
						->where('soft_delete', '=', 0)
						->whereNotIn('quotation_modify_status', [1])
						->get();
				} else {
					$services = Service::orderBy('id', 'desc')->where([['job_no', 'like', 'J%'], ['customer_id', '=', Auth::User()->id]])->where('soft_delete', '=', 0)->whereNotIn('quotation_modify_status', [1])->get();
				}
			} elseif (getUsersRole(Auth::User()->role_id) == "Employee") {
				if (Gate::allows('jobcard_owndata')) {
					if (!empty($request->free)) {
						$services = Service::orderBy('service_date', 'asc')
							->where([['job_no', 'like', 'J%'], ['service_type', '=', 'free'], ['branch_id', $currentUser->branch_id]])
							->where('assign_to', '=', Auth::User()->id)
							->where('soft_delete', '=', 0)
							->whereNotIn('quotation_modify_status', [1])
							->get();
					} elseif (!empty($request->paid)) {
						$services = Service::orderBy('service_date', 'asc')
							->where([['job_no', 'like', 'J%'], ['service_type', '=', 'paid'], ['branch_id', $currentUser->branch_id]])
							->where('assign_to', '=', Auth::User()->id)
							->where('soft_delete', '=', 0)
							->whereNotIn('quotation_modify_status', [1])
							->get();
					} elseif (!empty($request->repeatjob)) {
						$services = Service::orderBy('service_date', 'asc')
							->where([['job_no', 'like', 'J%'], ['service_category', '=', 'repeat job'], ['branch_id', $currentUser->branch_id]])
							->where('assign_to', '=', Auth::User()->id)
							->where('soft_delete', '=', 0)
							->whereNotIn('quotation_modify_status', [1])
							->get();
					} else {
						$services = Service::orderBy('id', 'desc')->where([['job_no', 'like', 'J%'], ['assign_to', '=', Auth::User()->id], ['branch_id', $currentUser->branch_id]])->where('soft_delete', '=', 0)->whereNotIn('quotation_modify_status', [1])->get();
					}
				} else {
					if (!empty($request->free)) {
						$services = Service::orderBy('service_date', 'asc')->where([['job_no', 'like', 'J%'], ['service_type', '=', 'free'], ['soft_delete', '=', 0], ['branch_id', $adminCurrentBranch->branch_id]])->whereNotIn('quotation_modify_status', [1])->get();
					} elseif (!empty($request->paid)) {
						$services = Service::orderBy('service_date', 'asc')->where([['job_no', 'like', 'J%'], ['service_type', '=', 'paid'], ['soft_delete', '=', 0], ['branch_id', $adminCurrentBranch->branch_id]])->whereNotIn('quotation_modify_status', [1])->get();
					} elseif (!empty($request->repeatjob)) {
						$services = Service::orderBy('service_date', 'asc')->where([['job_no', 'like', 'J%'], ['service_category', '=', 'repeat job'], ['soft_delete', '=', 0], ['branch_id', $adminCurrentBranch->branch_id]])->whereNotIn('quotation_modify_status', [1])->get();
					} else {
						$services = Service::where([['soft_delete', 0], ['job_no', 'like', 'J%']])->whereNotIn('quotation_modify_status', [1])->orderBy('id', 'desc')->get();
					}
				}
			} elseif (getUsersRole(Auth::user()->role_id) == 'Support Staff' || getUsersRole(Auth::user()->role_id) == 'Accountant' || getUsersRole(Auth::user()->role_id) == 'Branch Admin') {

				if (!empty($request->free)) {
					$services = Service::orderBy('service_date', 'asc')->where([['job_no', 'like', 'J%'], ['service_type', '=', 'free'], ['branch_id', $currentUser->branch_id]])->where('soft_delete', '=', 0)->whereNotIn('quotation_modify_status', [1])->get();
				} elseif (!empty($request->paid)) {
					$services = Service::orderBy('service_date', 'asc')->where([['job_no', 'like', 'J%'], ['service_type', '=', 'paid'], ['branch_id', $currentUser->branch_id]])->where('soft_delete', '=', 0)->whereNotIn('quotation_modify_status', [1])->get();
				} elseif (!empty($request->repeatjob)) {
					$services = Service::orderBy('service_date', 'asc')->where([['job_no', 'like', 'J%'], ['service_category', '=', 'repeat job'], ['branch_id', $currentUser->branch_id]])->where('soft_delete', '=', 0)->whereNotIn('quotation_modify_status', [1])->get();
				} else {
					$services = Service::orderBy('id', 'desc')->where([['job_no', 'like', 'J%'], ['branch_id', $currentUser->branch_id]])->where('soft_delete', '=', 0)->whereNotIn('quotation_modify_status', [1])->get();
				}
			}
		} else {
			if (!empty($request->free)) {
				$services = Service::orderBy('service_date', 'asc')->where([['job_no', 'like', 'J%'], ['service_type', '=', 'free'], ['soft_delete', '=', 0], ['branch_id', $adminCurrentBranch->branch_id]])->whereNotIn('quotation_modify_status', [1])->get();
			} elseif (!empty($request->paid)) {
				$services = Service::orderBy('service_date', 'asc')->where([['job_no', 'like', 'J%'], ['service_type', '=', 'paid'], ['soft_delete', '=', 0], ['branch_id', $adminCurrentBranch->branch_id]])->whereNotIn('quotation_modify_status', [1])->get();
			} elseif (!empty($request->repeatjob)) {
				$services = Service::orderBy('service_date', 'asc')->where([['job_no', 'like', 'J%'], ['service_category', '=', 'repeat job'], ['soft_delete', '=', 0], ['branch_id', $adminCurrentBranch->branch_id]])->whereNotIn('quotation_modify_status', [1])->get();
			} else {
				$services = Service::where([['soft_delete', 0], ['job_no', 'like', 'J%']])->whereNotIn('quotation_modify_status', [1])->orderBy('id', 'desc')->get();
			}
		}

		$month = date('m');
		$year = date('Y');
		$start_date = "$year/$month/01";
		$end_date = "$year/$month/31";

		$current_month = DB::select("SELECT service_date FROM tbl_services WHERE service_date BETWEEN  '$start_date' AND '$end_date'");
		if (!empty($current_month)) {
			foreach ($current_month as $list) {
				$date[] = $list->service_date;
			}
			$available = json_encode($date);
		} else {
			$available = json_encode([0]);
		}

		foreach ($services as $service) {
			$job = JobcardDetail::where('service_id', '=', $service->id)->first();
			if ($job) {
				$service->next_date = $job->next_date;
			} else {
				// If record doesn't exist, create a new one
				$tbl_jobcard_details = new JobcardDetail;
				$tbl_jobcard_details->customer_id = $service->customer_id;
				$tbl_jobcard_details->vehicle_id = $service->vehicle_id;
				$tbl_jobcard_details->service_id = $service->id;
				$tbl_jobcard_details->jocard_no = $service->job_no;
				$tbl_jobcard_details->in_date = $service->created_at;
				$tbl_jobcard_details->save();

				$service->next_date = $tbl_jobcard_details->next_date;
			}
		}

        $this->services = $services;
        $this->available = $available;
 
	}
}
