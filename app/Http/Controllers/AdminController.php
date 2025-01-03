<?php

namespace App\Http\Controllers;

use App\Http\Services\AdminServices;
use Illuminate\Http\Request;

class AdminController extends Controller
{
    protected $adminServices;
    public function __construct(AdminServices $adminServices){
        $this->adminServices = $adminServices;
    }

    public function index(){
        return $this->adminServices->index();
    }
    public function logout(){
        return $this->adminServices->logout();
    }
}
