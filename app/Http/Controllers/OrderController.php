<?php

namespace App\Http\Controllers;

use App\Http\Requests\CategoryRequest;
use App\Order;
use App\OrderItem;
use App\ProductStock;
use App\SparePart;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class OrderController extends Controller
{
  public function __construct()
  {
    $this->middleware('auth');
  }
  public function index()
  {
    $orders = Order::with('orderItem')
      ->whereHas('orderItem', function ($qry) {
        $qry->where('user_id', Auth::user()->id);
      })
      ->get();
    return view('saas.order.list', compact('orders'));
  }

  public function view($id)
  {
    $order_items = OrderItem::where('order_id', $id)->get();

    // echo "<pre>";print_r($order_items[0]->SpareParts->name);die;
    return view('saas.order.view', compact('order_items'));
  }


  public function accept(Request $request)
  {
    $order_item = OrderItem::where('id', $request->id)->first();


    if ($request->status == 1) {
      $spare_part = SparePart::where('id', $order_item->spare_part_id)->first();
      $spare_part->stock = $spare_part->stock - $order_item->quantity;
      $spare_part->save();

      $stockProduct = ProductStock::where('label_id', $spare_part->label_id)->first();

      if (empty($stockProduct)) {
        $stockProducts = new ProductStock;
        $stockProducts->label_id = $spare_part->label_id;
        $stockProducts->category_id = $order_item->category;
        $stockProducts->stock = $order_item->quantity;
        $stockProducts->user_id = Auth::user()->id;
        $stockProducts->save();
      } else {
        $stockProduct->stock = $stockProduct->stock + $order_item->quantity;
        $stockProduct->save();
      }

    }
    $order_item->status = $request->status;
    $order_item->save();
    //  echo "<pre>";print_r($order_item);die;


  }

}
