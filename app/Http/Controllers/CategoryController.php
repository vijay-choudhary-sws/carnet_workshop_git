<?php

namespace App\Http\Controllers;

use App\Http\Requests\CategoryRequest;
use App\Category;
use Illuminate\Http\Request;


class CategoryController extends Controller
{
  public function __construct()
  {
    $this->middleware('auth');
  }
  public function index()
  {
    $categories = Category::all();
    return view('saas.category.list', compact('categories'));
  }
  public function add()
  {
    return view('saas.category.add');
  }
  public function store(CategoryRequest $request)
  {

    $category = new Category;
    $category->title = $request->title;
    $category->save();

    return redirect()->route('category.list')->with('message', 'Category Added Successfully');
  }

  public function edit($id)
  {
    $category = Category::find($id);

    return view('saas.category.edit', compact('category'));
  }

  public function update(CategoryRequest $request)
  {
   
    // $count = Category::where([['name', '=', $request->name], ['part_number', '=', $request->part_number], ['id', '!=', $request->id]])->count();

    // if ($count == 0) {
      $category = Category::find($request->id);
      if(!empty($category)){
        $category->title = $request->title;
        $category->save();
        
        return redirect()->route('category.list')->with('message', 'Category Updated Successfully');
      }
      return redirect()->back()->with('message','Error! Something went wrong.');

    // } else {
    //   return redirect()->route('category.edit' . $id)->with('message', 'Duplicate Data');
    // }
  }

  public function destory($id)
  {
    Category::find($id)->delete();

    return redirect()->route('category.list')->with('message', 'Category Deleted Successfully');
  }

  public function destroyMultiple(Request $request)
  {
    $ids = $request->input('ids');

    Category::whereIn('id', $ids)->delete();

    return redirect()->route('category.list')->with('message', 'Category Deleted Successfully');
  }

}
