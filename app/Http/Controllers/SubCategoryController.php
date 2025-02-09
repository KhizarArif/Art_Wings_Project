<?php

namespace App\Http\Controllers;

use App\Http\Services\SubCategoryServices;
use Illuminate\Http\Request;

class SubCategoryController extends Controller
{
    protected $subCategoryServices;
    public function __construct(SubCategoryServices $subCategoryServices){
        $this->subCategoryServices = $subCategoryServices;
    }


    public function index(){
        return $this->subCategoryServices->index();
    }
    public function create(){
        return $this->subCategoryServices->create();
    }
    public function store(Request $request){
        return $this->subCategoryServices->store($request);
    }

    public function edit(Request $request)
    {
        return $this->subCategoryServices->edit($request);
    }
    public function destroy($id)
    {
        return $this->subCategoryServices->destroy($id);
    }
    public function updateSubCategoryImage(Request $request)
    {
        return $this->subCategoryServices->updateSubCategoryImage($request);
    }
    public function deleteSubCategoryImage(Request $request)
    {
        return $this->subCategoryServices->deleteSubCategoryImage($request);
    }

}
