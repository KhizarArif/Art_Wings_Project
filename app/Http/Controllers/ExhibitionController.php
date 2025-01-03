<?php

namespace App\Http\Controllers;

use App\Http\Services\ExhibitionServices;
use Illuminate\Http\Request;

class ExhibitionController extends Controller
{
    protected $exhibitionServices;
    public function __construct(ExhibitionServices $exhibitionServices)
    {
        $this->exhibitionServices = $exhibitionServices;
    }

    public function index()
    {
        return $this->exhibitionServices->index();
    }


    public function create()
    {
        return $this->exhibitionServices->create();
    }


    public function store(Request $request)
    {
        return $this->exhibitionServices->store($request);
    }


    public function edit(Request $request)
    {
        return $this->exhibitionServices->edit($request);
    }

    public function destroy($id)
    {
        return $this->exhibitionServices->destroy($id);
    }

    public function updateExhibitionImage(Request $request)
    {
        return $this->exhibitionServices->updateExhibitionImage($request);
    }
    public function deleteExhibitionImage(Request $request)
    {
        return $this->exhibitionServices->deleteExhibitionImage($request);
    }

}
