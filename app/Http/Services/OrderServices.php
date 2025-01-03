<?php

namespace App\Http\Services;

use App\Models\Category;
use App\Models\Order;
use App\Models\Review;
use Illuminate\Support\Facades\Validator;

class OrderServices
{
    public function index()
    {
        $orders = Order::orderBy('id', 'desc')->get();
        return view('admin.orders.index', compact('orders'));
    }

    public function create()
    {
        return view('admin.orders.create');
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
        $order = Order::select('orders.*', 'cities_pk.name as cityName')->where('orders.id', $request->id)
                        ->leftJoin('cities_pk', 'cities_pk.id', 'orders.city')
                        ->with('orderItems')->first();
        return view('admin.orders.edit', compact('order'));
    }


    public function destroy($id)
    {
        $order = Order::find($id);
        $order->delete();

        return response()->json([
            "status" => true,
            "message" => 'Order Deleted Successfully! ',
        ]);
    }
}
