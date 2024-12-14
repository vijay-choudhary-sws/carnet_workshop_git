<?php

namespace App\Http\Controllers;

use App\Http\Requests\CategoryRequest;
use App\Order;
use App\OrderItem;
use App\ProductStock;
use App\SparePart;
use Illuminate\Http\Request;
use Auth;


class ProductStockcontroller extends Controller
{
  public function __construct()
  {
    $this->middleware('auth');
  }
  public function index()
  {
    $product_stocks = ProductStock::all();
    return view('product_stock.list', compact('product_stocks'));
  } 
 
  
}
