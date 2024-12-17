<?php

namespace App\Http\Controllers; 
use App\StockHistory;
use Illuminate\Support\Facades\Auth;

class StockHistoryController extends Controller
{
  public function __construct()
  {
    $this->middleware('auth');
  }
  public function index()
  {
    $stocks_histories = StockHistory::where('user_id',Auth::id())->get(); 
    return view('stock_history.list', compact('stocks_histories'));
  } 
 
  
}
