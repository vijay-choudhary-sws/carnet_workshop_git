<?php

namespace App\Http\Controllers;

use App\Http\Requests\PurcahseSparePartRequest;
use App\Order;
use App\OrderItem;
use App\SparePart;
use App\SparePartLabel;
use App\User;
use Carbon\Carbon;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PurchaseSparePartController extends Controller
{
	public function __construct()
	{
		$this->middleware('auth');
	}

	public function index()
	{

		$orders = Order::all();
		return view('purchase_spare_part.list', compact('orders'));
	}

	public function view($id)
	{
		$order_items = OrderItem::where('order_id', $id)->get();
		return view('purchase_spare_part.view', compact('order_items'));
	}

	public function add()
	{
		return view('purchase_spare_part.add');
	}

	public function getItem(Request $request)
	{
		$itemIds = [];
		if (is_array($request->item_id)) {
			$itemIds = $request->item_id;
		}

		$items = SparePart::where(['spare_part_type' => $request->cat_id])->whereNotIn('id', $itemIds)->get();
		if (count($items) > 0) {
			$html = view('purchase_spare_part.component.item', compact('items'))->render();


			return response()->json(['status' => 1, 'html' => $html]);
		}
		return response()->json(['status' => 0, 'msg' => 'Item not found.']);
	}
	public function getAmount(Request $request)
	{
		$items = SparePart::find($request->stock_id);
		// echo "<pre>";print_r($items->toArray());die;

		if (!empty($items)) {
			$data = [
				'status' => 1,
				'stock' => $items->stock,
				'price' => $items->price,
				'stock_id' => $items->id
			];
			return response()->json($data);
		}
		return response()->json(['status' => 0, 'msg' => 'Item not found.']);
	}
	public function addRow(Request $request)
	{
		$html = view('purchase_spare_part.component.add_row', ['row' => $request->row])->render();
		return response()->json(['status' => 1, 'html' => $html]);
	}

	public function store(PurcahseSparePartRequest $request)
	{

		$category = $request->category;
		$item = $request->item;
		$quantity = $request->quantity;
		$price = $request->price;
		$total_price = $request->total_price;

		$order = new Order;
		$order->order_date = Carbon::now();
		$order->total_amount = $request->total_amount;
		$order->total_quantity = array_sum($request->quantity);
		$order->total_item = count($request->item);
		$order->save();

		foreach ($category as $key => $value) {

			$userId = SparePart::where('id', $item[$key])->select('user_id')->first()->user_id;

			$orderItem = new OrderItem;
			$orderItem->spare_part_id = $item[$key];
			$orderItem->user_id = $userId;
			$orderItem->quantity = $quantity[$key];
			$orderItem->price = $price[$key];
			$orderItem->total_amount = $total_price[$key];
			$orderItem->category = $value;
			$orderItem->order_id = $order->id;
			$orderItem->save();
		}

		return response()->json(['status' => 'success']);
	}

	public function addRowmodel(Request $request)
	{
		$html = view('purchase_spare_part.component.add_row_model', ['row' => $request->row])->render();
		return response()->json(['status' => 1, 'html' => $html]);
	}
	public function create(): View
	{
		return view('purchase_spare_part.create');
	}




}
