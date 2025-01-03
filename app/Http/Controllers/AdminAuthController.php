<?php

namespace App\Http\Controllers;

use App\Http\Services\AdminAuthServices;
use Illuminate\Http\Request;

class AdminAuthController extends Controller
{
    protected $adminAuthServices;
    public function __construct(AdminAuthServices $adminAuthServices){
        $this->adminAuthServices = $adminAuthServices;
    }
    public function index(){
        return $this->adminAuthServices->index();
    }
    public function authenticate(Request $request){
        return $this->adminAuthServices->authenticate($request);
    }
  

}
