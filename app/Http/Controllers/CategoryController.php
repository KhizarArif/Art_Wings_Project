<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Services\CategoryServices;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    protected $categoryServices;
    public function __construct(CategoryServices $categoryServices)
    {
        $this->categoryServices = $categoryServices;
    }

    public function index()
    {
        return $this->categoryServices->index();
    }
    public function create()
    {
        return $this->categoryServices->create();
    }
    public function store(Request $request)
    {
        return $this->categoryServices->store($request);
    }
    public function edit(Request $request)
    {
        return $this->categoryServices->edit($request);
    }
    public function destroy($id)
    {
        return $this->categoryServices->destroy($id);
    }
}
