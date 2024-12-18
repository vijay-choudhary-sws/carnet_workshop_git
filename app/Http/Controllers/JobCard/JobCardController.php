<?php

namespace App\Http\Controllers\JobCard;

use App\Http\Controllers\Controller;
use App\NewJobCard;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class JobCardController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }
    public function index()
    {
        $jobcards = NewJobCard::all();
        return view('new_jobcard.list', compact('jobcards'));
    }
    public function add()
    {
        $customers = User::where([['role', 'Customer'], ['soft_delete', 0]])->get();
        $country = DB::table('tbl_countries')->get()->toArray();
        
        return view('new_jobcard.add',compact('customers','country'));
    }
    public function store(AccessoryRequest $request)
    {

        $sparePartLabel = SparePartLabel::firstOrCreate(
            ['title' => $request->name],
            ['title' => $request->name, 'spare_part_type' => 1]
        );

        $accessory = new Accessory;
        $accessory->user_id = Auth::user()->id;
        $accessory->spare_part_type = 1;
        $accessory->label_id = $sparePartLabel->id;

        if (!empty($request->image)) {
            $accessory->image = uploadFile($request->image, '/uploads/accessory/') ?? '';
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
        $stockHistory->spare_part_id = $accessory->id;
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
        $accessory = NewJobCard::find($id);
        $units = Unit::all();
        $sparePartLabels = SparePartLabel::select('id', 'title')->where('spare_part_type', 1)->get();

        return view('saas.accessory.edit', compact('accessory', 'units', 'sparePartLabels'));
    }

    public function update(AccessoryRequest $request)
    {

        // $count = NewJobCard::where([['name', '=', $request->name], ['part_number', '=', $request->part_number], ['id', '!=', $request->id]])->count();

        // if ($count == 0) {
        $sparePartLabel = SparePartLabel::firstOrCreate(
            ['title' => $request->name],
            ['title' => $request->name, 'spare_part_type' => 1]
        );
        $accessoryStock = NewJobCard::find($request->id)->first('stock');
        $accessory = NewJobCard::find($request->id);
        if (!empty($accessory)) {

            $accessory->label_id = $sparePartLabel->id;

            if (!empty($request->image)) {
                $accessory->image = uploadFile($request->image, '/uploads/accessory/') ?? '';
            }

            $accessory->unit_id = $request->unit_id;
            $accessory->brand = $request->brand;
            $accessory->suitable_for = $request->suitable_for;
            $accessory->price = $request->price;
            $accessory->discount = $request->discount;
            $accessory->stock = $request->stock;
            $accessory->description = $request->description;
            $accessory->save();


            $stockHistory = new StockHistory;
            $stockHistory->label_id = $sparePartLabel->id;
            $stockHistory->spare_part_id = $accessory->id;
            $stockHistory->category = 1;
            $stockHistory->user_id = Auth::user()->id;
            $stockHistory->last_stock = $accessoryStock->stock;
            $stockHistory->current_stock = $accessory->stock;
            $stockHistory->stock_type = 'addition';
            $stockHistory->remarks = "Stock added by spare part vendor.";
            $stockHistory->save();

            return redirect()->route('accessory.list')->with('message', 'Accessory Updated Successfully');
        }
        return redirect()->back()->with('message', 'Error! Something went wrong.');

        // } else {
        //   return redirect()->route('accessory.edit' . $id)->with('message', 'Duplicate Data');
        // }
    }

    public function destory($id)
    {
        NewJobCard::find($id)->delete();

        return redirect()->route('accessory.list')->with('message', 'Accessory Deleted Successfully');
    }

    public function destroyMultiple(Request $request)
    {
        $ids = $request->input('ids');

        NewJobCard::whereIn('id', $ids)->delete();

        return redirect()->route('accessory.list')->with('message', 'Accessory Deleted Successfully');
    }

}
