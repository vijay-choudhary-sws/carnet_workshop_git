<?php

namespace App\Http\Controllers;

use App\Http\Requests\StockRequest;
use App\ProductStock;
use App\SparePartLabel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;


class ProductStockcontroller extends Controller
{
  public function __construct()
  {
    $this->middleware('auth');
  }
  public function index()
  {
    $product_stocks = ProductStock::where('user_id', Auth::id())->get();
    // $product_stocks = ProductStock::all();

    return view('product_stock.list', compact('product_stocks'));
  }

  public function add(Request $request)
  {
    $sparePartLabels = SparePartLabel::select('id', 'title')->get();

    if ($request->ajax()) {
      $html = view('new_jobcard.component.add-stock', compact('sparePartLabels'))->render();
      return response()->json(['status' => 1, 'html' => $html]);
    }

    return view('product_stock.add', compact('sparePartLabels'));
  }

  public function store(StockRequest $request)
  {
    $sparePartLabel = SparePartLabel::firstOrCreate(
      ['title' => $request->name, 'spare_part_type' => $request->category_id],
      ['title' => $request->name, 'spare_part_type' => $request->category_id]
    );
    if (
      $productStock = ProductStock::where([
        'user_id' => Auth::id(),
        'label_id' => $sparePartLabel->id,
        'price' => $request->price,
        'category_id' => $request->category_id
      ])->first()
    ) {
      $productStock->stock = $productStock->stock + $request->stock;
      $productStock->save();
    } else {
      $productStock = new ProductStock;
      $productStock->user_id = Auth::id();
      $productStock->label_id = $sparePartLabel->id;
      $productStock->category_id = $request->category_id;
      $productStock->price = $request->price;
      $productStock->stock = $request->stock;
      $productStock->save();
    }

    return redirect()->route('stock.list')->with('message', 'Stock Updated Successfully');
  }
  public function bulkStore(Request $request)
  {

    $this->validate($request, [
      'stock_item' => 'required|array',
      'stock_price' => 'required|array',
      'stock_quantity' => 'required|array',
      'stock_category' => 'required|array',
      'stock_item.*' => 'required|string',
      'stock_price.*' => 'required|numeric|min:1',
      'stock_quantity.*' => 'required|integer|min:1',
      'stock_category.*' => 'required|integer',
    ], [
      'stock_item.required' => 'Stock items array is required.',
      'stock_item.*.required' => 'Each stock item is required.',
      'stock_item.*.string' => 'Each stock item must be a string.',

      'stock_price.required' => 'Stock prices array is required.',
      'stock_price.*.required' => 'Each stock price is required.',
      'stock_price.*.numeric' => 'Each stock price must be a number.',
      'stock_price.*.min' => 'Each stock price must be at least 1.',

      'stock_quantity.required' => 'Stock quantities array is required.',
      'stock_quantity.*.required' => 'Each stock quantity is required.',
      'stock_quantity.*.integer' => 'Each stock quantity must be an integer.',
      'stock_quantity.*.min' => 'Each stock quantity must be at least 1.',

      'stock_category.required' => 'Stock category is required.',
      'stock_category.*.required' => 'Each stock category is required.',
      'stock_category.*.integer' => 'Each stock category must be an integer.',
    ]);

    $name = $request->stock_item;
    $price = $request->stock_price;
    $stock = $request->stock_quantity;
    $category_id = $request->stock_category;

    foreach ($name as $key => $value) {
      $sparePartLabel = SparePartLabel::firstOrCreate(
        ['title' => $value, 'spare_part_type' => $category_id[$key]],
        ['title' => $value, 'spare_part_type' => $category_id[$key]]
      );
      if (
        $productStock = ProductStock::where([
          'user_id' => Auth::id(),
          'label_id' => $sparePartLabel->id,
          'price' => $price[$key],
          'category_id' => $category_id[$key]
        ])->first()
      ) {
        $productStock->stock = $productStock->stock + $stock[$key];
        $productStock->save();
      } else {
        $productStock = new ProductStock;
        $productStock->user_id = Auth::id();
        $productStock->label_id = $sparePartLabel->id;
        $productStock->category_id = $category_id[$key];
        $productStock->price = $price[$key];
        $productStock->stock = $stock[$key];
        $productStock->save();
      }
    }

    return response()->json(['status' => 1, 'message' => 'Stock Updated Successfully']);
  }

  public function search(Request $request)
  {
    $query = $request->input('query');
    $results = SparePartLabel::where('title', 'LIKE', '%' . $query . '%')->groupBy('title')->get();

    return response()->json($results);
  }
  public function addrow(Request $request)
  {
    $html = view('new_jobcard.component.add-stock-row')->render();
    return response()->json(['status' => 1, 'html' => $html]);
  }

}
