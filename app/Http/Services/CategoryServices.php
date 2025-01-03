<?php

namespace App\Http\Services;

use App\Models\Category;
use App\Models\Review;
use Illuminate\Support\Facades\Validator;

class CategoryServices
{
    public function index()
    {
        $categories = Category::orderBy('id', 'desc')->get();
        return view('admin.category.index', compact('categories'));
    }

    public function create()
    {
        $categories = Category::orderBy('id', 'desc')->get();
        return view('admin.category.create', compact('categories'));
    }

    public function store($request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'status' => 'required',
            'show_on_home' => 'required',
        ]);

        if ($validator->fails()) {
            $message = $validator->errors();

            return response()->json(['error' => $message]);
        }

        if (empty($request->id)) {
            $categoryExists = Category::where('name', $request->name)->exists();

            if ($categoryExists) {
                $message = 'Category with this name already exists.';

                return response()->json(['error' => $message]);
            }
        }

        if ($validator->passes()) {

            $category = $request->id ? Category::find($request->id) : new Category();
            $category->name = $request->name;
            $category->slug = $request->slug;
            $category->status = $request->status;
            $category->showOnHome = $request->show_on_home;
            $category->save();
            $message = $request->id ? 'Category Update successfully' : 'Category created successfully.';
            return response()->json(['status' => true, 'message' => $message]);
        }
    }

    public function edit($request)
    {
        $category = Category::find($request->id);
        return view('admin.category.create', compact('category'));
    }

    public function destroy($id)
    {
        $product = Category::find($id);
        $product->delete();

        return response()->json([
            "status" => true,
            "message" => 'Category Deleted Successfully! ',
        ]);
    }
}
