<?php

namespace App\Http\Controllers;

use App\Http\Requests\AccessoryRequest;
use App\{Category, Accessory, SparePartLabel, StockHistory, Unit};
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AccessoryController extends Controller
{
  public function __construct()
  {
    $this->middleware('auth');
  }
  public function index()
  {
    $accessories = Accessory::with('unit')->where('user_id', Auth::user()->id)->get();
    return view('saas.accessory.list', compact('accessories'));
  }
  public function add()
  {
    $units = Unit::all();
    $sparePartLabels = SparePartLabel::select('id','title')->where('spare_part_type' , 1)->get();
    return view('saas.accessory.add', compact('units','sparePartLabels'));
  }
  public function store(AccessoryRequest $request)
  {
    // echo "<pre>";print_r($request->all());die;

    $sparePartLabel = SparePartLabel::firstOrCreate(
      ['title' => $request->name],
      ['title' => $request->name,'spare_part_type' => 1]
    );
    
    $accessory = new Accessory;
    $accessory->user_id = Auth::user()->id;
    $accessory->spare_part_type = 1;
    $accessory->label_id = $sparePartLabel->id;

    if(!empty($request->image)){
      $accessory->image = uploadFile($request->image,'/uploads/accessory/') ?? '';
    }
  
    $accessory->unit_id = $request->unit_id;
    $accessory->brand = $request->brand ?? '';
    $accessory->suitable_for = $request->suitable_for ?? '';
    $accessory->price = $request->price;
    $accessory->discount = $request->discount ?? 0;
    $accessory->stock = $request->stock;
    $accessory->description = $request->description ?? '';
    $accessory->save();

    $stockHistory = new StockHistory;
    $stockHistory->label_id = $sparePartLabel->id;
    $stockHistory->category = 1;
    $stockHistory->user_id = Auth::user()->id;
    $stockHistory->last_stock = 0;
    $stockHistory->current_stock = $accessory->stock;
    $stockHistory->stock_type = 'addition';
    $stockHistory->remarks = "Stock added by spare part vendor.";
    $stockHistory->save();

    return redirect()->route('accessory.list')->with('message', 'Accessory Added Successfully');
  }

  public function edit($id)
  {
    $accessory = Accessory::find($id);
    $units = Unit::all();
    $sparePartLabels = SparePartLabel::select('id','title')->where('spare_part_type' , 1)->get();

    return view('saas.accessory.edit', compact('accessory', 'units','sparePartLabels'));
  }

  public function update(AccessoryRequest $request)
  {
   
    // $count = Accessory::where([['name', '=', $request->name], ['part_number', '=', $request->part_number], ['id', '!=', $request->id]])->count();

    // if ($count == 0) {
      $sparePartLabel = SparePartLabel::firstOrCreate(
        ['title' => $request->name],
        ['title' => $request->name,'spare_part_type' => 1]
      );
      $accessory = Accessory::find($request->id);
      if(!empty($accessory)){

        $accessory->label_id = $sparePartLabel->id;
        
        if(!empty($request->image)){
          $accessory->image = uploadFile($request->image,'/uploads/accessory/') ?? '';
        }

        $accessory->unit_id = $request->unit_id;
        $accessory->brand = $request->brand;
        $accessory->suitable_for = $request->suitable_for;
        $accessory->price = $request->price;
        $accessory->discount = $request->discount;
        $accessory->stock = $request->stock;
        $accessory->description = $request->description;
        $accessory->save();
        
        
        return redirect()->route('accessory.list')->with('message', 'Accessory Updated Successfully');
      }
      return redirect()->back()->with('message','Error! Something went wrong.');

    // } else {
    //   return redirect()->route('accessory.edit' . $id)->with('message', 'Duplicate Data');
    // }
  }

  public function destory($id)
  {
    Accessory::find($id)->delete();

    return redirect()->route('accessory.list')->with('message', 'Accessory Deleted Successfully');
  }

  public function destroyMultiple(Request $request)
  {
    $ids = $request->input('ids');

    Accessory::whereIn('id', $ids)->delete();

    return redirect()->route('accessory.list')->with('message', 'Accessory Deleted Successfully');
  }

}
