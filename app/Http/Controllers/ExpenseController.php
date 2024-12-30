<?php

namespace App\Http\Controllers;

use DB;
use Auth;
use App\User;
use App\Branch;
use App\Expense;
use App\CustomField;
use App\BranchSetting;
use App\ExpenseCategory;
use Illuminate\Http\Request;
use App\ExpenseHistoryRecord;
use DataTables\Editor\Validate;
use Illuminate\Support\Facades\Session;

class ExpenseController extends Controller
{

	public function __construct()
	{
		$this->middleware('auth');
	}


	// expense list
	public function showall()
	{
		$expenses = Expense::with('category')->get();

		return view('expense.list', compact('expenses'));
	}


	// expense addform
	public function index()
	{
		$categories = ExpenseCategory::where('status',0)->get();
		$suppliers = User::where('soft_delete',0)->get();
		return view('expense.add',compact('suppliers','categories'));
	}

	// expense store
	public function store(Request $request)
	{

		$tbl_expenses = new Expense;
		$tbl_expenses->category_id = $request->category_id;
		$tbl_expenses->supplier_id = $request->supplier_id ?? null;
		$tbl_expenses->bill_number = $request->bill_number;
		$tbl_expenses->date = $request->date;
		$tbl_expenses->total_amount = $request->total_amount;
		$tbl_expenses->paid_amount = $request->paid_amount;
		$tbl_expenses->balance_amount = $request->balance_amount;
		$tbl_expenses->description = $request->description ?? null;
		$tbl_expenses->save();
		
		return redirect('expense/list')->with('message', 'Expense Added Successfully');
	}

	public function edit($id)
	{
		$expense = Expense::find($id);
		$categories = ExpenseCategory::where('status',0)->get();
		$suppliers = User::where('soft_delete',0)->get();
		return view("expense/edit",compact('expense','categories','suppliers'));
	}

	// expense update
	public function update(Request $request)
	{
		$tbl_expenses = Expense::find($request->id);
		$tbl_expenses->category_id = $request->category_id;
		$tbl_expenses->supplier_id = $request->supplier_id;
		$tbl_expenses->bill_number = $request->bill_number;
		$tbl_expenses->date = $request->date;
		$tbl_expenses->total_amount = $request->total_amount;
		$tbl_expenses->paid_amount = $request->paid_amount;
		$tbl_expenses->balance_amount = $request->balance_amount;
		$tbl_expenses->description = $request->description;
		$tbl_expenses->save();

		return redirect('expense/list')->with('message', 'Expense Updated Successfully');
	}

	// expense delete
	public function destroy($id)
	{
		Expense::where('id', $id)->delete();

		return redirect("expense/list")->with('message', 'Expense Deleted Successfully');
	}

	public function destroyMultiple(Request $request)
	{
		$ids = $request->input('ids');

		if (!empty($ids)) {
			Expense::whereIn('id', $ids)->delete();
		}
	}

	// monthly expense form
	public function monthly_expense()
	{
		return view("expense/month_expense");
	}

	// monthly expense
	public function get_month_expense(Request $request)
	{
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
			$month_expense = Expense::join('tbl_expenses_history_records', 'tbl_expenses.id', '=', 'tbl_expenses_history_records.tbl_expenses_id')
				->whereBetween('date', [$start_date, $end_date])
				->select('tbl_expenses.*', 'tbl_expenses_history_records.*')
				// ->where('tbl_expenses.branch_id', '=', $adminCurrentBranch->branch_id)
				->orderBy('tbl_expenses_history_records.id', 'DESC')
				->get();
		} elseif (getUsersRole(Auth::user()->role_id) == 'Customer') {

			$month_expense = Expense::join('tbl_expenses_history_records', 'tbl_expenses.id', '=', 'tbl_expenses_history_records.tbl_expenses_id')
				->whereBetween('date', [$start_date, $end_date])
				->select('tbl_expenses.*', 'tbl_expenses_history_records.*')
				->orderBy('tbl_expenses_history_records.id', 'DESC')
				->get();
		} elseif (getUsersRole(Auth::user()->role_id) == 'Employee') {
			$month_expense = Expense::join('tbl_expenses_history_records', 'tbl_expenses.id', '=', 'tbl_expenses_history_records.tbl_expenses_id')
				->whereBetween('date', [$start_date, $end_date])
				->select('tbl_expenses.*', 'tbl_expenses_history_records.*')
				->where('tbl_expenses.branch_id', '=', $currentUser->branch_id)
				->orderBy('tbl_expenses_history_records.id', 'DESC')
				->get();
		} else {
			$month_expense = Expense::join('tbl_expenses_history_records', 'tbl_expenses.id', '=', 'tbl_expenses_history_records.tbl_expenses_id')
				->whereBetween('date', [$start_date, $end_date])
				->select('tbl_expenses.*', 'tbl_expenses_history_records.*')
				->where('tbl_expenses.branch_id', '=', $currentUser->branch_id)
				->orderBy('tbl_expenses_history_records.id', 'DESC')
				->get();
		}

		if (empty($month_expense)) {
			Session::flash('message', 'Data Not Found !');
		}
		return view('expense.expense_report', compact('month_expense', 'start_date', 'end_date'));
	}

	public function addCategory(Request $request){

		$category = ExpenseCategory::where('title',$request->title)->first();
		if(empty($category)){
			$category = new ExpenseCategory;
			$category->title = $request->title;
			$category->save();

			$html = '<option value="'.$category->id.'" selected>'.$category->title.'</option>';
			
			return response()->json(['status'=>1,'msg'=>'Category Saved Successfully.','html'=>$html]);
		}
		return response()->json(['status'=>2,'msg'=>'Duplicate Entry.']);
	}
}
