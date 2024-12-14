<?php

namespace App\Http\Controllers;

use App\Http\Requests\ToolRequest;
use App\{StockHistory, Tool,Unit};
use App\SparePartLabel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ToolController extends Controller
{
  public function __construct()
  {
    $this->middleware('auth');
  }
  public function index()
  {
    $tools = Tool::with('unit')->where('user_id', Auth::user()->id)->get();

    return view('saas.tool.list', compact('tools'));
  }
  public function add()
  {
    $units = Unit::all();
    $sparePartLabels = SparePartLabel::select('id','title')->where('spare_part_type' , 3)->get();
    return view('saas.tool.add', compact('units','sparePartLabels'));
  }
  public function store(ToolRequest $request)
  {

    $sparePartLabel = SparePartLabel::firstOrCreate(
      ['title' => $request->name],
      ['title' => $request->name,'spare_part_type' => 3]
    );
    $tool = new Tool;
    $tool->user_id = Auth::user()->id;
    $tool->spare_part_type = 3;
    $tool->label_id = $sparePartLabel->id;

    if(!empty($request->image)){
      $tool->image = uploadFile($request->image,'/uploads/tool/') ?? '';
    }
  
    $tool->unit_id = $request->unit_id;
    $tool->suitable_for = $request->suitable_for;
    $tool->price = $request->price;
    $tool->discount = $request->discount ?? 0;
    $tool->stock = $request->stock;
    $tool->description = $request->description;
    $tool->save();

    $stockHistory = new StockHistory;
    $stockHistory->label_id = $sparePartLabel->id;
    $stockHistory->category = 3;
    $stockHistory->user_id = Auth::user()->id;
    $stockHistory->last_stock = 0;
    $stockHistory->current_stock = $tool->stock;
    $stockHistory->stock_type = 'addition';
    $stockHistory->remarks = "Stock added by spare part vendor.";
    $stockHistory->save();

    return redirect()->route('tool.list')->with('message', 'Tool Added Successfully');
  }

  public function edit($id)
  {
    $tool = Tool::find($id);
    $units = Unit::all();
    $sparePartLabels = SparePartLabel::select('id','title')->where('spare_part_type' , 3)->get();

    return view('saas.tool.edit', compact('tool', 'units','sparePartLabels'));
  }

  public function update(ToolRequest $request)
  {
   
    // $count = Tool::where([['name', '=', $request->name], ['part_number', '=', $request->part_number], ['id', '!=', $request->id]])->count();

    // if ($count == 0) {
      $sparePartLabel = SparePartLabel::firstOrCreate(
        ['title' => $request->name],
        ['title' => $request->name,'spare_part_type' => 3]
      );
      $tool = Tool::find($request->id);
      if(!empty($tool)){

        $tool->label_id = $sparePartLabel->id;
        
        if(!empty($request->image)){
          $tool->image = uploadFile($request->image,'/uploads/tool/') ?? '';
        }

        $tool->unit_id = $request->unit_id;
        $tool->suitable_for = $request->suitable_for;
        $tool->price = $request->price;
        $tool->discount = $request->discount;
        $tool->stock = $request->stock;
        $tool->description = $request->description;
        $tool->save();
        
        return redirect()->route('tool.list')->with('message', 'Tool Updated Successfully');
      }
      return redirect()->back()->with('message','Error! Something went wrong.');

    // } else {
    //   return redirect()->route('tool.edit' . $id)->with('message', 'Duplicate Data');
    // }
  }

  public function destory($id)
  {
    Tool::find($id)->delete();

    return redirect()->route('tool.list')->with('message', 'Tool Deleted Successfully');
  }

  public function destroyMultiple(Request $request)
  {
    $ids = $request->input('ids');

    Tool::whereIn('id', $ids)->delete();

    return redirect()->route('tool.list')->with('message', 'Tool Deleted Successfully');
  }

}
