<?php

namespace App\Http\Services;

use App\Models\Category;
use App\Models\City;
use App\Models\Order;
use App\Models\Review;
use App\Models\ShippingCharge;
use Illuminate\Support\Facades\Validator;

class ShippingServices
{
    public function create()
    {
        $cities = City::orderBy('name', 'asc')->get();
        $shippingCharges =  ShippingCharge::select('shipping_charges.*', 'cities_pk.name')->leftJoin('cities_pk', 'cities_pk.id', 'shipping_charges.city_id')->get();
        return view('admin.shipping.create', compact('cities', 'shippingCharges'));
    }

    public function store($request)
    {
        $validator = Validator::make($request->all(), [
            'city' => 'required',
            'amount' => 'required | numeric',
        ]);

        if ($validator->passes()) {

            $count = ShippingCharge::where('city_id', $request->city)->count();

            if (empty($request->shipping_id)) {

                if ($count > 0) {
                    return response()->json([
                        "status" => false,
                        "errors" => "Shipping Already Exists"
                    ], 422);
                }
            }

            $shipping = $request->shipping_id ? ShippingCharge::find($request->shipping_id) : new ShippingCharge();
            $shipping->city_id = $request->city;
            $shipping->amount = $request->amount;
            $shipping->save();

            $message = $request->shipping_id ? "Shipping charge Updated successfully" : "Shipping charge created successfully";
            return response()->json([
                "status" => true,
                "message" => $message
            ]);
        } else {
            return response()->json([
                "status" => false,
                "errors" => $validator->errors()
            ], 422);
        }
    }

    public function edit($request)
    {
        $cities = City::get();
        $shippingCharge = ShippingCharge::find($request->id);
        return view('admin.shipping.edit', compact('cities', 'shippingCharge'));
    }

    public function destroy($id)
    {
        $shipping = ShippingCharge::find($id);
        $shipping->delete();

        return response()->json([
            "status" => true,
            "message" => 'Shipping Deleted Successfully! ',
        ]);
    }
}
