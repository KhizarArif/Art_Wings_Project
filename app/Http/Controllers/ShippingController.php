<?php

namespace App\Http\Controllers;

use App\Http\Services\ShippingServices;
use Illuminate\Http\Request;

class ShippingController extends Controller
{
    protected $shippingServices;
    public function __construct(ShippingServices $shippingServices)
    {
        $this->shippingServices = $shippingServices;
    }

    public function create()
    {
        return $this->shippingServices->create();
    }
    public function store(Request $request)
    {
        return $this->shippingServices->store($request);
    }
    public function edit(Request $request)
    {
        return $this->shippingServices->edit($request);
    }
  
    public function destroy($id)
    {
        return $this->shippingServices->destroy($id);
    }
}
