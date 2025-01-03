<?php

namespace App\Http\Controllers;

use App\Http\Services\NewArrivalServices;
use Illuminate\Http\Request;

class NewArrivalController extends Controller
{
    protected $newArrivalServices;
    public function __construct(NewArrivalServices $newArrivalServices)
    {
        $this->newArrivalServices = $newArrivalServices;
    }

    public function index()
    {
        return $this->newArrivalServices->index();
    }


    public function create()
    {
        return $this->newArrivalServices->create();
    }


    public function store(Request $request)
    {
        return $this->newArrivalServices->store($request);
    }


    public function edit(Request $request)
    {
        return $this->newArrivalServices->edit($request);
    }


    public function updateProductImage(Request $request)
    {
        return $this->newArrivalServices->updateProductImage($request);
    }


    public function deleteProductImage(Request $request)
    {
        return $this->newArrivalServices->deleteProductImage($request);
    }


    public function destroy($id)
    {
        return $this->newArrivalServices->destroy($id);
    }
}
