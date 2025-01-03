<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller; 
use App\Http\Services\OrderServices;
use Illuminate\Http\Request;

class OrderController extends Controller
{   
    protected $orderServices;
    public function __construct(OrderServices $orderServices)
    {
        $this->orderServices = $orderServices;
    }

    public function index()
    {
        return $this->orderServices->index();
    }
    public function create()
    {
        return $this->orderServices->create();
    }
    public function store(Request $request)
    {
        return $this->orderServices->store($request);
    }
    public function edit(Request $request)
    {
        return $this->orderServices->edit($request);
    }
    public function changeOrderStatus(Request $request, $id)
    {
        return $this->orderServices->changeOrderStatus($request, $id);
    }
    public function destroy($id)
    {
        return $this->orderServices->destroy($id);
    }
}
