<?php

namespace App\Http\Controllers;

use App\Http\Requests\LubricantRequest;
use App\{Category, Lubricant, StockHistory, Unit};
use App\SparePartLabel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LubricantController extends Controller
{
  public function __construct()
  {
    $this->middleware('auth');
  }
  public function index()
  {
    $lubricants = Lubricant::with('unit','category')->where('user_id', Auth::user()->id)->get();
    return view('saas.lubricant.list', compact('lubricants'));
  }
  public function add()
  {
    $units = Unit::all();
    $categories = Category::all();
    $sparePartLabels = SparePartLabel::select('id','title')->where('spare_part_type' , 4)->get();

    return view('saas.lubricant.add', compact('units','categories','sparePartLabels'));
  }
  public function store(LubricantRequest $request)
  {

    $sparePartLabel = SparePartLabel::firstOrCreate(
      ['title' => $request->name],
      ['title' => $request->name,'spare_part_type' => 4]
    );

    $lubricant = new Lubricant;
    $lubricant->user_id = Auth::user()->id;
    $lubricant->spare_part_type = 4;
    $lubricant->label_id = $sparePartLabel->id;

    if(!empty($request->image)){
      $lubricant->image = uploadFile($request->image,'/uploads/lubricant/') ?? '';
    }
  
    $lubricant->unit_id = $request->unit_id;
    $lubricant->category_id = $request->category_id;
    $lubricant->brand = $request->brand;
    $lubricant->suitable_for = $request->suitable_for;
    $lubricant->price = $request->price;
    $lubricant->discount = $request->discount ?? '';
    $lubricant->stock = $request->stock;
    $lubricant->description = $request->description;
    $lubricant->save();

    $stockHistory = new StockHistory;
    $stockHistory->label_id = $sparePartLabel->id;
    $stockHistory->spare_part_id = $lubricant->id;
    $stockHistory->category = 4;
    $stockHistory->user_id = Auth::user()->id;
    $stockHistory->last_stock = 0;
    $stockHistory->current_stock = $lubricant->stock;
    $stockHistory->stock_type = 'addition';
    $stockHistory->remarks = "Stock added by spare part vendor.";
    $stockHistory->save();

    return redirect()->route('lubricant.list')->with('message', 'Lubricant Added Successfully');
  }

  public function edit($id)
  {
    $lubricant = Lubricant::find($id);
    $units = Unit::all();
    $categories = Category::all();
    $sparePartLabels = SparePartLabel::select('id','title')->where('spare_part_type' , 4)->get();

    return view('saas.lubricant.edit', compact('lubricant', 'units','categories','sparePartLabels'));
  }

  public function update(LubricantRequest $request)
  {
   
    // $count = Lubricant::where([['name', '=', $request->name], ['part_number', '=', $request->part_number], ['id', '!=', $request->id]])->count();

    // if ($count == 0) {
      $sparePartLabel = SparePartLabel::firstOrCreate(
        ['title' => $request->name],
        ['title' => $request->name,'spare_part_type' => 4]
      );
      $lubricantStock = Lubricant::where('id',$request->id)->first('stock');
      $lubricant = Lubricant::find($request->id);
      if(!empty($lubricant)){

        $lubricant->label_id = $sparePartLabel->id;
        
        if(!empty($request->image)){
          $lubricant->image = uploadFile($request->image,'/uploads/lubricant/') ?? '';
        }

        $lubricant->unit_id = $request->unit_id;
        $lubricant->category_id = $request->category_id;
        $lubricant->brand = $request->brand;
        $lubricant->suitable_for = $request->suitable_for;
        $lubricant->price = $request->price;
        $lubricant->discount = $request->discount;
        $lubricant->stock = $request->stock;
        $lubricant->description = $request->description;
        $lubricant->save();

        $stockHistory = new StockHistory;
        $stockHistory->label_id = $sparePartLabel->id;
        $stockHistory->spare_part_id = $lubricant->id;
        $stockHistory->category = 4;
        $stockHistory->user_id = Auth::user()->id;
        $stockHistory->last_stock = $lubricantStock->stock;
        $stockHistory->current_stock = $lubricant->stock;
        $stockHistory->stock_type = 'addition';
        $stockHistory->remarks = "Stock added by spare part vendor.";
        $stockHistory->save();
        
        return redirect()->route('lubricant.list')->with('message', 'Lubricant Updated Successfully');
      }
      return redirect()->back()->with('message','Error! Something went wrong.');

    // } else {
    //   return redirect()->route('lubricant.edit' . $id)->with('message', 'Duplicate Data');
    // }
  }

  public function destory($id)
  {
    Lubricant::find($id)->delete();

    return redirect()->route('lubricant.list')->with('message', 'Lubricant Deleted Successfully');
  }

  public function destroyMultiple(Request $request)
  {
    $ids = $request->input('ids');

    Lubricant::whereIn('id', $ids)->delete();

    return redirect()->route('lubricant.list')->with('message', 'Lubricant Deleted Successfully');
  }

}
