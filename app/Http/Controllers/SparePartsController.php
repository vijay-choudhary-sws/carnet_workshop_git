<?php

namespace App\Http\Controllers;

use App\Http\Requests\SparePartRequest;
use App\Part;
use App\SparePartLabel;
use App\StockHistory;
use App\Unit;
use DB;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SparePartsController extends Controller
{
  public function __construct()
  {
    $this->middleware('auth');
  }
  public function index()
  {
    $spareParts = Part::with('unit')->where('user_id', Auth::user()->id)->get();

    return view('saas.spareparts.list', compact('spareParts'));
  }
  public function add()
  {
    $units = Unit::all();
    $sparePartLabels = SparePartLabel::select('id','title')->where('spare_part_type' , 2)->get();
    return view('saas.spareparts.add', compact('units','sparePartLabels'));
  }
  public function store(SparePartRequest $request)
  {

    $sparePartLabel = SparePartLabel::firstOrCreate(
      ['title' => $request->name],
      ['title' => $request->name,'spare_part_type' => 2]
    );

    $sparePart = new Part;
    $sparePart->user_id = Auth::user()->id;
    $sparePart->spare_part_type = 2;
    $sparePart->label_id = $sparePartLabel->id;

    if(!empty($request->image)){
      $sparePart->image = uploadFile($request->image,'/uploads/spare_parts/') ?? '';
    }
    
    $sparePart->part_number = $request->part_number;
    $sparePart->brand = $request->brand;
    $sparePart->unit_id = $request->unit_id;
    $sparePart->suitable_for = $request->suitable_for;
    $sparePart->price = $request->price;
    $sparePart->discount = $request->discount ?? 0;
    $sparePart->stock = $request->stock;
    $sparePart->sp_type = $request->sp_type;
    $sparePart->description = $request->description;
    $sparePart->save();

    $stockHistory = new StockHistory;
    $stockHistory->label_id = $sparePartLabel->id;
    $stockHistory->category = 2;
    $stockHistory->user_id = Auth::user()->id;
    $stockHistory->last_stock = 0;
    $stockHistory->current_stock = $sparePart->stock;
    $stockHistory->stock_type = 'addition';
    $stockHistory->remarks = "Stock added by spare part vendor.";
    $stockHistory->save();

    return redirect()->route('sparepart.list')->with('message', 'Spare Part Added Successfully');
  }

  public function edit($id)
  {
    $sparePart = Part::find($id);
    $units = Unit::all();
    $sparePartLabels = SparePartLabel::select('id','title')->where('spare_part_type' , 2)->get();

    return view('saas.spareparts.edit', compact('sparePart', 'units','sparePartLabels'));
  }

  public function update(SparePartRequest $request)
  {
   
    // $count = Part::where([['name', '=', $request->name], ['part_number', '=', $request->part_number], ['id', '!=', $request->id]])->count();

    // if ($count == 0) {
      $sparePartLabel = SparePartLabel::firstOrCreate(
        ['title' => $request->name],
        ['title' => $request->name,'spare_part_type' => 2]
      );
      $sparePart = Part::find($request->id);
      if(!empty($sparePart)){

        $sparePart->label_id = $sparePartLabel->id;
        
        if(!empty($request->image)){
          $sparePart->image = uploadFile($request->image,'/uploads/spare_parts/') ?? '';
        }
        
        $sparePart->part_number = $request->part_number;
        $sparePart->brand = $request->brand;
        $sparePart->unit_id = $request->unit_id;
        $sparePart->suitable_for = $request->suitable_for;
        $sparePart->price = $request->price;
        $sparePart->discount = $request->discount;
        $sparePart->stock = $request->stock;
        $sparePart->sp_type = $request->sp_type;
        $sparePart->description = $request->description;
        $sparePart->save();
        
        return redirect()->route('sparepart.list')->with('message', 'Spare Part Updated Successfully');
      }
      return redirect()->back()->with('message','Error! Something went wrong.');

    // } else {
    //   return redirect()->route('sparepart.edit' . $id)->with('message', 'Duplicate Data');
    // }
  }

  public function destory($id)
  {
    Part::find($id)->delete();

    return redirect()->route('sparepart.list')->with('message', 'Spare Part Deleted Successfully');
  }

  public function destroyMultiple(Request $request)
  {
    $ids = $request->input('ids');

    Part::whereIn('id', $ids)->delete();

    return redirect()->route('sparepart.list')->with('message', 'Spare Part Deleted Successfully');
  }


}
