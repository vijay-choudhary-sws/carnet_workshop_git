<?php

namespace App\Http\Controllers;

use App\Http\Requests\UnitRequest;
use App\Unit;
use Illuminate\Http\Request;


class UnitController extends Controller
{
  public function __construct()
  {
    $this->middleware('auth');
  }
  public function index()
  {
    $units = Unit::all();
    return view('saas.unit.list', compact('units'));
  }
  public function add()
  {
    return view('saas.unit.add');
  }
  public function store(UnitRequest $request)
  {

    $unit = new Unit;
    $unit->title = $request->title;
    $unit->save();

    return redirect()->route('unit.list')->with('message', 'Unit Added Successfully');
  }

  public function edit($id)
  {
    $unit = Unit::find($id);

    return view('saas.unit.edit', compact('unit'));
  }

  public function update(UnitRequest $request)
  {
   
    // $count = Unit::where([['name', '=', $request->name], ['part_number', '=', $request->part_number], ['id', '!=', $request->id]])->count();

    // if ($count == 0) {
      $unit = Unit::find($request->id);
      if(!empty($unit)){
        $unit->title = $request->title;
        $unit->save();
        
        return redirect()->route('unit.list')->with('message', 'Unit Updated Successfully');
      }
      return redirect()->back()->with('message','Error! Something went wrong.');

    // } else {
    //   return redirect()->route('unit.edit' . $id)->with('message', 'Duplicate Data');
    // }
  }

  public function destory($id)
  {
    Unit::find($id)->delete();

    return redirect()->route('unit.list')->with('message', 'Unit Deleted Successfully');
  }

  public function destroyMultiple(Request $request)
  {
    $ids = $request->input('ids');

    Unit::whereIn('id', $ids)->delete();

    return redirect()->route('unit.list')->with('message', 'Unit Deleted Successfully');
  }

}
