<?php

namespace App\Http\Controllers;

use DB;
use Auth;
use App\User;
use App\Branch;
use App\Income;
use App\Invoice;
use App\CustomField;
use App\BranchSetting;
use App\tbl_payment_records;
use App\IncomeHistoryRecord;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use App\Http\Requests\StoreIncomeRequest;
use App\Http\Requests\StoreIncomeEditRequest;

class IncomeController extends Controller
{
	public function __construct()
	{
		$this->middleware('auth');
	}


	//income list
	public function showall()
	{
		$currentUser = User::where([['soft_delete', 0], ['id', '=', Auth::User()->id]])->orderBy('id', 'DESC')->first();
		// $adminCurrentBranch = BranchSetting::where('id', '=', 1)->first();

		if (isAdmin(Auth::User()->role_id)) {
			$incomes = Income::join('tbl_income_history_records', 'tbl_incomes.id', '=', 'tbl_income_history_records.tbl_income_id')
				->where([['tbl_income_history_records.soft_delete', 0]])
				->groupBy('tbl_income_history_records.tbl_income_id')
				->orderBy('tbl_incomes.id', 'DESC')
				->select('tbl_incomes.*', 'tbl_income_history_records.*')
				->get();
		} elseif (getUsersRole(Auth::user()->role_id) == 'Customer') {
			$incomes = Income::join('tbl_income_history_records', 'tbl_incomes.id', '=', 'tbl_income_history_records.tbl_income_id')
				->where([['tbl_incomes.soft_delete', 0], ['tbl_incomes.customer_id', Auth::user()->id]])
				->groupBy('tbl_income_history_records.tbl_income_id')
				->orderBy('tbl_incomes.id', 'DESC')
				->select('tbl_incomes.*', 'tbl_income_history_records.*')
				->get();
		} else {
			$incomes = Income::join('tbl_income_history_records', 'tbl_incomes.id', '=', 'tbl_income_history_records.tbl_income_id')
				->where([['tbl_income_history_records.soft_delete', 0], ['tbl_income_history_records.branch_id', $currentUser->branch_id]])
				->groupBy('tbl_income_history_records.tbl_income_id')
				->orderBy('tbl_incomes.id', 'DESC')
				->select('tbl_incomes.*', 'tbl_income_history_records.*')
				->get();
		}

		// Filter the $income records based on the condition
		$income = $incomes->filter(function ($incomes) {
			return getSumOfIncome($incomes->tbl_income_id) != 0;
		});

		//Custom Field Data
		$tbl_custom_fields = CustomField::where([['form_name', '=', 'income'], ['always_visable', '=', 'yes']])->get();

		return view('income.list', compact('income', 'tbl_custom_fields'));
	}


	//income add form
	public function index()
	{
		$invoice_no = DB::table('tbl_incomes')->get()->toArray();
		$invoices = array();
		foreach ($invoice_no as $invoicess) {
			$invoices[] = $invoicess->invoice_number;
		}

		$characterss = '0123456789';
		$codepay =  'P' . '' . substr(str_shuffle($characterss), 0, 6);
		$tbl_payments = DB::table('tbl_payments')->where('soft_delete', '=', 0)->get()->toArray();

		$tbl_custom_fields = DB::table('tbl_custom_fields')->where([['form_name', '=', 'income'], ['always_visable', '=', 'yes'], ['soft_delete', '=', 0]])->get()->toArray();

		$currentUser = User::where([['soft_delete', 0], ['id', '=', Auth::User()->id]])->orderBy('id', 'DESC')->first();
		// $adminCurrentBranch = BranchSetting::where('id', '=', 1)->first();
		if (isAdmin(Auth::User()->role_id)) {
			$branchDatas = Branch::get();
			$left_invoice = DB::table('tbl_invoices')->where([['soft_delete', 0]])->get()->toArray();
		// } elseif (getUsersRole(Auth::user()->role_id) == 'Customer') {
		// 	$branchDatas = Branch::get();
		// 	$left_invoice = DB::table('tbl_invoices')->where('soft_delete', '=', 0)->get()->toArray();
		} else {
			$branchDatas = Branch::where('id', $currentUser->branch_id)->get();
			$left_invoice = DB::table('tbl_invoices')->where([['soft_delete', 0], ['branch_id', $currentUser->branch_id]])->get()->toArray();
		}

		return view('income.add', compact('left_invoice', 'codepay', 'tbl_payments', 'tbl_custom_fields', 'branchDatas'));
	}


	//income store
	public function store(StoreIncomeRequest $request)
	{
		$Total_Amount = $request->Total_Amount;
		$income_entry = $request->income_entry;
		$amount_recevied1 = 0;

		if (!empty($income_entry)) {
			foreach ($income_entry as $key => $value) {
				$income_entr = $income_entry[$key];
				$amount_recevied1 += $income_entr;
			}
		}

		if ($Total_Amount >= $amount_recevied1) {
			if (getDateFormat() == 'm-d-Y') {
				$dates = date('Y-m-d', strtotime(str_replace('-', '/', $request->date)));
			} else {
				$dates = date('Y-m-d', strtotime($request->date));
			}
			$invoiceno = $request->invoice;
			$tbl_invoices = DB::table('tbl_invoices')->where('invoice_number', '=', $invoiceno)->first();
			$customer_id = $tbl_invoices->customer_id;
			$tbl_incomes = new Income;
			$tbl_incomes->invoice_number = $request->invoice;
			$tbl_incomes->payment_number = $request->paymentno;
			$tbl_incomes->customer_id = $customer_id;
			$tbl_incomes->status = $request->status;
			$tbl_incomes->payment_type = $request->Payment_type;
			$tbl_incomes->date = $dates;
			$tbl_incomes->main_label = $request->main_label;
			$tbl_incomes->branch_id = $request->branch;

			//custom field	
			$custom = $request->custom;
			$custom_fileld_value = array();
			$custom_fileld_value_jason_array = array();
			if (!empty($custom)) {
				foreach ($custom as $key => $value) {
					if (is_array($value)) {
						$add_one_in = implode(",", $value);
						$custom_fileld_value[] = array("id" => "$key", "value" => "$add_one_in");
					} else {
						$custom_fileld_value[] = array("id" => "$key", "value" => "$value");
					}
				}

				$custom_fileld_value_jason_array['custom_fileld_value'] = json_encode($custom_fileld_value);

				foreach ($custom_fileld_value_jason_array as $key1 => $val1) {
					$incomeData = $val1;
				}
				$tbl_incomes->custom_field = $incomeData;
			}

			$tbl_incomes->save();

			$income_entry = $request->income_entry;
			$income_label = $request->income_label;

			$total1 = 0;
			foreach ($income_entry as $key => $value) {
				$income_entr = $income_entry[$key];

				$income_lbls = $income_label[$key];

				$tbl_income_id = DB::table('tbl_incomes')->orderBy('id', 'DESC')->first();

				$tbl_income_history_records = new IncomeHistoryRecord;
				$tbl_income_history_records->tbl_income_id = $tbl_income_id->id;
				$tbl_income_history_records->income_amount = $income_entr;
				$tbl_income_history_records->income_label = $income_lbls;
				$tbl_income_history_records->branch_id = $request->branch;
				$tbl_income_history_records->save();
				$total1 += $income_entr;
			}

			$tbl_invoices = DB::table('tbl_invoices')->where('invoice_number', '=', $invoiceno)->first();
			$id = $tbl_invoices->id;
			$paid_amount = $tbl_invoices->paid_amount;
			$grandtotal = $tbl_invoices->grand_total;
			$total = $paid_amount + $total1;


			$tblin = Invoice::find($id);
			$tblin->paid_amount = $total;
			if ($grandtotal == $total) {
				$status = 2;
				$tblin->payment_status = $status;
			} elseif ($grandtotal > $total && $total > 0) {
				$status = 1;
				$tblin->payment_status = $status;
			} elseif ($total == 0) {
				$status = 0;
				$tblin->payment_status = $status;
			}
			$tblin->save();

			$tbl_payment_records = new  tbl_payment_records;
			$tbl_payment_records->invoices_id = $id;
			$tbl_payment_records->payment_number = $request->paymentno;
			$tbl_payment_records->amount = $total1;
			$tbl_payment_records->payment_type = $request->Payment_type;
			$tbl_payment_records->payment_date = $dates;
			$tbl_payment_records->branch_id = $request->branch;
			$tbl_payment_records->save();

			return redirect('income/list')->with('message', 'Income Added Successfully');
		} else {
			return redirect('income/add')->with('message', 'amount');
		}
	}


	//income edit
	public function edit($id)
	{
		$customer = DB::table('users')->where([['role', '=', 'Customer'], ['soft_delete', '=', 0]])->get()->toArray();
		$invoice_no = DB::table('tbl_invoices')->get()->toArray();
		$tbl_payments = DB::table('tbl_payments')->where('soft_delete', '=', 0)->get()->toArray();

		//Custom Field Data
		$tbl_custom_fields = DB::table('tbl_custom_fields')->where([['form_name', '=', 'income'], ['always_visable', '=', 'yes'], ['soft_delete', '=', 0]])->get()->toArray();

		$currentUser = User::where([['soft_delete', 0], ['id', '=', Auth::User()->id]])->orderBy('id', 'DESC')->first();
		// $adminCurrentBranch = BranchSetting::where('id', '=', 1)->first();
		if (isAdmin(Auth::User()->role_id)) {
			$branchDatas = Branch::get();
			$first_data = DB::table("tbl_incomes")->where([['id', $id]])->first();
			$sec_data = DB::table("tbl_income_history_records")->where([['tbl_income_id', $id]])->get()->toArray();
		// } elseif (getUsersRole(Auth::user()->role_id) == 'Customer') {
		// 	$branchDatas = Branch::get();
		// 	$first_data = DB::table("tbl_incomes")->where('id', $id)->first();
		// 	$sec_data = DB::table("tbl_income_history_records")->where('tbl_income_id', $id)->get()->toArray();
		} else {
			$branchDatas = Branch::where('id', '=', $currentUser->branch_id)->get();
			$first_data = DB::table("tbl_incomes")->where([['id', $id], ['branch_id', $currentUser->branch_id]])->first();
			$sec_data = DB::table("tbl_income_history_records")->where([['tbl_income_id', $id], ['branch_id', $currentUser->branch_id]])->get()->toArray();
		}

		return view("income/edit", compact('first_data', 'sec_data', 'customer', 'invoice_no', 'tbl_payments', 'tbl_custom_fields', 'branchDatas'));
	}

	//income update
	public function update(Request $request, $id)
	{
		$paidamount = getSumOfIncome($id);
		$Total_Amount = $request->Total_Amount;
		$income_entry = $request->income_entry;
		$amount_recevied1 = 0;

		if (!empty($income_entry)) {
			foreach ($income_entry as $key => $value) {
				$income_entr = $income_entry[$key];
				$amount_recevied1 += $income_entr;
			}
		}

		if ($paidamount < $amount_recevied1) {
			$amount1 = $amount_recevied1 - $paidamount;
		} else {
			$amount1 = 0;
		}
		if ($Total_Amount >= $amount1) {
			$tbl_income = DB::table('tbl_incomes')->where('id', '=', $id)->first();
			$payment_number = $tbl_income->payment_number;
			$invoice_number = $tbl_income->invoice_number;

			if (getDateFormat() == 'm-d-Y') {
				$dates = date('Y-m-d H:i:s', strtotime(str_replace('-', '/', $request->date)));
			} else {
				$dates = date('Y-m-d', strtotime($request->date));
			}

			$tbl_income = Income::find($id);
			$tbl_income->payment_number = $payment_number;
			$tbl_income->status = $request->status;
			$tbl_income->payment_type = $request->Payment_type;
			$tbl_income->date = $dates;
			$tbl_income->main_label = $request->main_label;
			// dd($request->branch);
			$tbl_income->branch_id = $request->branch;

			//custom field	
			$custom = $request->custom;
			$custom_fileld_value = array();
			$custom_fileld_value_jason_array = array();
			if (!empty($custom)) {
				foreach ($custom as $key => $value) {
					if (is_array($value)) {
						$add_one_in = implode(",", $value);
						$custom_fileld_value[] = array("id" => "$key", "value" => "$add_one_in");
					} else {
						$custom_fileld_value[] = array("id" => "$key", "value" => "$value");
					}
				}

				$custom_fileld_value_jason_array['custom_fileld_value'] = json_encode($custom_fileld_value);

				foreach ($custom_fileld_value_jason_array as $key1 => $val1) {
					$incomeData = $val1;
				}
				$tbl_income->custom_field = $incomeData;
			}

			$tbl_income->save();
			$paidamount = getSumOfIncome($id);
			$income_entry = $request->income_entry;
			$id = $request->autoid;
			$income_label = $request->income_label;
			DB::table('tbl_income_history_records')->where('tbl_income_id', $request->id)->delete();
			$amount_recevied = 0;
			foreach ($income_entry as $key => $value) {
				$income_entr = $income_entry[$key];
				$income_lbls = $income_label[$key];
				DB::insert("insert into tbl_income_history_records set tbl_income_id = $request->id, income_amount = $income_entr, income_label = '$income_lbls', branch_id  = '$request->branch'");
				$amount_recevied += $income_entr;
			}

			$tbl_invoices = DB::table('tbl_invoices')->where('invoice_number', '=', $invoice_number)->first();
			$invoice_id = $tbl_invoices->id;
			$paid_amount = $tbl_invoices->paid_amount;
			$payment_number1 = $tbl_invoices->payment_number;

			if ($amount_recevied > $paidamount) {
				$amount = $amount_recevied - $paidamount;
				$paid_amount1 = $paid_amount + $amount;
			}
			if ($amount_recevied < $paidamount) {
				$amount = $paidamount - $amount_recevied;
				$paid_amount1 = $paid_amount - $amount;
			}
			if ($amount_recevied == $paidamount) {
				$paid_amount1 = $paid_amount;
			}

			if ($payment_number == $payment_number1) {
				DB::update("update tbl_invoices set paid_amount='$paid_amount1',amount_recevied='$amount_recevied' where id=$invoice_id");
			} else {
				DB::update("update tbl_invoices set paid_amount='$paid_amount1' where id=$invoice_id");
			}

			$tbl_payment_recordss = DB::table('tbl_payment_records')->where('payment_number', '=', $payment_number)->first();
			$tbl_payment_records_id = $tbl_payment_recordss->id;

			$tbl_payment_records = tbl_payment_records::find($tbl_payment_records_id);
			$tbl_payment_records->invoices_id = $invoice_id;
			$tbl_payment_records->payment_number = $payment_number;
			$tbl_payment_records->amount = $amount_recevied;
			$tbl_payment_records->payment_type = $request->Payment_type;
			$tbl_payment_records->payment_date = $dates;
			$tbl_payment_records->branch_id = 1;
			// dd($tbl_payment_records);
			$tbl_payment_records->save();
			return redirect('income/list')->with('message', 'Income Updated Successfully');
		} else {
			return redirect('income/edit/' . $id)->with('message', "sucessfully Submitted");
		}
	}

	//income delete
	public function destroy($id)
	{
		$tbl_incomes = DB::table('tbl_incomes')->where('id', $id)->first();
		$idinvoice = $tbl_incomes->invoice_number;
		$payment_number = $tbl_incomes->payment_number;
		$amount = getSumOfIncome($id);

		$tbl_invoices = DB::table('tbl_invoices')->where('invoice_number', '=', $idinvoice)->first();

		$invoice_id = $tbl_invoices->id;
		$paid_amount = $tbl_invoices->paid_amount;
		$payment_number1 = $tbl_invoices->payment_number;

		$total_paid = $paid_amount - $amount;

		if ($tbl_invoices == $payment_number1) {
			DB::update("update tbl_invoices set paid_amount='$total_paid',amount_recevied='0',payment_status='1' where id=$invoice_id");
		} else {
			DB::update("update tbl_invoices set paid_amount='$total_paid',payment_status='1' where id=$invoice_id");
		}

		DB::table('tbl_payment_records')->where('payment_number', $payment_number)->update(['soft_delete' => 1]);
		DB::table('tbl_incomes')->where('id', $id)->update(['soft_delete' => 1]);

		DB::table('tbl_income_history_records')->where('tbl_income_id', '=', $id)->update(['soft_delete' => 1]);

		return redirect("income/list")->with('message', 'Income Deleted Successfully');
	}

	public function destoryMultiple(Request $request)
	{
		$ids = $request->input('ids');

		foreach ($ids as $id) {
			$this->destroy($id);
		}

		return response()->json(['message' => 'Successfully deleted selected purchase records']);
	}

	//month income form
	public function monthly_income()
	{
		return view("income/month_income");
	}

	//month income list report
	public function get_month_income(Request $request)
	{
		$this->validate($request, []);
		if (getDateFormat() == 'm-d-Y') {
			$start_date = date('Y-m-d', strtotime(str_replace('-', '/', $request->start_date)));
			$end_date = date('Y-m-d', strtotime(str_replace('-', '/', $request->end_date)));
		} else {
			$start_date = date('Y-m-d', strtotime($request->start_date));
			$end_date = date('Y-m-d', strtotime($request->end_date));
		}

		$currentUser = User::where([['soft_delete', 0], ['id', '=', Auth::User()->id]])->orderBy('id', 'DESC')->first();
		// $adminCurrentBranch = BranchSetting::where('id', '=', 1)->first();
		if (isAdmin(Auth::User()->role_id)) {
			$month_income = Income::join('tbl_income_history_records', 'tbl_incomes.id', '=', 'tbl_income_history_records.tbl_income_id')
				->where('tbl_income_history_records.soft_delete', 0)
				->whereBetween('date', [$start_date, $end_date])
				->select('tbl_incomes.*', 'tbl_income_history_records.*')
				->where('income_amount', '!=', '')
				// ->where('tbl_incomes.branch_id', '=', $adminCurrentBranch->branch_id)
				->orderBy('tbl_income_history_records.id', 'DESC')
				->get();
		} elseif (getUsersRole(Auth::user()->role_id) == 'Customer') {
			$month_income = Income::join('tbl_income_history_records', 'tbl_incomes.id', '=', 'tbl_income_history_records.tbl_income_id')
				->where('tbl_income_history_records.soft_delete', 0)
				->whereBetween('date', [$start_date, $end_date])
				->select('tbl_incomes.*', 'tbl_income_history_records.*')
				->where('income_amount', '!=', '')
				->where('tbl_incomes.customer_id', '=', Auth::user()->id)
				->orderBy('tbl_income_history_records.id', 'DESC')
				->get();
		} elseif (getUsersRole(Auth::user()->role_id) == 'Employee') {
			$month_income = Income::join('tbl_income_history_records', 'tbl_incomes.id', '=', 'tbl_income_history_records.tbl_income_id')
				->where('tbl_income_history_records.soft_delete', 0)
				->whereBetween('date', [$start_date, $end_date])
				->select('tbl_incomes.*', 'tbl_income_history_records.*')
				->where('income_amount', '!=', '')
				->where('tbl_incomes.branch_id', '=', $currentUser->branch_id)
				->orderBy('tbl_income_history_records.id', 'DESC')
				->get();
		} else {
			$month_income = Income::join('tbl_income_history_records', 'tbl_incomes.id', '=', 'tbl_income_history_records.tbl_income_id')
				->where('tbl_income_history_records.soft_delete', 0)
				->whereBetween('date', [$start_date, $end_date])
				->select('tbl_incomes.*', 'tbl_income_history_records.*')
				->where('income_amount', '!=', '')
				->where('tbl_incomes.branch_id', '=', $currentUser->branch_id)
				->orderBy('tbl_income_history_records.id', 'DESC')
				->get();
		}

		if (empty($month_income)) {
			Session::flash('message', 'Data Not Found !');
		}
		return view('income.income_report', compact('month_income', 'start_date', 'end_date'));
	}
}
