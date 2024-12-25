<?php

namespace App\Http\Controllers;

use App\Http\Requests\CategoryRequest;
use App\Order;
use App\OrderItem;
use App\ProductStock;
use App\SparePart;
use App\StockHistory;
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
    $orders = Order::with(['orderItem' => function ($qry) {
      $qry->where('user_id', Auth::user()->id);
    }])->whereHas('orderItem', function ($qry) {
        $qry->where('user_id', Auth::user()->id);
      })
      ->get();

      // echo "<pre>";print_r($orders->toArray());die;
    return view('saas.order.list', compact('orders'));
  }

  public function view($id)
  {
    $order_items = OrderItem::where(['order_id' => $id,'user_id' => Auth::user()->id ],)->get();

    //  echo "<pre>";print_r($order_items->toArray());die;
    return view('saas.order.view', compact('order_items'));
  }
  
  
  public function accept(Request $request)
  {
    
    $order_item = OrderItem::where('id', $request->id)->first();
    
    if ($request->status == 1) {

      $sparepartStock = $spare_part = SparePart::where('id', $order_item->spare_part_id)->first();
      $spare_part->stock = $spare_part->stock - $order_item->quantity;
      $spare_part->save();

      $stockProduct = ProductStock::where(['label_id' => $spare_part->label_id,'price' => $order_item->price])->first();
      //  echo "<pre>";print_r($order_items->toArray());die;
      if (empty($stockProduct)) {
        $stockProducts = new ProductStock;
        $stockProducts->label_id = $spare_part->label_id;
        $stockProducts->category_id = $order_item->category;
        $stockProducts->stock = $order_item->quantity;
        $stockProducts->price = $order_item->price;
        $stockProducts->user_id = Auth::user()->id;
        $stockProducts->save();
      } else {
        $stockProduct->stock = $stockProduct->stock + $order_item->quantity;
        $stockProduct->price = $order_item->price;
        $stockProduct->save();
      }
      
      $stockHistory = new StockHistory;
      $stockHistory->label_id = $spare_part->label_id;
      $stockHistory->spare_part_id = $order_item->spare_part_id;
      $stockHistory->category = $order_item->category;
      $stockHistory->user_id = Auth::user()->id;
      $stockHistory->last_stock = $sparepartStock->stock;
      $stockHistory->current_stock = $sparepartStock->stock -  $order_item->quantity;
      $stockHistory->stock_type = 'removal';
      $stockHistory->remarks = "Stock removal for order accept";
      $stockHistory->save();

    }
    $order_item->status = $request->status;
    $order_item->save();

    $order_item_for_order = OrderItem::where('order_id', $order_item->order_id)->get();
    $order_status = [];
    foreach($order_item_for_order as $order_item_for_orders){
      $order_status[] = $order_item_for_orders->status;
    }

    $order = Order::where('id', $order_item->order_id)->first();
    if (!in_array('2', $order_status) && in_array('1', $order_status) && in_array('0', $order_status)) {
    $order->status = 3;
    $order->save();
    }elseif(in_array('2', $order_status) && in_array('1', $order_status) && !in_array('0', $order_status)){
      $order->status = 4;
      $order->save();
    }elseif(!in_array('2', $order_status) && in_array('1', $order_status) && !in_array('0', $order_status)){
      $order->status = 1;
      $order->save();
    }elseif(in_array('2', $order_status) && !in_array('1', $order_status) && !in_array('0', $order_status)){
      $order->status = 2;
      $order->save();
    }
       



    //  echo "<pre>";print_r($order_item);die;


  }

}
