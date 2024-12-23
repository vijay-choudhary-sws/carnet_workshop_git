<?php

namespace App\Http\Controllers;

use DB;
use Auth;
use App\Sale;
use App\User;
use App\Service;
use App\Product;
use App\BranchSetting;
use App\EmailLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\URL;
use Mpdf\Mpdf;
use Mpdf\Output\Destination;

class Reportcontroller extends Controller
{
	public function __construct()
	{
		$this->middleware('auth');
	}

	//sales list in report
	public function sales()
	{
		$s_date = '';
		$e_date = '';
		$all_customer = 'all';
		$all_salesman = 'all';
		$Sales = DB::select("SELECT count(*) as counts, RIGHT(YEAR(`date`),4) as date FROM tbl_sales group by YEAR(`date`) ORDER BY date ASC");

		$title_report = 'All Sales';
		$date_report = 'Year';
		$title = 'Sales';



		$currentUser = User::where([['soft_delete', 0], ['id', '=', Auth::User()->id]])->orderBy('id', 'DESC')->first();
		$adminCurrentBranch = BranchSetting::where('id', '=', 1)->first();

		if (isAdmin(Auth::User()->role_id)) {
			$Select_customer = User::where([['role', '=', 'Customer'], ['soft_delete', 0]])->get();
			$Select_salesman = User::where([['role', '=', 'employee'], ['soft_delete', 0], ['branch_id', $adminCurrentBranch->branch_id]])->get();
			$salesreport = Sale::where([['soft_delete', 0], ['branch_id', $adminCurrentBranch->branch_id]])->orderby('id', 'DESC')->get();
		} elseif (getUsersRole(Auth::user()->role_id) == 'Customer') {
			$Select_customer = User::where([['role', '=', 'Customer'], ['soft_delete', 0]])->get();
			$Select_salesman = User::where([['role', '=', 'employee'], ['soft_delete', 0]])->get();
			$salesreport = Sale::where('soft_delete', '=', 0)->orderby('id', 'DESC')->get();
		} else {
			$Select_customer = User::where([['role', '=', 'Customer'], ['soft_delete', 0]])->get();
			$Select_salesman = User::where([['role', '=', 'employee'], ['soft_delete', 0], ['branch_id', $currentUser->branch_id]])->get();
			$salesreport = Sale::where([['soft_delete', 0], ['branch_id', $currentUser->branch_id]])->orderby('id', 'DESC')->get();
		}
		return view('report.sales.list', compact('all_customer', 'Select_customer', 'salesreport', 'Sales', 'title_report', 'date_report', 'title', 'Select_salesman', 'all_salesman'));
	}

	//sales list based on date in report
	public function record_sales(Request $request)
	{
		if (getDateFormat() == 'm-d-Y') {
			$s_date = date('Y-m-d', strtotime(str_replace('-', '/', $request->start_date)));
			$e_date = date('Y-m-d', strtotime(str_replace('-', '/', $request->end_date)));
		} else {
			$s_date = date('Y-m-d', strtotime($request->start_date));
			$e_date = date('Y-m-d', strtotime($request->end_date));
		}
		$all_customer = $request->s_customer;
		$all_salesman = $request->s_salesman;


		$currentUser = User::where([['soft_delete', 0], ['id', '=', Auth::User()->id]])->orderBy('id', 'DESC')->first();
		$adminCurrentBranch = BranchSetting::where('id', '=', 1)->first();

		if (isAdmin(Auth::User()->role_id)) {
			$Select_salesman = User::where([['role', '=', 'employee'], ['soft_delete', 0], ['branch_id', $adminCurrentBranch->branch_id]])->get();

			if ($s_date == "" && $e_date == "" && $all_customer == 'all' && $all_salesman == 'all') {

				$Sales = DB::select("SELECT count(*) as counts, RIGHT(YEAR(`date`),4) as date FROM tbl_sales where customer_id='$all_customer' group by YEAR(`date`) ORDER BY date ASC");

				$title_report = 'All Sales';
				$date_report = 'Year';
				$title = 'Sales';

				$salesreport = Sale::where([['soft_delete', 0], ['branch_id', $adminCurrentBranch->branch_id]])->get();
			} elseif ($s_date != "" && $e_date != "" && $all_customer == 'all' && $all_salesman == 'all') {
				$Sales = DB::select("select count(*) as counts, CONCAT(MONTHNAME(`date`),'-',RIGHT(YEAR(`date`),4)) as date FROM tbl_sales where date BETWEEN  '$s_date' AND '$e_date' GROUP BY MONTH(date), YEAR(date)");

				$title_report = 'All Sales';
				$date_report = 'Year';
				$title = 'Sales';

				$salesreport = Sale::whereBetween('date', array($s_date, $e_date))->where([['soft_delete', 0], ['branch_id', $adminCurrentBranch->branch_id]])->get();
			} else if ($s_date == "" && $e_date == "" && $all_customer != 'all' && $all_salesman == 'all') {
				$Sales = DB::select("SELECT count(*) as counts, RIGHT(YEAR(`date`),4) as date FROM tbl_sales where customer_id='$all_customer' group by YEAR(`date`) ORDER BY date ASC");
				$title_report = 'All Sales';
				$date_report = 'Year';
				$title = 'Sales';

				$salesreport = Sale::where('customer_id', '=', $all_customer)->where([['soft_delete', 0], ['branch_id', $adminCurrentBranch->branch_id]])->get();
			} else if ($s_date != "" && $e_date != "" && $all_customer != 'all' && $all_salesman == 'all') {
				$Sales = DB::select("select count(*) as counts, CONCAT(MONTHNAME(`date`),'-',RIGHT(YEAR(`date`),4)) as date FROM tbl_sales where date BETWEEN  '$s_date' AND '$e_date' and customer_id='$all_customer' GROUP BY MONTH(date), YEAR(date)");

				$title_report = 'All Sales';
				$date_report = 'Year';
				$title = 'Sales';

				$salesreport = Sale::whereBetween('date', array($s_date, $e_date))->where('customer_id', '=', $all_customer)->where([['soft_delete', 0], ['branch_id', $adminCurrentBranch->branch_id]])->get();
			} else if ($s_date != "" && $e_date != "" && $all_customer == 'all' && $all_salesman != 'all') {
				$Sales = DB::select("select count(*) as counts, CONCAT(MONTHNAME(`date`),'-',RIGHT(YEAR(`date`),4)) as date FROM tbl_sales where date BETWEEN  '$s_date' AND '$e_date' and salesmanname='$all_salesman' GROUP BY MONTH(date), YEAR(date)");

				$title_report = 'All Sales';
				$date_report = 'Year';
				$title = 'Sales';

				$salesreport = Sale::whereBetween('date', array($s_date, $e_date))->where('salesmanname', '=', $all_salesman)->where([['soft_delete', 0], ['branch_id', $adminCurrentBranch->branch_id]])->get();
			} else if ($s_date != "" && $e_date != "" && $all_customer != 'all' && $all_salesman != 'all') {
				$Sales = DB::select("select count(*) as counts, CONCAT(MONTHNAME(`date`),'-',RIGHT(YEAR(`date`),4)) as date FROM tbl_sales where date BETWEEN  '$s_date' AND '$e_date' and customer_id='$all_customer' and salesmanname='$all_salesman' GROUP BY MONTH(date), YEAR(date)");

				$title_report = 'All Sales';
				$date_report = 'Year';
				$title = 'Sales';

				$salesreport = Sale::whereBetween('date', array($s_date, $e_date))->where([['salesmanname', '=', $all_salesman], ['customer_id', '=', $all_customer]])->where([['soft_delete', 0], ['branch_id', $adminCurrentBranch->branch_id]])->get();
			}
		} elseif (getUsersRole(Auth::user()->role_id) == 'Customer') {
			$Select_salesman = User::where('role', '=', 'employee')->get();

			if ($s_date == "" && $e_date == "" && $all_customer == 'all' && $all_salesman == 'all') {

				$Sales = DB::select("SELECT count(*) as counts, RIGHT(YEAR(`date`),4) as date FROM tbl_sales where customer_id='$all_customer' group by YEAR(`date`) ORDER BY date ASC");

				$title_report = 'All Sales';
				$date_report = 'Year';
				$title = 'Sales';

				$salesreport = Sale::where('soft_delete', '=', 0)->get();
			} elseif ($s_date != "" && $e_date != "" && $all_customer == 'all' && $all_salesman == 'all') {
				$Sales = DB::select("select count(*) as counts, CONCAT(MONTHNAME(`date`),'-',RIGHT(YEAR(`date`),4)) as date FROM tbl_sales where date BETWEEN  '$s_date' AND '$e_date' GROUP BY MONTH(date), YEAR(date)");

				$title_report = 'All Sales';
				$date_report = 'Year';
				$title = 'Sales';

				$salesreport = Sale::whereBetween('date', array($s_date, $e_date))->get();
			} else if ($s_date == "" && $e_date == "" && $all_customer != 'all' && $all_salesman == 'all') {
				$Sales = DB::select("SELECT count(*) as counts, RIGHT(YEAR(`date`),4) as date FROM tbl_sales where customer_id='$all_customer' group by YEAR(`date`) ORDER BY date ASC");
				$title_report = 'All Sales';
				$date_report = 'Year';
				$title = 'Sales';

				$salesreport = Sale::where('customer_id', '=', $all_customer)->get();
			} else if ($s_date != "" && $e_date != "" && $all_customer != 'all' && $all_salesman == 'all') {
				$Sales = DB::select("select count(*) as counts, CONCAT(MONTHNAME(`date`),'-',RIGHT(YEAR(`date`),4)) as date FROM tbl_sales where date BETWEEN  '$s_date' AND '$e_date' and customer_id='$all_customer' GROUP BY MONTH(date), YEAR(date)");

				$title_report = 'All Sales';
				$date_report = 'Year';
				$title = 'Sales';

				$salesreport = Sale::whereBetween('date', array($s_date, $e_date))->where('customer_id', '=', $all_customer)->get();
			} else if ($s_date != "" && $e_date != "" && $all_customer == 'all' && $all_salesman != 'all') {
				$Sales = DB::select("select count(*) as counts, CONCAT(MONTHNAME(`date`),'-',RIGHT(YEAR(`date`),4)) as date FROM tbl_sales where date BETWEEN  '$s_date' AND '$e_date' and salesmanname='$all_salesman' GROUP BY MONTH(date), YEAR(date)");

				$title_report = 'All Sales';
				$date_report = 'Year';
				$title = 'Sales';

				$salesreport = Sale::whereBetween('date', array($s_date, $e_date))->where('salesmanname', '=', $all_salesman)->get();
			} else if ($s_date != "" && $e_date != "" && $all_customer != 'all' && $all_salesman != 'all') {
				$Sales = DB::select("select count(*) as counts, CONCAT(MONTHNAME(`date`),'-',RIGHT(YEAR(`date`),4)) as date FROM tbl_sales where date BETWEEN  '$s_date' AND '$e_date' and customer_id='$all_customer' and salesmanname='$all_salesman' GROUP BY MONTH(date), YEAR(date)");

				$title_report = 'All Sales';
				$date_report = 'Year';
				$title = 'Sales';

				$salesreport = Sale::whereBetween('date', array($s_date, $e_date))->where([['salesmanname', '=', $all_salesman], ['customer_id', '=', $all_customer]])->get();
			}
		} else {
			$Select_salesman = User::where([['role', '=', 'employee'], ['soft_delete', 0], ['branch_id', $currentUser->branch_id]])->get();
			if ($s_date == "" && $e_date == "" && $all_customer == 'all' && $all_salesman == 'all') {
				$Sales = DB::select("SELECT count(*) as counts, RIGHT(YEAR(`date`),4) as date FROM tbl_sales where customer_id='$all_customer' group by YEAR(`date`) ORDER BY date ASC");

				$title_report = 'All Sales';
				$date_report = 'Year';
				$title = 'Sales';

				$salesreport = Sale::where([['soft_delete', 0], ['branch_id', $currentUser->branch_id]])->get();
			} elseif ($s_date != "" && $e_date != "" && $all_customer == 'all' && $all_salesman == 'all') {
				$Sales = DB::select("select count(*) as counts, CONCAT(MONTHNAME(`date`),'-',RIGHT(YEAR(`date`),4)) as date FROM tbl_sales where date BETWEEN  '$s_date' AND '$e_date' GROUP BY MONTH(date), YEAR(date)");

				$title_report = 'All Sales';
				$date_report = 'Year';
				$title = 'Sales';

				$salesreport = Sale::whereBetween('date', array($s_date, $e_date))->where([['soft_delete', 0], ['branch_id', $currentUser->branch_id]])->get();
			} else if ($s_date == "" && $e_date == "" && $all_customer != 'all' && $all_salesman == 'all') {
				$Sales = DB::select("SELECT count(*) as counts, RIGHT(YEAR(`date`),4) as date FROM tbl_sales where customer_id='$all_customer' group by YEAR(`date`) ORDER BY date ASC");
				$title_report = 'All Sales';
				$date_report = 'Year';
				$title = 'Sales';

				$salesreport = Sale::where('customer_id', '=', $all_customer)->where([['soft_delete', 0], ['branch_id', $currentUser->branch_id]])->get();
			} else if ($s_date != "" && $e_date != "" && $all_customer != 'all' && $all_salesman == 'all') {
				$Sales = DB::select("select count(*) as counts, CONCAT(MONTHNAME(`date`),'-',RIGHT(YEAR(`date`),4)) as date FROM tbl_sales where date BETWEEN  '$s_date' AND '$e_date' and customer_id='$all_customer' GROUP BY MONTH(date), YEAR(date)");

				$title_report = 'All Sales';
				$date_report = 'Year';
				$title = 'Sales';

				$salesreport = Sale::whereBetween('date', array($s_date, $e_date))->where('customer_id', '=', $all_customer)->where([['soft_delete', 0], ['branch_id', $currentUser->branch_id]])->get();
			} else if ($s_date != "" && $e_date != "" && $all_customer == 'all' && $all_salesman != 'all') {
				$Sales = DB::select("select count(*) as counts, CONCAT(MONTHNAME(`date`),'-',RIGHT(YEAR(`date`),4)) as date FROM tbl_sales where date BETWEEN  '$s_date' AND '$e_date' and salesmanname='$all_salesman' GROUP BY MONTH(date), YEAR(date)");

				$title_report = 'All Sales';
				$date_report = 'Year';
				$title = 'Sales';

				$salesreport = Sale::whereBetween('date', array($s_date, $e_date))->where('salesmanname', '=', $all_salesman)->where([['soft_delete', 0], ['branch_id', $currentUser->branch_id]])->get();
			} else if ($s_date != "" && $e_date != "" && $all_customer != 'all' && $all_salesman != 'all') {
				$Sales = DB::select("select count(*) as counts, CONCAT(MONTHNAME(`date`),'-',RIGHT(YEAR(`date`),4)) as date FROM tbl_sales where date BETWEEN  '$s_date' AND '$e_date' and customer_id='$all_customer' and salesmanname='$all_salesman' GROUP BY MONTH(date), YEAR(date)");

				$title_report = 'All Sales';
				$date_report = 'Year';
				$title = 'Sales';

				$salesreport = Sale::whereBetween('date', array($s_date, $e_date))->where([['salesmanname', '=', $all_salesman], ['customer_id', '=', $all_customer]])->where([['soft_delete', 0], ['branch_id', $currentUser->branch_id]])->get();
			}
		}

		$Select_customer = User::where([['role', '=', 'Customer'], ['soft_delete', 0]])->get();

		return view('report.sales.list', compact('Select_customer', 'salesreport', 'all_customer', 's_date', 'e_date', 'Sales', 'title_report', 'date_report', 'title', 'all_salesman', 'Select_salesman'));
	}


	//service list in report
	public function service(Request $request)
	{
		$all_service = $request->service_select;
		$title_report = 'All Service';
		$date_report = 'Year';
		$title = 'Service';

		$service = DB::select("SELECT count(*) as counts, RIGHT(YEAR(`service_date`),4) as date FROM tbl_services where done_status=1  group by YEAR(`service_date`) ORDER BY service_date ASC");

		$currentUser = User::where([['soft_delete', 0], ['id', '=', Auth::User()->id]])->orderBy('id', 'DESC')->first();
		// $adminCurrentBranch = BranchSetting::where('id', '=', 1)->first();
		if (isAdmin(Auth::User()->role_id)) {
			$servicereport = Service::where([['soft_delete', 0], ['done_status', '=', 1]])->orderBy('id', 'desc')->get();
		} elseif (getUsersRole(Auth::user()->role_id) == 'Customer') {
			$servicereport = Service::where('done_status', '=', 1)->where('soft_delete', 0)->orderBy('id', 'desc')->get();
		} else {
			$servicereport = Service::where([['done_status', '=', 1], ['soft_delete', 0], ['branch_id', $currentUser->branch_id]])->orderBy('id', 'desc')->get();
		}

		$customers = User::where([['role', '=', 'Customer'], ['soft_delete', 0]])->get();
		$select_customerId = "";

		$totalAmount = 0;
		// Loop through each service record and sum the amounts
		foreach ($servicereport as $s) {
			$paidAmount = getPaidAmount($s->job_no);
			$totalAmount += $paidAmount;
		}
		//dd($service, $servicereport);
		return view('report.service.list', compact('all_service', 'servicereport', 'title_report', 'date_report', 'title', 'service', 'customers', 'select_customerId', 'totalAmount'));
	}

	//service list based on date in report
	public function record_service(Request $request)
	{
		if (getDateFormat() == 'm-d-Y') {
			$s_date = date('Y-m-d', strtotime(str_replace('-', '/', $request->start_date)));
			$e_date = date('Y-m-d', strtotime(str_replace('-', '/', $request->end_date)));
		} else {
			$s_date = date('Y-m-d', strtotime($request->start_date));
			$e_date = date('Y-m-d', strtotime($request->end_date));
		}

		$s_date .= " 00:00:00";
		$e_date .= " 23:59:59";

		$all_service = $request->service_select;
		$select_customerId = $request->select_customername;


		$currentUser = User::where([['soft_delete', 0], ['id', '=', Auth::User()->id]])->orderBy('id', 'DESC')->first();
		// $adminCurrentBranch = BranchSetting::where('id', '=', 1)->first();
		if (isAdmin(Auth::User()->role_id)) {

			if ($s_date == "" && $e_date == "" && $all_service == 'all') {
				$service = DB::select("SELECT count(*) as counts, RIGHT(YEAR(`service_date`),4) as date FROM tbl_services where (done_status=1) group by YEAR(`service_date`) ORDER BY date ASC");
				$title_report = 'All Service';
				$date_report = 'Year';
				$title = 'Service';

				$servicereport = Service::where('done_status', '=', 1)->where([['soft_delete', 0]])->orderBy('id', 'desc')->get();
			} elseif ($s_date == "" && $e_date == "" && $all_service == 'free') {

				$service = DB::select("SELECT count(*) as counts, RIGHT(YEAR(`service_date`),4) as date FROM tbl_services where (service_type='$all_service') and (done_status=1) group by YEAR(`service_date`) ORDER BY date ASC");
				$title_report = 'All Service';
				$date_report = 'Year';
				$title = 'Service';

				$servicereport = Service::where([['done_status', '=', 1], ['service_type', '=', $all_service]])->where([['soft_delete', 0]])->orderBy('id', 'desc')->get();
			} elseif ($s_date == "" && $e_date == "" && $all_service == 'paid') {

				$service = DB::select("SELECT count(*) as counts, RIGHT(YEAR(`service_date`),4) as date FROM tbl_services where (service_type='$all_service') and (done_status=1) group by YEAR(`service_date`) ORDER BY date ASC");
				$title_report = 'All Service';
				$date_report = 'Year';
				$title = 'Service';

				$servicereport = Service::where([['done_status', '=', 1], ['service_type', '=', $all_service]])->where([['soft_delete', 0]])->orderBy('id', 'desc')->get();
			} elseif ($s_date != "" && $e_date != "" && $all_service == 'all') {

				if ($select_customerId != "") {
					$service = DB::select("select count(*) as counts, CONCAT(MONTHNAME(`service_date`),'-',RIGHT(YEAR(`service_date`),4)) as date FROM tbl_services where  (service_date BETWEEN  '$s_date' AND '$e_date') and (done_status=1)  and (customer_id='$select_customerId') GROUP BY MONTH(service_date), YEAR(service_date)");


					$title_report = 'All Service';
					$date_report = 'Year';
					$title = 'Service';

					$servicereport = Service::where([['done_status', '=', 1], ['customer_id', '=', $select_customerId]])
						->where([['soft_delete', 0]])
						->whereBetween('service_date', array($s_date, $e_date))->orderBy('id', 'desc')->get();
				} else {
					$service = DB::select("select count(*) as counts, CONCAT(MONTHNAME(`service_date`),'-',RIGHT(YEAR(`service_date`),4)) as date FROM tbl_services where  (service_date BETWEEN  '$s_date' AND '$e_date') and (done_status=1) GROUP BY MONTH(service_date), YEAR(service_date)");

					$title_report = 'All Service';
					$date_report = 'Year';
					$title = 'Service';

					$servicereport = Service::where('done_status', '=', 1)
						->where([['soft_delete', 0]])
						->whereBetween('service_date', array($s_date, $e_date))->orderBy('id', 'desc')->get();
				}
			} elseif ($s_date != "" && $e_date != "" && $all_service == 'free') {
				if ($select_customerId != "") {
					$service = DB::select("select count(*) as counts, CONCAT(MONTHNAME(`service_date`),'-',RIGHT(YEAR(`service_date`),4)) as date FROM tbl_services where  (service_date BETWEEN  '$s_date' AND '$e_date') and (done_status=1) and (service_type='$all_service') and (customer_id='$select_customerId') GROUP BY MONTH(service_date), YEAR(service_date)");

					$title_report = 'All Service';
					$date_report = 'Year';
					$title = 'Service';

					$servicereport = Service::where([['done_status', '=', 1], ['customer_id', '=', $select_customerId]])
						->where('service_type', '=', $all_service)
						->where([['soft_delete', 0]])
						->whereBetween('service_date', array($s_date, $e_date))->orderBy('id', 'desc')->get();
				} else {

					$service = DB::select("select count(*) as counts, CONCAT(MONTHNAME(`service_date`),'-',RIGHT(YEAR(`service_date`),4)) as date FROM tbl_services where  (service_date BETWEEN  '$s_date' AND '$e_date') and (done_status=1) and (service_type='$all_service')GROUP BY MONTH(service_date), YEAR(service_date)");

					$title_report = 'All Service';
					$date_report = 'Year';
					$title = 'Service';

					$servicereport = Service::where('done_status', '=', 1)
						->where('service_type', '=', $all_service)
						->where([['soft_delete', 0]])
						->whereBetween('service_date', array($s_date, $e_date))->orderBy('id', 'desc')->get();
				}
			} elseif ($s_date != "" && $e_date != "" && $all_service == 'paid') {
				//Selected customer record return
				if ($select_customerId != "") {
					$service = DB::select("select count(*) as counts, CONCAT(MONTHNAME(`service_date`),'-',RIGHT(YEAR(`service_date`),4)) as date FROM tbl_services where  (service_date BETWEEN  '$s_date' AND '$e_date') and (done_status=1) and (service_type='$all_service') and (customer_id='$select_customerId') GROUP BY MONTH(service_date), YEAR(service_date)");

					$title_report = 'All Service';
					$date_report = 'Year';
					$title = 'Service';

					$servicereport = Service::where([['done_status', '=', 1], ['customer_id', '=', $select_customerId]])
						->where('service_type', '=', $all_service)
						->where([['soft_delete', 0]])
						->whereBetween('service_date', array($s_date, $e_date))->orderBy('id', 'desc')->get();
				} else {
					$service = DB::select("select count(*) as counts, CONCAT(MONTHNAME(`service_date`),'-',RIGHT(YEAR(`service_date`),4)) as date FROM tbl_services where  (service_date BETWEEN  '$s_date' AND '$e_date') and (done_status=1) and (service_type='$all_service')GROUP BY MONTH(service_date), YEAR(service_date)");

					$title_report = 'All Service';
					$date_report = 'Year';
					$title = 'Service';

					$servicereport = Service::where('done_status', '=', 1)
						->where('service_type', '=', $all_service)
						->where([['soft_delete', 0]])
						->whereBetween('service_date', array($s_date, $e_date))->orderBy('id', 'desc')->get();
				}
			}
		} elseif (getUsersRole(Auth::user()->role_id) == 'Customer') {
			if ($s_date == "" && $e_date == "" && $all_service == 'all') {
				$service = DB::select("SELECT count(*) as counts, RIGHT(YEAR(`service_date`),4) as date FROM tbl_services where (done_status=1) group by YEAR(`service_date`) ORDER BY date ASC");
				$title_report = 'All Service';
				$date_report = 'Year';
				$title = 'Service';

				$servicereport = Service::where('done_status', '=', 1)->orderBy('id', 'desc')->get();
			} elseif ($s_date == "" && $e_date == "" && $all_service == 'free') {

				$service = DB::select("SELECT count(*) as counts, RIGHT(YEAR(`service_date`),4) as date FROM tbl_services where (service_type='$all_service') and (done_status=1) group by YEAR(`service_date`) ORDER BY date ASC");
				$title_report = 'All Service';
				$date_report = 'Year';
				$title = 'Service';

				$servicereport = Service::where([['done_status', '=', 1], ['service_type', '=', $all_service]])->orderBy('id', 'desc')->get();
			} elseif ($s_date == "" && $e_date == "" && $all_service == 'paid') {

				$service = DB::select("SELECT count(*) as counts, RIGHT(YEAR(`service_date`),4) as date FROM tbl_services where (service_type='$all_service') and (done_status=1) group by YEAR(`service_date`) ORDER BY date ASC");
				$title_report = 'All Service';
				$date_report = 'Year';
				$title = 'Service';

				$servicereport = Service::where([['done_status', '=', 1], ['service_type', '=', $all_service]])->orderBy('id', 'desc')->get();
			} elseif ($s_date != "" && $e_date != "" && $all_service == 'all') {
				if ($select_customerId != "") {
					$service = DB::select("select count(*) as counts, CONCAT(MONTHNAME(`service_date`),'-',RIGHT(YEAR(`service_date`),4)) as date FROM tbl_services where  (service_date BETWEEN  '$e_date' AND '$s_date') and (done_status=1) and (customer_id='$select_customerId') GROUP BY MONTH(service_date), YEAR(service_date)");


					$title_report = 'All Service';
					$date_report = 'Year';
					$title = 'Service';

					$servicereport = Service::where([['done_status', '=', 1], ['customer_id', '=', $select_customerId]])
						->whereBetween('service_date', array($s_date, $e_date))->orderBy('id', 'desc')->get();
				} else {
					$service = DB::select("select count(*) as counts, CONCAT(MONTHNAME(`service_date`),'-',RIGHT(YEAR(`service_date`),4)) as date FROM tbl_services where  (service_date BETWEEN  '$s_date' AND '$e_date') and (done_status=1) GROUP BY MONTH(service_date), YEAR(service_date)");

					$title_report = 'All Service';
					$date_report = 'Year';
					$title = 'Service';

					$servicereport = Service::where('done_status', '=', 1)
						->whereBetween('service_date', array($s_date, $e_date))->orderBy('id', 'desc')->get();
				}
			} elseif ($s_date != "" && $e_date != "" && $all_service == 'free') {
				if ($select_customerId != "") {
					$service = DB::select("select count(*) as counts, CONCAT(MONTHNAME(`service_date`),'-',RIGHT(YEAR(`service_date`),4)) as date FROM tbl_services where  (service_date BETWEEN  '$s_date' AND '$e_date') and (done_status=1) and (service_type='$all_service') and (customer_id='$select_customerId') GROUP BY MONTH(service_date), YEAR(service_date)");

					$title_report = 'All Service';
					$date_report = 'Year';
					$title = 'Service';

					$servicereport = Service::where([['done_status', '=', 1], ['customer_id', '=', $select_customerId]])
						->where('service_type', '=', $all_service)
						->whereBetween('service_date', array($s_date, $e_date))->orderBy('id', 'desc')->get();
				} else {

					$service = DB::select("select count(*) as counts, CONCAT(MONTHNAME(`service_date`),'-',RIGHT(YEAR(`service_date`),4)) as date FROM tbl_services where  (service_date BETWEEN  '$s_date' AND '$e_date') and (done_status=1) and (service_type='$all_service')GROUP BY MONTH(service_date), YEAR(service_date)");

					$title_report = 'All Service';
					$date_report = 'Year';
					$title = 'Service';

					$servicereport = Service::where('done_status', '=', 1)
						->where('service_type', '=', $all_service)
						->whereBetween('service_date', array($s_date, $e_date))->orderBy('id', 'desc')->get();
				}
			} elseif ($s_date != "" && $e_date != "" && $all_service == 'paid') {
				if ($select_customerId != "") {
					$service = DB::select("select count(*) as counts, CONCAT(MONTHNAME(`service_date`),'-',RIGHT(YEAR(`service_date`),4)) as date FROM tbl_services where  (service_date BETWEEN  '$s_date' AND '$e_date') and (done_status=1) and (service_type='$all_service') and (customer_id='$select_customerId') GROUP BY MONTH(service_date), YEAR(service_date)");

					$title_report = 'All Service';
					$date_report = 'Year';
					$title = 'Service';

					$servicereport = Service::where([['done_status', '=', 1], ['customer_id', '=', $select_customerId]])
						->where('service_type', '=', $all_service)
						->whereBetween('service_date', array($s_date, $e_date))->orderBy('id', 'desc')->get();
				} else {
					$service = DB::select("select count(*) as counts, CONCAT(MONTHNAME(`service_date`),'-',RIGHT(YEAR(`service_date`),4)) as date FROM tbl_services where  (service_date BETWEEN  '$s_date' AND '$e_date') and (done_status=1) and (service_type='$all_service')GROUP BY MONTH(service_date), YEAR(service_date)");

					$title_report = 'All Service';
					$date_report = 'Year';
					$title = 'Service';

					$servicereport = Service::where('done_status', '=', 1)
						->where('service_type', '=', $all_service)
						->whereBetween('service_date', array($s_date, $e_date))->orderBy('id', 'desc')->get();
				}
			}
		} else {

			if ($s_date == "" && $e_date == "" && $all_service == 'all') {
				$service = DB::select("SELECT count(*) as counts, RIGHT(YEAR(`service_date`),4) as date FROM tbl_services where (done_status=1) group by YEAR(`service_date`) ORDER BY date ASC");
				$title_report = 'All Service';
				$date_report = 'Year';
				$title = 'Service';

				$servicereport = Service::where('done_status', '=', 1)->where([['soft_delete', 0], ['branch_id', $currentUser->branch_id]])->orderBy('id', 'desc')->get();
			} elseif ($s_date == "" && $e_date == "" && $all_service == 'free') {

				$service = DB::select("SELECT count(*) as counts, RIGHT(YEAR(`service_date`),4) as date FROM tbl_services where (service_type='$all_service') and (done_status=1) group by YEAR(`service_date`) ORDER BY date ASC");
				$title_report = 'All Service';
				$date_report = 'Year';
				$title = 'Service';

				$servicereport = Service::where([['done_status', '=', 1], ['service_type', '=', $all_service]])->where([['soft_delete', 0], ['branch_id', $currentUser->branch_id]])->orderBy('id', 'desc')->get();
			} elseif ($s_date == "" && $e_date == "" && $all_service == 'paid') {

				$service = DB::select("SELECT count(*) as counts, RIGHT(YEAR(`service_date`),4) as date FROM tbl_services where (service_type='$all_service') and (done_status=1) group by YEAR(`service_date`) ORDER BY date ASC");
				$title_report = 'All Service';
				$date_report = 'Year';
				$title = 'Service';

				$servicereport = Service::where([['done_status', '=', 1], ['service_type', '=', $all_service]])->where([['soft_delete', 0], ['branch_id', $currentUser->branch_id]])->orderBy('id', 'desc')->get();
			} elseif ($s_date != "" && $e_date != "" && $all_service == 'all') {
				//Selected customer record return
				if ($select_customerId != "") {
					$service = DB::select("select count(*) as counts, CONCAT(MONTHNAME(`service_date`),'-',RIGHT(YEAR(`service_date`),4)) as date FROM tbl_services where  (service_date BETWEEN  '$e_date' AND '$s_date') and (done_status=1) and (customer_id='$select_customerId') GROUP BY MONTH(service_date), YEAR(service_date)");


					$title_report = 'All Service';
					$date_report = 'Year';
					$title = 'Service';

					$servicereport = Service::where([['done_status', '=', 1], ['customer_id', '=', $select_customerId]])
						->where([['soft_delete', 0], ['branch_id', $currentUser->branch_id]])
						->whereBetween('service_date', array($s_date, $e_date))->orderBy('id', 'desc')->get();
				} else {
					$service = DB::select("select count(*) as counts, CONCAT(MONTHNAME(`service_date`),'-',RIGHT(YEAR(`service_date`),4)) as date FROM tbl_services where  (service_date BETWEEN  '$s_date' AND '$e_date') and (done_status=1) GROUP BY MONTH(service_date), YEAR(service_date)");

					$title_report = 'All Service';
					$date_report = 'Year';
					$title = 'Service';

					$servicereport = Service::where('done_status', '=', 1)
						->where([['soft_delete', 0], ['branch_id', $currentUser->branch_id]])
						->whereBetween('service_date', array($s_date, $e_date))->orderBy('id', 'desc')->get();
				}
			} elseif ($s_date != "" && $e_date != "" && $all_service == 'free') {
				//Selected customer record return
				if ($select_customerId != "") {
					$service = DB::select("select count(*) as counts, CONCAT(MONTHNAME(`service_date`),'-',RIGHT(YEAR(`service_date`),4)) as date FROM tbl_services where  (service_date BETWEEN  '$s_date' AND '$e_date') and (done_status=1) and (service_type='$all_service') and (customer_id='$select_customerId') GROUP BY MONTH(service_date), YEAR(service_date)");

					$title_report = 'All Service';
					$date_report = 'Year';
					$title = 'Service';

					$servicereport = Service::where([['done_status', '=', 1], ['customer_id', '=', $select_customerId]])
						->where('service_type', '=', $all_service)
						->where([['soft_delete', 0], ['branch_id', $currentUser->branch_id]])
						->whereBetween('service_date', array($s_date, $e_date))->orderBy('id', 'desc')->get();
				} else {

					$service = DB::select("select count(*) as counts, CONCAT(MONTHNAME(`service_date`),'-',RIGHT(YEAR(`service_date`),4)) as date FROM tbl_services where  (service_date BETWEEN  '$s_date' AND '$e_date') and (done_status=1) and (service_type='$all_service')GROUP BY MONTH(service_date), YEAR(service_date)");

					$title_report = 'All Service';
					$date_report = 'Year';
					$title = 'Service';

					$servicereport = Service::where('done_status', '=', 1)
						->where('service_type', '=', $all_service)
						->where([['soft_delete', 0], ['branch_id', $currentUser->branch_id]])
						->whereBetween('service_date', array($s_date, $e_date))->orderBy('id', 'desc')->get();
				}
			} elseif ($s_date != "" && $e_date != "" && $all_service == 'paid') {
				//Selected customer record return
				if ($select_customerId != "") {
					$service = DB::select("select count(*) as counts, CONCAT(MONTHNAME(`service_date`),'-',RIGHT(YEAR(`service_date`),4)) as date FROM tbl_services where  (service_date BETWEEN  '$s_date' AND '$e_date') and (done_status=1) and (service_type='$all_service') and (customer_id='$select_customerId') GROUP BY MONTH(service_date), YEAR(service_date)");

					$title_report = 'All Service';
					$date_report = 'Year';
					$title = 'Service';

					$servicereport = Service::where([['done_status', '=', 1], ['customer_id', '=', $select_customerId]])
						->where('service_type', '=', $all_service)
						->where([['soft_delete', 0], ['branch_id', $currentUser->branch_id]])
						->whereBetween('service_date', array($s_date, $e_date))->orderBy('id', 'desc')->get();
				} else {
					$service = DB::select("select count(*) as counts, CONCAT(MONTHNAME(`service_date`),'-',RIGHT(YEAR(`service_date`),4)) as date FROM tbl_services where  (service_date BETWEEN  '$s_date' AND '$e_date') and (done_status=1) and (service_type='$all_service')GROUP BY MONTH(service_date), YEAR(service_date)");

					$title_report = 'All Service';
					$date_report = 'Year';
					$title = 'Service';

					$servicereport = Service::where('done_status', '=', 1)
						->where('service_type', '=', $all_service)
						->where([['soft_delete', 0], ['branch_id', $currentUser->branch_id]])
						->whereBetween('service_date', array($s_date, $e_date))->orderBy('id', 'desc')->get();
				}
			}
		}
		$totalAmount = 0;
		// Loop through each service record and sum the amounts
		foreach ($servicereport as $s) {
			$paidAmount = getPaidAmount($s->job_no);
			$totalAmount += $paidAmount;
		}

		$customers = User::where([['role', '=', 'Customer'], ['soft_delete', 0]])->get();
		return view('report.service.list', compact('servicereport', 's_date', 'e_date', 'service', 'title_report', 'date_report', 'title', 'all_service', 'customers', 'select_customerId', 'totalAmount'));
	}

	//product list in report
	public function product()
	{
		$all_product = 'all';
		$all_item = 'item';
		$title_report = 'All Product';
		$date_report = 'Year';
		$title = 'Product';

		$product = DB::select("SELECT count(*) as counts, RIGHT(YEAR(`product_date`),4) as date FROM tbl_stock_records INNER JOIN tbl_products
						ON tbl_stock_records.product_id=tbl_products.id group by YEAR(`product_date`) ORDER BY product_date ASC");

		$currentUser = User::where([['soft_delete', 0], ['id', '=', Auth::User()->id]])->orderBy('id', 'DESC')->first();
		// $adminCurrentBranch = BranchSetting::where('id', '=', 1)->first();
		if (isAdmin(Auth::User()->role_id)) {
			$productname = Product::where([['soft_delete', 0]])->get();
			$productreport = DB::table('tbl_purchases')
				->JOIN('tbl_purchase_history_records', 'tbl_purchase_history_records.purchase_id', '=', 'tbl_purchases.id')
				->JOIN('tbl_products', 'tbl_products.id', '=', 'tbl_purchase_history_records.product_id')
				->GROUPBY('tbl_purchase_history_records.product_id')
				// ->where('tbl_products.branch_id', '=', $adminCurrentBranch->branch_id)
				->get();
		} elseif (getUsersRole(Auth::user()->role_id) == 'Customer') {
			$productname = Product::where('soft_delete', '=', 0)->get();
			$productreport = DB::SELECT("SELECT * FROM tbl_purchase_history_records JOIN tbl_products on tbl_products.id = tbl_purchase_history_records.product_id WHERE purchase_id IN (SELECT MAX(purchase_id) FROM tbl_purchase_history_records GROUP by product_id) GROUP by product_id");
		} else {
			$productname = Product::where([['soft_delete', 0], ['branch_id', $currentUser->branch_id]])->get();
			$productreport = DB::table('tbl_purchases')
				->JOIN('tbl_purchase_history_records', 'tbl_purchase_history_records.purchase_id', '=', 'tbl_purchases.id')
				->JOIN('tbl_products', 'tbl_products.id', '=', 'tbl_purchase_history_records.product_id')
				->GROUPBY('tbl_purchase_history_records.product_id')
				->where('tbl_products.branch_id', '=', $currentUser->branch_id)
				->get();
		}

		$Select_product = DB::table('tbl_product_types')->where('soft_delete', '=', 0)->get()->toArray();

		return view('report.product.list', compact('all_product', 'all_item', 'Select_product', 'productreport', 'title_report', 'date_report', 'title', 'product', 'productname'));
	}


	// product type
	public function producttype(Request $request)
	{
		$id = $request->m_id;

		$currentUser = User::where([['soft_delete', 0], ['id', '=', Auth::User()->id]])->orderBy('id', 'DESC')->first();
		$adminCurrentBranch = BranchSetting::where('id', '=', 1)->first();
		if (isAdmin(Auth::User()->role_id)) {
			if ($id == 'all') {
				$all_item = "item";
?>
				<option value="item" <?php if ($all_item == 'item') {
											echo 'selected';
										} ?>>Items</option>
			<?php
				$tbl_products = DB::table('tbl_products')->where([['soft_delete', 0], ['branch_id', $adminCurrentBranch->branch_id]])->get()->toArray();
			} else {
				$all_item = "item";
			?>
				<option value="item" <?php if ($all_item == 'item') {
											echo 'selected';
										} ?>>Items</option>
			<?php
				$tbl_products = DB::table('tbl_products')->where('product_type_id', '=', $id)->where([['soft_delete', 0], ['branch_id', $adminCurrentBranch->branch_id]])->get()->toArray();
			}
		} elseif (getUsersRole(Auth::user()->role_id) == 'Customer') {

			if ($id == 'all') {
				$all_item = "item";
			?>
				<option value="item" <?php if ($all_item == 'item') {
											echo 'selected';
										} ?>>Items</option>
			<?php
				$tbl_products = DB::table('tbl_products')->get()->toArray();
			} else {
				$all_item = "item";
			?>
				<option value="item" <?php if ($all_item == 'item') {
											echo 'selected';
										} ?>>Items</option>
			<?php
				$tbl_products = DB::table('tbl_products')->where('product_type_id', '=', $id)->get()->toArray();
			}
		} else {
			if ($id == 'all') {
				$all_item = "item";
			?>
				<option value="item" <?php if ($all_item == 'item') {
											echo 'selected';
										} ?>>Items</option>
			<?php
				$tbl_products = DB::table('tbl_products')->where([['soft_delete', 0], ['branch_id', $currentUser->branch_id]])->get()->toArray();
			} else {
				$all_item = "item";
			?>
				<option value="item" <?php if ($all_item == 'item') {
											echo 'selected';
										} ?>>Items</option>
			<?php
				$tbl_products = DB::table('tbl_products')->where('product_type_id', '=', $id)->where([['soft_delete', 0], ['branch_id', $currentUser->branch_id]])->get()->toArray();
			}
		}

		if (!empty($tbl_products)) {
			foreach ($tbl_products as $tbl_productss) { ?>
				<option value="<?php echo  $tbl_productss->id; ?>"><?php echo $tbl_productss->name; ?></option>
<?php }
		}
	}


	//product list based on date in report
	public function record_product(Request $request)
	{
		$all_product = $request->s_product;
		$all_item = $request->product_name;

		$currentUser = User::where([['soft_delete', '=', 0], ['id', '=', Auth::User()->id]])->orderBy('id', 'DESC')->first();
		// $adminCurrentBranch = BranchSetting::where('id', '=', 1)->first();
		if (isAdmin(Auth::User()->role_id)) {
			if ($all_product == 'all' && $all_item == 'item') {
				$product = DB::select("SELECT count(*) as counts, RIGHT(YEAR(`product_date`),4) as date FROM tbl_stock_records INNER JOIN tbl_products
							ON tbl_stock_records.product_id=tbl_products.id group by YEAR(`product_date`) ORDER BY product_date ASC");
				$title_report = 'All Product';
				$date_report = 'Year';
				$title = 'Product';

				$productreport = DB::table('tbl_purchases')
					->JOIN('tbl_purchase_history_records', 'tbl_purchase_history_records.purchase_id', '=', 'tbl_purchases.id')
					->JOIN('tbl_products', 'tbl_products.id', '=', 'tbl_purchase_history_records.product_id')
					->GROUPBY('tbl_purchase_history_records.product_id')
					// ->where('tbl_products.branch_id', '=', $adminCurrentBranch->branch_id)
					->get();

				$productname = Product::where([['soft_delete', 0]])->get();
			} else if ($all_product != 'all' && $all_item == 'item') {
				$product = DB::select("SELECT count(*) as counts, RIGHT(YEAR(`product_date`),4) as date FROM tbl_products where product_type_id='$all_product' group by YEAR(`product_date`) ORDER BY product_date ASC");
				$title_report = 'All Product';
				$date_report = 'Year';
				$title = 'Product';

				$productreport = DB::table('tbl_purchases')
					->JOIN('tbl_purchase_history_records', 'tbl_purchase_history_records.purchase_id', '=', 'tbl_purchases.id')
					->JOIN('tbl_products', 'tbl_products.id', '=', 'tbl_purchase_history_records.product_id')
					->GROUPBY('tbl_purchase_history_records.product_id')
					->where('product_type_id', '=', $all_product)
					// ->where('tbl_products.branch_id', '=', $adminCurrentBranch->branch_id)
					->get();

				$productname = Product::where('tbl_products.product_type_id', '=', $all_product)->where([['soft_delete', 0]])->get();
			} else if ($all_product == 'all' && $all_item != 'item') {
				$product = DB::select("SELECT count(*) as counts, RIGHT(YEAR(`product_date`),4) as date FROM tbl_products where name='$all_item' group by YEAR(`product_date`) ORDER BY product_date ASC");
				$title_report = 'All Product';
				$date_report = 'Year';
				$title = 'Product';

				$productname = Product::where([['soft_delete', 0]])->get();

				$productreport = DB::table('tbl_purchases')
					->JOIN('tbl_purchase_history_records', 'tbl_purchase_history_records.purchase_id', '=', 'tbl_purchases.id')
					->JOIN('tbl_products', 'tbl_products.id', '=', 'tbl_purchase_history_records.product_id')
					->orderBy('tbl_purchase_history_records.purchase_id', 'desc')
					->GROUPBY('tbl_purchase_history_records.product_id')
					->where('tbl_products.id', '=', $all_item)
					// ->where('tbl_products.branch_id', '=', $adminCurrentBranch->branch_id)
					->get();
			} else if ($all_product != 'all' && $all_item != 'item') {
				$product = DB::select("SELECT count(*) as counts, RIGHT(YEAR(`product_date`),4) as date FROM tbl_products where (product_type_id='$all_product') and (name='$all_item') group by YEAR(`product_date`) ORDER BY product_date ASC");
				$title_report = 'All Product';
				$date_report = 'Year';
				$title = 'Product';

				$productreport = DB::table('tbl_purchases')
					->JOIN('tbl_purchase_history_records', 'tbl_purchase_history_records.purchase_id', '=', 'tbl_purchases.id')
					->JOIN('tbl_products', 'tbl_products.id', '=', 'tbl_purchase_history_records.product_id')
					->GROUPBY('tbl_purchase_history_records.product_id')
					->where([['tbl_products.id', '=', $all_item], ['tbl_products.product_type_id', '=', $all_product]])
					// ->where('tbl_products.branch_id', '=', $adminCurrentBranch->branch_id)
					->get();

				$productname = Product::where('tbl_products.product_type_id', '=', $all_product)->where([['soft_delete', 0]])->get();
			}
		} elseif (getUsersRole(Auth::user()->role_id) == 'Customer') {
			if ($all_product == 'all' && $all_item == 'item') {
				$product = DB::select("SELECT count(*) as counts, RIGHT(YEAR(`product_date`),4) as date FROM tbl_stock_records INNER JOIN tbl_products
							ON tbl_stock_records.product_id=tbl_products.id group by YEAR(`product_date`) ORDER BY product_date ASC");
				$title_report = 'All Product';
				$date_report = 'Year';
				$title = 'Product';

				$productreport = DB::table('tbl_purchases')
					->JOIN('tbl_purchase_history_records', 'tbl_purchase_history_records.purchase_id', '=', 'tbl_purchases.id')
					->JOIN('tbl_products', 'tbl_products.id', '=', 'tbl_purchase_history_records.product_id')
					->GROUPBY('tbl_purchase_history_records.product_id')
					->get();
				$productname = Product::get();
			} else if ($all_product != 'all' && $all_item == 'item') {
				$product = DB::select("SELECT count(*) as counts, RIGHT(YEAR(`product_date`),4) as date FROM tbl_products where product_type_id='$all_product' group by YEAR(`product_date`) ORDER BY product_date ASC");
				$title_report = 'All Product';
				$date_report = 'Year';
				$title = 'Product';

				$productreport = DB::table('tbl_purchases')
					->JOIN('tbl_purchase_history_records', 'tbl_purchase_history_records.purchase_id', '=', 'tbl_purchases.id')
					->JOIN('tbl_products', 'tbl_products.id', '=', 'tbl_purchase_history_records.product_id')
					->GROUPBY('tbl_purchase_history_records.product_id')
					->where('product_type_id', '=', $all_product)
					->get();
				$productname = Product::where('tbl_products.product_type_id', '=', $all_product)->get();
			} else if ($all_product == 'all' && $all_item != 'item') {
				$product = DB::select("SELECT count(*) as counts, RIGHT(YEAR(`product_date`),4) as date FROM tbl_products where name='$all_item' group by YEAR(`product_date`) ORDER BY product_date ASC");
				$title_report = 'All Product';
				$date_report = 'Year';
				$title = 'Product';

				$productname = Product::get();

				$productreport = DB::table('tbl_purchases')
					->JOIN('tbl_purchase_history_records', 'tbl_purchase_history_records.purchase_id', '=', 'tbl_purchases.id')
					->JOIN('tbl_products', 'tbl_products.id', '=', 'tbl_purchase_history_records.product_id')
					->orderBy('tbl_purchase_history_records.purchase_id', 'desc')
					->GROUPBY('tbl_purchase_history_records.product_id')
					->where('tbl_products.id', '=', $all_item)
					->get();
			} else if ($all_product != 'all' && $all_item != 'item') {
				$product = DB::select("SELECT count(*) as counts, RIGHT(YEAR(`product_date`),4) as date FROM tbl_products where (product_type_id='$all_product') and (name='$all_item') group by YEAR(`product_date`) ORDER BY product_date ASC");
				$title_report = 'All Product';
				$date_report = 'Year';
				$title = 'Product';

				$productreport = DB::table('tbl_purchases')
					->JOIN('tbl_purchase_history_records', 'tbl_purchase_history_records.purchase_id', '=', 'tbl_purchases.id')
					->JOIN('tbl_products', 'tbl_products.id', '=', 'tbl_purchase_history_records.product_id')
					->GROUPBY('tbl_purchase_history_records.product_id')
					->where([['tbl_products.id', '=', $all_item], ['tbl_products.product_type_id', '=', $all_product]])
					->orderBy('tbl_purchase_history_records.purchase_id', 'desc')
					->get();

				$productname = Product::where('tbl_products.product_type_id', '=', $all_product)->get();
			}
		} else {
			$product = Product::where([['soft_delete', 0], ['branch_id', $currentUser->branch_id]])->orderBy('id', 'DESC')->get();
			if ($all_product == 'all' && $all_item == 'item') {
				$product = DB::select("SELECT count(*) as counts, RIGHT(YEAR(`product_date`),4) as date FROM tbl_stock_records INNER JOIN tbl_products
							ON tbl_stock_records.product_id=tbl_products.id group by YEAR(`product_date`) ORDER BY product_date ASC");
				$title_report = 'All Product';
				$date_report = 'Year';
				$title = 'Product';

				$productreport = DB::table('tbl_purchases')
					->JOIN('tbl_purchase_history_records', 'tbl_purchase_history_records.purchase_id', '=', 'tbl_purchases.id')
					->JOIN('tbl_products', 'tbl_products.id', '=', 'tbl_purchase_history_records.product_id')
					->GROUPBY('tbl_purchase_history_records.product_id')
					->where('tbl_products.branch_id', '=', $currentUser->branch_id)
					->get();

				$productname = Product::where([['soft_delete', 0], ['branch_id', $currentUser->branch_id]])->get();
			} else if ($all_product != 'all' && $all_item == 'item') {
				$product = DB::select("SELECT count(*) as counts, RIGHT(YEAR(`product_date`),4) as date FROM tbl_products where product_type_id='$all_product' group by YEAR(`product_date`) ORDER BY product_date ASC");
				$title_report = 'All Product';
				$date_report = 'Year';
				$title = 'Product';

				$productreport = DB::table('tbl_purchases')
					->JOIN('tbl_purchase_history_records', 'tbl_purchase_history_records.purchase_id', '=', 'tbl_purchases.id')
					->JOIN('tbl_products', 'tbl_products.id', '=', 'tbl_purchase_history_records.product_id')
					->GROUPBY('tbl_purchase_history_records.product_id')
					->where('product_type_id', '=', $all_product)
					->where('tbl_products.branch_id', '=', $currentUser->branch_id)
					->get();

				$productname = Product::where('tbl_products.product_type_id', '=', $all_product)->where([['soft_delete', 0], ['branch_id', $currentUser->branch_id]])->get();
			} else if ($all_product == 'all' && $all_item != 'item') {
				$product = DB::select("SELECT count(*) as counts, RIGHT(YEAR(`product_date`),4) as date FROM tbl_products where name='$all_item' group by YEAR(`product_date`) ORDER BY product_date ASC");
				$title_report = 'All Product';
				$date_report = 'Year';
				$title = 'Product';

				$productname = Product::where([['soft_delete', 0], ['branch_id', $currentUser->branch_id]])->get();

				$productreport = DB::table('tbl_purchases')
					->JOIN('tbl_purchase_history_records', 'tbl_purchase_history_records.purchase_id', '=', 'tbl_purchases.id')
					->JOIN('tbl_products', 'tbl_products.id', '=', 'tbl_purchase_history_records.product_id')
					->orderBy('tbl_purchase_history_records.purchase_id', 'desc')
					->GROUPBY('tbl_purchase_history_records.product_id')
					->where('tbl_products.id', '=', $all_item)
					->where('tbl_products.branch_id', '=', $currentUser->branch_id)
					->get();
			} else if ($all_product != 'all' && $all_item != 'item') {
				$product = DB::select("SELECT count(*) as counts, RIGHT(YEAR(`product_date`),4) as date FROM tbl_products where (product_type_id='$all_product') and (name='$all_item') group by YEAR(`product_date`) ORDER BY product_date ASC");
				$title_report = 'All Product';
				$date_report = 'Year';
				$title = 'Product';

				$productreport = DB::table('tbl_purchases')
					->JOIN('tbl_purchase_history_records', 'tbl_purchase_history_records.purchase_id', '=', 'tbl_purchases.id')
					->JOIN('tbl_products', 'tbl_products.id', '=', 'tbl_purchase_history_records.product_id')
					->GROUPBY('tbl_purchase_history_records.product_id')
					->where([['tbl_products.id', '=', $all_item], ['tbl_products.product_type_id', '=', $all_product]])
					->where('tbl_products.branch_id', '=', $currentUser->branch_id)
					->get();

				$productname = Product::where('tbl_products.product_type_id', '=', $all_product)->where([['soft_delete', 0], ['branch_id', $currentUser->branch_id]])->get();
			}
		}

		$Select_product = DB::table('tbl_product_types')->where('soft_delete', '=', 0)->get();
		return view('report.product.list', compact('all_product', 'all_item', 'Select_product', 'productreport', 'product', 'title_report', 'date_report', 'title', 'productname'));
	}

	// array sort by column
	public function array_sort_by_column(&$array, $column, $direction = SORT_ASC)
	{
		$reference_array = array();
		foreach ($array as $key => $row) {
			$reference_array[$key] = $row->date;
		}
		array_multisort($reference_array, $direction, $array);
	}


	//productuses list report
	public function productuses()
	{
		$id = 1;
		$totalstock1 = DB::table('tbl_purchase_history_records')
			->JOIN('tbl_purchases', 'tbl_purchases.id', '=', 'tbl_purchase_history_records.purchase_id')
			->where('tbl_purchase_history_records.product_id', '=', $id)
			->select('tbl_purchases.date as date', 'tbl_purchases.supplier_id', 'tbl_purchase_history_records.qty', 'tbl_purchases.purchase_no')
			->get()->toArray();

		$cellstock1 = DB::table('tbl_service_pros')->JOIN('tbl_services', 'tbl_services.id', '=', 'tbl_service_pros.service_id')
			->where('product_id', '=', $id)
			->select('tbl_services.customer_id', 'tbl_services.assign_to', 'tbl_services.job_no', 'tbl_services.service_date as date', 'tbl_service_pros.quantity')
			->get()->toArray();
		$arr = array_merge($totalstock1, $cellstock1);
		$common_array = $this->array_sort_by_column($arr, 'date');

		$s_date = '';
		$e_date = '';
		$all_product = 'all';
		$all_item = 'item';

		$currentUser = User::where([['soft_delete', 0], ['id', '=', Auth::User()->id]])->orderBy('id', 'DESC')->first();
		// $adminCurrentBranch = BranchSetting::where('id', '=', 1)->first();
		if (isAdmin(Auth::User()->role_id)) {
			$productname = Product::where([['soft_delete', 0]])->get();

			$productreport = DB::table('tbl_purchases')
				->JOIN('tbl_purchase_history_records', 'tbl_purchase_history_records.purchase_id', '=', 'tbl_purchases.id')
				->JOIN('tbl_products', 'tbl_products.id', '=', 'tbl_purchase_history_records.product_id')
				->GROUPBY('tbl_purchase_history_records.product_id')
				// ->where('tbl_products.branch_id', '=', $adminCurrentBranch->branch_id)
				->get();
		} elseif (getUsersRole(Auth::user()->role_id) == 'Customer') {
			$productreport = DB::SELECT("SELECT * FROM tbl_purchase_history_records JOIN tbl_products on tbl_products.id = tbl_purchase_history_records.product_id WHERE purchase_id IN (SELECT MAX(purchase_id) FROM tbl_purchase_history_records GROUP by product_id) GROUP by product_id");

			$productname = Product::get();
		} else {
			$productname = Product::where([['soft_delete', 0], ['branch_id', $currentUser->branch_id]])->get();

			$productreport = DB::table('tbl_purchases')
				->JOIN('tbl_purchase_history_records', 'tbl_purchase_history_records.purchase_id', '=', 'tbl_purchases.id')
				->JOIN('tbl_products', 'tbl_products.id', '=', 'tbl_purchase_history_records.product_id')
				->GROUPBY('tbl_purchase_history_records.product_id')
				->where('tbl_products.branch_id', '=', $currentUser->branch_id)
				->get();
		}

		// $Select_product = DB::table('tbl_product_types')->get()->toArray();
		$Select_product = DB::table('tbl_product_types')->where('soft_delete', '=', 0)->get()->toArray();


		return view('report.product.product_uses', compact('all_product', 'all_item', 'Select_product', 'productname', 'productreport', 's_date', 'e_date'));
	}

	//uses_product list record in report
	public function uses_product(Request $request)
	{
		if (getDateFormat() == 'm-d-Y') {
			$s_date = date('Y-m-d', strtotime(str_replace('-', '/', $request->start_date)));
			$e_date = date('Y-m-d', strtotime(str_replace('-', '/', $request->end_date)));
		} else {
			$s_date = date('Y-m-d', strtotime($request->start_date));
			$e_date = date('Y-m-d', strtotime($request->end_date));
		}

		$all_product = $request->m_product;
		$all_item = $request->product_name;

		$currentUser = User::where([['soft_delete', 0], ['id', '=', Auth::User()->id]])->orderBy('id', 'DESC')->first();
		// $adminCurrentBranch = BranchSetting::where('id', '=', 1)->first();

		if (isAdmin(Auth::User()->role_id)) {
			if ($s_date != "" && $e_date != "" && $all_product == 'all' && $all_item == 'item') {
				$productreport = DB::table('tbl_purchase_history_records')
					->JOIN('tbl_purchases', 'tbl_purchases.id', '=', 'tbl_purchase_history_records.purchase_id')
					->JOIN('tbl_products', 'tbl_products.id', '=', 'tbl_purchase_history_records.product_id')
					->whereBetween('date', [$s_date, $e_date])
					// ->where('tbl_products.branch_id', '=', $adminCurrentBranch->branch_id)
					->GROUPBY('tbl_purchase_history_records.product_id')
					->get()->toArray();

				$productname = Product::where([['soft_delete', 0]])->get();
			} elseif ($s_date != "" && $e_date != "" && $all_product == 'all' && $all_item != 'item') {
				$productreport = DB::table('tbl_purchase_history_records')
					->JOIN('tbl_purchases', 'tbl_purchases.id', '=', 'tbl_purchase_history_records.purchase_id')
					->JOIN('tbl_products', 'tbl_products.id', '=', 'tbl_purchase_history_records.product_id')
					->whereBetween('date', [$s_date, $e_date])
					->where('tbl_products.id', '=', $all_item)
					// ->where('tbl_products.branch_id', '=', $adminCurrentBranch->branch_id)
					->GROUPBY('tbl_purchase_history_records.product_id')
					->get()->toArray();

				$productname = Product::where([['soft_delete', 0]])->get();
			} elseif ($s_date != "" && $e_date != "" && $all_product != 'all' && $all_item != 'item') {
				$productreport = DB::table('tbl_purchase_history_records')
					->JOIN('tbl_purchases', 'tbl_purchases.id', '=', 'tbl_purchase_history_records.purchase_id')
					->JOIN('tbl_products', 'tbl_products.id', '=', 'tbl_purchase_history_records.product_id')
					->whereBetween('date', [$s_date, $e_date])
					->where([['tbl_products.id', '=', $all_item], ['tbl_products.product_type_id', '=', $all_product]])
					// ->where('tbl_products.branch_id', '=', $adminCurrentBranch->branch_id)
					->GROUPBY('tbl_purchase_history_records.product_id')
					->get()->toArray();

				$productname = Product::where([['product_type_id', '=', $all_product], ['soft_delete', 0]])->get();
			} elseif ($s_date != "" && $e_date != "" && $all_product != 'all' && $all_item == 'item') {
				$productreport = DB::table('tbl_purchase_history_records')
					->JOIN('tbl_purchases', 'tbl_purchases.id', '=', 'tbl_purchase_history_records.purchase_id')
					->JOIN('tbl_products', 'tbl_products.id', '=', 'tbl_purchase_history_records.product_id')
					->whereBetween('date', [$s_date, $e_date])
					->where('tbl_products.product_type_id', '=', $all_product)
					// ->where('tbl_products.branch_id', '=', $adminCurrentBranch->branch_id)
					->GROUPBY('tbl_purchase_history_records.product_id')
					->get()->toArray();

				$productname = Product::where([['product_type_id', '=', $all_product], ['soft_delete', 0]])->get();
			}
		} elseif (getUsersRole(Auth::user()->role_id) == 'Customer') {
			$product = Product::where('soft_delete', '=', 0)->orderBy('id', 'DESC')->get();

			if ($s_date != "" && $e_date != "" && $all_product == 'all' && $all_item == 'item') {
				$productreport = DB::table('tbl_purchase_history_records')->JOIN('tbl_purchases', 'tbl_purchases.id', '=', 'tbl_purchase_history_records.purchase_id')
					->JOIN('tbl_products', 'tbl_products.id', '=', 'tbl_purchase_history_records.product_id')
					->whereBetween('date', [$s_date, $e_date])
					->GROUPBY('tbl_purchase_history_records.product_id')
					->get()->toArray();

				$productname = Product::get();
			} elseif ($s_date != "" && $e_date != "" && $all_product == 'all' && $all_item != 'item') {
				$productreport = DB::table('tbl_purchase_history_records')->JOIN('tbl_purchases', 'tbl_purchases.id', '=', 'tbl_purchase_history_records.purchase_id')
					->JOIN('tbl_products', 'tbl_products.id', '=', 'tbl_purchase_history_records.product_id')
					->whereBetween('date', [$s_date, $e_date])
					->where('tbl_products.id', '=', $all_item)
					->GROUPBY('tbl_purchase_history_records.product_id')
					->get()->toArray();

				$productname = Product::get();
			} elseif ($s_date != "" && $e_date != "" && $all_product != 'all' && $all_item != 'item') {
				$productreport = DB::table('tbl_purchase_history_records')->JOIN('tbl_purchases', 'tbl_purchases.id', '=', 'tbl_purchase_history_records.purchase_id')
					->JOIN('tbl_products', 'tbl_products.id', '=', 'tbl_purchase_history_records.product_id')
					->whereBetween('date', [$s_date, $e_date])
					->where([['tbl_products.id', '=', $all_item], ['tbl_products.product_type_id', '=', $all_product]])
					->GROUPBY('tbl_purchase_history_records.product_id')
					->get()->toArray();

				$productname = Product::where('product_type_id', '=', $all_product)->get();
			} elseif ($s_date != "" && $e_date != "" && $all_product != 'all' && $all_item == 'item') {
				$productreport = DB::table('tbl_purchase_history_records')->JOIN('tbl_purchases', 'tbl_purchases.id', '=', 'tbl_purchase_history_records.purchase_id')
					->JOIN('tbl_products', 'tbl_products.id', '=', 'tbl_purchase_history_records.product_id')
					->whereBetween('date', [$s_date, $e_date])
					->where('tbl_products.product_type_id', '=', $all_product)
					->GROUPBY('tbl_purchase_history_records.product_id')
					->get()->toArray();

				$productname = Product::where('product_type_id', '=', $all_product)->get();
			}
		} else {
			if ($s_date != "" && $e_date != "" && $all_product == 'all' && $all_item == 'item') {
				$productreport = DB::table('tbl_purchase_history_records')
					->JOIN('tbl_purchases', 'tbl_purchases.id', '=', 'tbl_purchase_history_records.purchase_id')
					->JOIN('tbl_products', 'tbl_products.id', '=', 'tbl_purchase_history_records.product_id')
					->whereBetween('date', [$s_date, $e_date])
					->where('tbl_products.branch_id', '=', $currentUser->branch_id)
					->GROUPBY('tbl_purchase_history_records.product_id')
					->get()->toArray();

				$productname = Product::where([['soft_delete', 0], ['branch_id', $currentUser->branch_id]])->get();
			} elseif ($s_date != "" && $e_date != "" && $all_product == 'all' && $all_item != 'item') {
				$productreport = DB::table('tbl_purchase_history_records')
					->JOIN('tbl_purchases', 'tbl_purchases.id', '=', 'tbl_purchase_history_records.purchase_id')
					->JOIN('tbl_products', 'tbl_products.id', '=', 'tbl_purchase_history_records.product_id')
					->whereBetween('date', [$s_date, $e_date])
					->where('tbl_products.id', '=', $all_item)
					->where('tbl_products.branch_id', '=', $currentUser->branch_id)
					->GROUPBY('tbl_purchase_history_records.product_id')
					->get()->toArray();

				$productname = Product::where([['soft_delete', 0], ['branch_id', $currentUser->branch_id]])->get();
			} elseif ($s_date != "" && $e_date != "" && $all_product != 'all' && $all_item != 'item') {
				$productreport = DB::table('tbl_purchase_history_records')
					->JOIN('tbl_purchases', 'tbl_purchases.id', '=', 'tbl_purchase_history_records.purchase_id')
					->JOIN('tbl_products', 'tbl_products.id', '=', 'tbl_purchase_history_records.product_id')
					->whereBetween('date', [$s_date, $e_date])
					->where([['tbl_products.id', '=', $all_item], ['tbl_products.product_type_id', '=', $all_product]])
					->where('tbl_products.branch_id', '=', $currentUser->branch_id)
					->GROUPBY('tbl_purchase_history_records.product_id')
					->get()->toArray();

				$productname = Product::where([['product_type_id', '=', $all_product], ['soft_delete', 0], ['branch_id', $currentUser->branch_id]])->get();
			} elseif ($s_date != "" && $e_date != "" && $all_product != 'all' && $all_item == 'item') {
				$productreport = DB::table('tbl_purchase_history_records')
					->JOIN('tbl_purchases', 'tbl_purchases.id', '=', 'tbl_purchase_history_records.purchase_id')
					->JOIN('tbl_products', 'tbl_products.id', '=', 'tbl_purchase_history_records.product_id')
					->whereBetween('date', [$s_date, $e_date])
					->where('tbl_products.product_type_id', '=', $all_product)
					->where('tbl_products.branch_id', '=', $currentUser->branch_id)
					->GROUPBY('tbl_purchase_history_records.product_id')
					->get()->toArray();

				$productname = Product::where([['product_type_id', '=', $all_product], ['soft_delete', 0], ['branch_id', $currentUser->branch_id]])->get();
			}
		}

		$Select_product = DB::table('tbl_product_types')->where('soft_delete', '=', 0)->get()->toArray();

		return view('report.product.product_uses', compact('all_product', 'all_item', 'Select_product', 'productname', 'productreport', 's_date', 'e_date'));
	}

	// product model view 
	public function modalview(Request $request)
	{
		$id = $request->productid;
		$s_date = $request->s_date;
		$e_date = $request->e_date;

		if ($s_date == '' && $e_date == '') {
			$totalstock1 = DB::table('tbl_purchase_history_records')
				->JOIN('tbl_purchases', 'tbl_purchases.id', '=', 'tbl_purchase_history_records.purchase_id')
				->where('tbl_purchase_history_records.product_id', '=', $id)
				->orderby('tbl_purchases.date', 'ASC')
				->select('tbl_purchases.date as date', 'tbl_purchases.supplier_id', 'tbl_purchase_history_records.qty', 'tbl_purchases.purchase_no', 'tbl_purchases.id')
				->get()->toArray();

			$cellstock1 = DB::table('tbl_service_pros')->JOIN('tbl_services', 'tbl_services.id', '=', 'tbl_service_pros.service_id')
				->where('product_id', '=', $id)
				->orderby('tbl_services.service_date', 'ASC')
				->select('tbl_services.customer_id', 'tbl_services.assign_to', 'tbl_services.job_no', 'tbl_services.service_date as date', 'tbl_service_pros.quantity', 'tbl_services.id')
				->get()->toArray();
			$totalstock = array_merge($totalstock1, $cellstock1);
			$common_array = $this->array_sort_by_column($totalstock, 'date');
		} else {
			$totalstock1 = DB::table('tbl_purchase_history_records')
				->JOIN('tbl_purchases', 'tbl_purchases.id', '=', 'tbl_purchase_history_records.purchase_id')
				->where('tbl_purchase_history_records.product_id', '=', $id)
				->whereBetween('tbl_purchases.date', [$s_date, $e_date])
				->orderby('tbl_purchases.date', 'ASC')
				->select('tbl_purchases.date as date', 'tbl_purchases.supplier_id', 'tbl_purchase_history_records.qty', 'tbl_purchases.purchase_no', 'tbl_purchases.id')
				->get()->toArray();

			$cellstock1 = DB::table('tbl_service_pros')->JOIN('tbl_services', 'tbl_services.id', '=', 'tbl_service_pros.service_id')
				->where('product_id', '=', $id)
				->whereBetween('tbl_services.service_date', [$s_date, $e_date])
				->orderby('tbl_services.service_date', 'ASC')
				->select('tbl_services.customer_id', 'tbl_services.assign_to', 'tbl_services.job_no', 'tbl_services.service_date as date', 'tbl_service_pros.quantity', 'tbl_services.id')
				->get()->toArray();

			$totalstock = array_merge($totalstock1, $cellstock1);
			$common_array = $this->array_sort_by_column($totalstock, 'date');
		}
		$html = view('report.product.model')->with(compact('totalstock', 'id'))->render();
		return response()->json(['success' => true, 'html' => $html]);
	}

	public function modalviewPart(Request $request)
	{
		$id = $request->productid;
		$s_date = $request->s_date;
		$e_date = $request->e_date;

		if ($s_date == '' && $e_date == '') {
			$totalstock1 = DB::table('tbl_purchase_history_records')
				->JOIN('tbl_purchases', 'tbl_purchases.id', '=', 'tbl_purchase_history_records.purchase_id')
				->where('tbl_purchase_history_records.product_id', '=', $id)
				->orderby('tbl_purchases.date', 'ASC')
				->select('tbl_purchases.date as date', 'tbl_purchases.supplier_id as salesmanname', 'tbl_purchase_history_records.qty', 'tbl_purchases.purchase_no', 'tbl_purchases.id', 'tbl_purchases.supplier_id')
				->get()->toArray();

			$cellstock3 = DB::table('tbl_sale_parts')
				->where('product_id', '=', $id)
				->get()->toArray();

			$cellstock2 = DB::table('tbl_service_pros')->JOIN('tbl_services', 'tbl_services.id', '=', 'tbl_service_pros.service_id')
				->where('product_id', '=', $id)
				->orderby('tbl_services.service_date', 'ASC')
				->select('tbl_services.customer_id', 'tbl_services.assign_to as salesmanname', 'tbl_services.job_no', 'tbl_services.service_date as date', 'tbl_service_pros.quantity', 'tbl_services.id')
				->get()->toArray();

			$totalstock = array_merge($totalstock1, $cellstock3, $cellstock2);

			$common_array = $this->array_sort_by_column($totalstock, 'date');
		} else {
			$totalstock1 = DB::table('tbl_purchase_history_records')->JOIN('tbl_purchases', 'tbl_purchases.id', '=', 'tbl_purchase_history_records.purchase_id')->where('tbl_purchase_history_records.product_id', '=', $id)->whereBetween('tbl_purchases.date', [$s_date, $e_date])->orderby('tbl_purchases.date', 'ASC')->select('tbl_purchases.date as date', 'tbl_purchases.supplier_id as salesmanname', 'tbl_purchase_history_records.qty', 'tbl_purchases.purchase_no', 'tbl_purchases.id', 'tbl_purchases.supplier_id')->get()->toArray();

			$cellstock3 = DB::table('tbl_sale_parts')->where('product_id', '=', $id)->whereBetween('date', [$s_date, $e_date])->get()->toArray();

			$cellstock2 = DB::table('tbl_service_pros')->JOIN('tbl_services', 'tbl_services.id', '=', 'tbl_service_pros.service_id')
				->where('product_id', '=', $id)
				->whereBetween('tbl_services.service_date', [$s_date, $e_date])
				->orderby('tbl_services.service_date', 'ASC')
				->select('tbl_services.customer_id', 'tbl_services.assign_to as salesmanname', 'tbl_services.job_no', 'tbl_services.service_date as date', 'tbl_service_pros.quantity', 'tbl_services.id')
				->get()->toArray();

			$totalstock = array_merge($totalstock1, $cellstock2, $cellstock3);
			$common_array = $this->array_sort_by_column($totalstock, 'date');
		}

		//$html = view('report.product.modelsale')->with(compact('totalstock','cellstock','id','stockdatas','cellstock3'))->render();
		$html = view('report.product.modelsale')->with(compact('totalstock', 'id', 'cellstock3'))->render();
		return response()->json(['success' => true, 'html' => $html]);
	}

	//service by emp. list in report
	public function servicebyemployee()
	{
		$all_employee = 'all';
		$title_report = 'All Service';
		$date_report = 'Year';
		$title = 'Service';

		$currentUser = User::where([['soft_delete', 0], ['id', '=', Auth::User()->id]])->orderBy('id', 'DESC')->first();
		// $adminCurrentBranch = BranchSetting::where('id', '=', 1)->first();

		if (isAdmin(Auth::User()->role_id)) {
			$servicereport = Service::where([['done_status', '=', 1], ['soft_delete', 0]])->orderBy('id', 'desc')->get();

			$service = DB::select("SELECT count(*) as counts, RIGHT(YEAR(`service_date`),4) as date FROM tbl_services where done_status=1 group by YEAR(`service_date`) ORDER BY service_date ASC");

			$Select_employee = DB::table('users')->where([['role', '=', 'employee'], ['soft_delete', 0]])->get()->toArray();
		} elseif (getUsersRole(Auth::user()->role_id) == 'Customer') {
			$servicereport = Service::where('done_status', '=', 1)->orderBy('id', 'desc')->get();

			$service = DB::select("SELECT count(*) as counts, RIGHT(YEAR(`service_date`),4) as date FROM tbl_services where done_status=1  group by YEAR(`service_date`) ORDER BY service_date ASC");
			$Select_employee = DB::table('users')->where('role', '=', 'employee')->get()->toArray();
		} else {
			$servicereport = Service::where([['done_status', '=', 1], ['soft_delete', 0], ['branch_id', $currentUser->branch_id]])->orderBy('id', 'desc')->get();

			$service = DB::select("SELECT count(*) as counts, RIGHT(YEAR(`service_date`),4) as date FROM tbl_services where done_status=1 and branch_id= '" . $currentUser->branch_id . "'  group by YEAR(`service_date`) ORDER BY service_date ASC");

			$Select_employee = DB::table('users')->where([['role', '=', 'employee'], ['soft_delete', 0], ['branch_id', $currentUser->branch_id]])->get()->toArray();
		}
		$totalAmount = 0;
		// Loop through each service record and sum the amounts
		foreach ($servicereport as $s) {
			$paidAmount = getPaidAmount($s->job_no);
			$totalAmount += $paidAmount;
		}
		return view('report.service.empservicelist', compact('all_employee', 'Select_employee', 'servicereport', 'title_report', 'date_report', 'title', 'service', 'totalAmount'));
	}

	//service emp. list based on date in report
	public function employeeservice(Request $request)
	{
		if (getDateFormat() == 'm-d-Y') {
			$s_date = date('Y-m-d', strtotime(str_replace('-', '/', $request->start_date)));
			$e_date = date('Y-m-d', strtotime(str_replace('-', '/', $request->end_date)));
		} else {
			$s_date = date('Y-m-d', strtotime($request->start_date));
			$e_date = date('Y-m-d', strtotime($request->end_date));
		}

		$s_date .= " 00:00:00";
		$e_date .= " 23:59:59";
		$all_employee = $request->s_customer;

		$currentUser = User::where([['soft_delete', 0], ['id', '=', Auth::User()->id]])->orderBy('id', 'DESC')->first();
		// $adminCurrentBranch = BranchSetting::where('id', '=', 1)->first();

		if (isAdmin(Auth::User()->role_id)) {
			if ($s_date == "" && $e_date == "" && $all_employee == 'all') {
				$title_report = 'All Service';
				$date_report = 'Year';
				$title = 'Service';

				$service = DB::select("SELECT count(*) as counts, RIGHT(YEAR(`service_date`),4) as date FROM tbl_services where (assign_to='$all_employee') and (done_status=1) group by YEAR(`service_date`) ORDER BY date ASC");

				$servicereport = Service::where([['done_status', '=', 1], ['soft_delete', 0]])->orderBy('id', 'desc')->get();

				$Select_employee = DB::table('users')->where([['role', '=', 'employee'], ['soft_delete', 0]])->get()->toArray();
			} elseif ($s_date != "" && $e_date != "" && $all_employee == 'all') {
				$title_report = 'All Service';
				$date_report = 'Year';
				$title = 'Service';

				$service = DB::select("select count(*) as counts, CONCAT(	MONTHNAME(`service_date`),'-',RIGHT(YEAR(`service_date`),4)) as date FROM tbl_services where  (service_date BETWEEN  '$s_date' AND '$e_date') and (done_status=1) GROUP BY MONTH(service_date), YEAR(service_date)");

				$Select_employee = User::where([['role', '=', 'employee'], ['soft_delete', 0]])->get();

				$servicereport = Service::where([['done_status', '=', 1], ['soft_delete', 0]])->whereBetween('service_date', array($s_date, $e_date))->orderBy('id', 'desc')->get();
			} else if ($s_date == "" && $e_date == "" && $all_employee != 'all') {
				$title_report = 'All Service';
				$date_report = 'Year';
				$title = 'Service';

				$service = DB::select("SELECT count(*) as counts, RIGHT(YEAR(`service_date`),4) as date FROM tbl_services where (assign_to='$all_employee') and (done_status=1)group by YEAR(`service_date`) ORDER BY service_date ASC");

				$Select_employee = User::where([['role', '=', 'employee'], ['soft_delete', 0]])->get();

				$servicereport = Service::where([['assign_to', '=', $all_employee], ['done_status', '=', 1], ['soft_delete', 0]])->orderBy('id', 'desc')->get();
			} else if ($s_date != "" && $e_date != "" && $all_employee != 'all') {
				$title_report = 'All Service';
				$date_report = 'Year';
				$title = 'Service';

				$service = DB::select("select count(*) as counts, CONCAT(MONTHNAME(`service_date`),'-',RIGHT(YEAR(`service_date`),4)) as date FROM tbl_services where (service_date BETWEEN  '$s_date' AND '$e_date') and (done_status=1) and assign_to='$all_employee' GROUP BY MONTH(service_date), YEAR(service_date)");

				$Select_employee = User::where([['role', '=', 'employee'], ['soft_delete', 0]])->get();

				$servicereport = Service::where('done_status', '=', 1)
					->whereBetween('service_date', array($s_date, $e_date))
					// ->where([['soft_delete', 0], ['branch_id', $adminCurrentBranch->branch_id]])
					->where('assign_to', '=', $all_employee)
					->orderBy('id', 'desc')->get();
			}
		} elseif (getUsersRole(Auth::user()->role_id) == 'Customer') {
			if ($s_date == "" && $e_date == "" && $all_employee == 'all') {
				$title_report = 'All Service';
				$date_report = 'Year';
				$title = 'Service';

				$Select_employee = User::where('role', '=', 'employee')->get();

				$service = DB::select("SELECT count(*) as counts, RIGHT(YEAR(`service_date`),4) as date FROM tbl_services where (assign_to='$all_employee') and (done_status=1) group by YEAR(`service_date`) ORDER BY date ASC");

				$servicereport = Service::where('done_status', '=', 1)->orderBy('id', 'desc')->get();
			} elseif ($s_date != "" && $e_date != "" && $all_employee == 'all') {
				$title_report = 'All Service';
				$date_report = 'Year';
				$title = 'Service';

				$service = DB::select("select count(*) as counts, CONCAT(	MONTHNAME(`service_date`),'-',RIGHT(YEAR(`service_date`),4)) as date FROM tbl_services where  (service_date BETWEEN  '$s_date' AND '$e_date') and (done_status=1) GROUP BY MONTH(service_date), YEAR(service_date)");

				$Select_employee = User::where('role', '=', 'employee')->get();

				$servicereport = Service::where('done_status', '=', 1)->whereBetween('service_date', array($s_date, $e_date))->orderBy('id', 'desc')->get();
			} else if ($s_date == "" && $e_date == "" && $all_employee != 'all') {
				$title_report = 'All Service';
				$date_report = 'Year';
				$title = 'Service';

				$service = DB::select("SELECT count(*) as counts, RIGHT(YEAR(`service_date`),4) as date FROM tbl_services where (assign_to='$all_employee') and (done_status=1) group by YEAR(`service_date`) ORDER BY service_date ASC");

				$Select_employee = User::where('role', '=', 'employee')->get();

				$servicereport = Service::where([['assign_to', '=', $all_employee], ['done_status', '=', 1]])->orderBy('id', 'desc')->get();
			} else if ($s_date != "" && $e_date != "" && $all_employee != 'all') {
				$title_report = 'All Service';
				$date_report = 'Year';
				$title = 'Service';

				$service = DB::select("select count(*) as counts, CONCAT(MONTHNAME(`service_date`),'-',RIGHT(YEAR(`service_date`),4)) as date FROM tbl_services where (service_date BETWEEN  '$s_date' AND '$e_date') and (done_status=1) and assign_to='$all_employee' GROUP BY MONTH(service_date), YEAR(service_date)");

				$Select_employee = User::where('role', '=', 'employee')->get();

				$servicereport = Service::where('done_status', '=', 1)
					->whereBetween('service_date', array($s_date, $e_date))
					->where('assign_to', '=', $all_employee)->orderBy('id', 'desc')->get();
			}
		} else {
			$product = Product::where([['soft_delete', 0], ['branch_id', $currentUser->branch_id]])->orderBy('id', 'DESC')->get();

			if ($s_date == "" && $e_date == "" && $all_employee == 'all') {
				$title_report = 'All Service';
				$date_report = 'Year';
				$title = 'Service';

				$service = DB::select("SELECT count(*) as counts, RIGHT(YEAR(`service_date`),4) as date FROM tbl_services where (assign_to='$all_employee') and (done_status=1) and '" . $currentUser->branch_id . "' group by YEAR(`service_date`) ORDER BY date ASC");

				$servicereport = Service::where([['done_status', '=', 1], ['soft_delete', 0], ['branch_id', $currentUser->branch_id]])->orderBy('id', 'desc')->get();

				$Select_employee = DB::table('users')->where([['role', '=', 'employee'], ['soft_delete', 0], ['branch_id', $currentUser->branch_id]])->get()->toArray();
			} elseif ($s_date != "" && $e_date != "" && $all_employee == 'all') {
				$title_report = 'All Service';
				$date_report = 'Year';
				$title = 'Service';

				$service = DB::select("select count(*) as counts, CONCAT(	MONTHNAME(`service_date`),'-',RIGHT(YEAR(`service_date`),4)) as date FROM tbl_services where  (service_date BETWEEN  '$s_date' AND '$e_date') and (done_status=1) and '" . $currentUser->branch_id . "' GROUP BY MONTH(service_date), YEAR(service_date)");

				$Select_employee = User::where([['role', '=', 'employee'], ['soft_delete', 0], ['branch_id', $currentUser->branch_id]])->get();

				$servicereport = Service::where([['done_status', '=', 1], ['soft_delete', 0], ['branch_id', $currentUser->branch_id]])->whereBetween('service_date', array($s_date, $e_date))->orderBy('id', 'desc')->get();
			} else if ($s_date == "" && $e_date == "" && $all_employee != 'all') {
				$title_report = 'All Service';
				$date_report = 'Year';
				$title = 'Service';

				$service = DB::select("SELECT count(*) as counts, RIGHT(YEAR(`service_date`),4) as date FROM tbl_services where (assign_to='$all_employee') and (done_status=1) and '" . $currentUser->branch_id . "' group by YEAR(`service_date`) ORDER BY service_date ASC");

				$Select_employee = User::where([['role', '=', 'employee'], ['soft_delete', 0], ['branch_id', $currentUser->branch_id]])->get();

				$servicereport = Service::where([['assign_to', '=', $all_employee], ['done_status', '=', 1], ['soft_delete', 0], ['branch_id', $currentUser->branch_id]])->orderBy('id', 'desc')->get();
			} else if ($s_date != "" && $e_date != "" && $all_employee != 'all') {
				$title_report = 'All Service';
				$date_report = 'Year';
				$title = 'Service';

				$service = DB::select("select count(*) as counts, CONCAT(MONTHNAME(`service_date`),'-',RIGHT(YEAR(`service_date`),4)) as date FROM tbl_services where (service_date BETWEEN  '$s_date' AND '$e_date') and (done_status=1) and assign_to='$all_employee' and '" . $currentUser->branch_id . "' GROUP BY MONTH(service_date), YEAR(service_date)");

				$Select_employee = User::where([['role', '=', 'employee'], ['soft_delete', 0], ['branch_id', $currentUser->branch_id]])->get();

				$servicereport = Service::where('done_status', '=', 1)
					->whereBetween('service_date', array($s_date, $e_date))
					->where([['soft_delete', 0], ['branch_id', $currentUser->branch_id]])
					->where('assign_to', '=', $all_employee)
					->orderBy('id', 'desc')->get();
			}
		}

		$totalAmount = 0;
		// Loop through each service record and sum the amounts
		foreach ($servicereport as $s) {
			$paidAmount = getPaidAmount($s->job_no);
			$totalAmount += $paidAmount;
		}
		return view('report.service.empservicelist', compact('Select_employee', 'servicereport', 'all_employee', 's_date', 'e_date', 'service', 'title_report', 'date_report', 'title', 'totalAmount'));
	}

	public function email(Request $request)
	{
		$email = EmailLog::orderBy('created_at', 'desc')->get();
		// dd($email);
		return view('report.email', compact('email'));
	}

	public function record_email(Request $request)
	{
		if (getDateFormat() == 'm-d-Y') {
			$startDate = date('Y-m-d', strtotime(str_replace('-', '/', $request->start_date)));
			$endDate = date('Y-m-d', strtotime(str_replace('-', '/', $request->end_date)));
		} else {
			$startDate = date('Y-m-d', strtotime($request->start_date));
			$endDate = date('Y-m-d', strtotime($request->end_date));
		}
		$email = EmailLog::whereBetween('created_at', [$startDate, $endDate])->orderBy('created_at', 'desc')->get();
		// dd($email);
		return view('report.email', compact('email', 'startDate', 'endDate'));
	}

	//upcoming service list in report
	public function upcomingservice(Request $request)
	{
		$nowdate = date('Y-m-d');

		$upcomingservice = Service::where([['job_no', 'like', 'J%'], ['service_date', '>', $nowdate], ['soft_delete', '=', 0]])->get();
		$totalAmount = 0;
		// Loop through each service record and sum the amounts
		foreach ($upcomingservice as $s) {
			$paidAmount = getPaidAmount($s->job_no);
			$totalAmount += $paidAmount;
		}
		return view('report.service.upcomingservice', compact('upcomingservice', 'totalAmount'));
	}

	public function record_upcoming(Request $request)
	{
		if (getDateFormat() == 'm-d-Y') {
			$startDate = date('Y-m-d', strtotime(str_replace('-', '/', $request->start_date)));
			$endDate = date('Y-m-d', strtotime(str_replace('-', '/', $request->end_date)));
		} else {
			$startDate = date('Y-m-d', strtotime($request->start_date));
			$endDate = date('Y-m-d', strtotime($request->end_date));
		}
		$upcomingservice = Service::whereBetween('service_date', [$startDate, $endDate])->orderBy('created_at', 'desc')->get();
		$totalAmount = 0;
		// Loop through each service record and sum the amounts
		foreach ($upcomingservice as $s) {
			$paidAmount = getPaidAmount($s->job_no);
			$totalAmount += $paidAmount;
		}
		return view('report.service.upcomingservice', compact('upcomingservice', 'startDate', 'endDate', 'totalAmount'));
	}


	public function generate_pdf(Request $request)
	{
		// Get table data from request
		$tableData = $request->get('tableData');

		$columnNames = $request->get('columnNames');

		// Generate HTML for the PDF content
		$html = '<html><head><style>
			table, th, td {
				border: 1px solid black;
				border-collapse: collapse;
				padding: 5px;
			}
		</style></head><body>';

		// Start table
		$html .= '<table border="1" width="100%">';

		// Check if table data is not empty
		if (!empty($tableData)) {
			// Header row
			$html .= '<tr>';
			// Use column names obtained earlier as keys
			foreach ($columnNames as $columnName) {
				$html .= '<th>' . $columnName . '</th>';
			}
			$html .= '</tr>';

			// Data rows
			foreach ($tableData as $row) {
				$html .= '<tr>';
				foreach ($row as $value) {
					$html .= '<td>' . $value . '</td>';
				}
				$html .= '</tr>';
			}
		} else {
			// If table data is empty, show a message
			$html .= '<tr><td colspan="999">No data available</td></tr>';
		}

		// End table
		$html .= '</table>';

		$html .= '</body></html>';

		$mpdf = new Mpdf();

		// Load HTML content
		$mpdf->WriteHTML($html);

		$filename = time() . '.pdf';

		$filePath = 'public/pdf/csv/' . $filename;

		$mpdf->Output($filePath, Destination::FILE);
		$quotation = URL::to($filePath);

		return response()->json(['pdfPath' => $quotation]);
	}
}
