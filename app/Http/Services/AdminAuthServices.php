<?php

namespace App\Http\Services;

use App\Models\Review;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;

class AdminAuthServices
{
    public function index()
    {
        return view('admin.auth.login');
    }

    public function authenticate($request)
    {
        $validate = Validator::make($request->all(), [
            "email" => "required|email",
            "password" => "required",
        ]);

        if ($validate->fails()) {
            Session::flash('error', $validate->errors()->first());
            return response()->json([
                'error' => $validate->errors(),
                'status' => false
            ]);
        }

        if ($validate->passes()) {
            if (auth()->attempt(['email' => $request->email, 'password' => $request->password])) {
                if (auth()->user()->role == 'admin') {
                    Session::flash('success', 'Welcome, Admin!');
                    return response()->json([
                        'status' => true,
                        'message' => 'Welcome, Admin!'
                    ]);
                } else {
                    return response()->json([
                        'status' => false,
                        'message' => 'You are not authorized to access this site.'
                    ]);
                }
            } else {
                return response()->json([
                    'status' => false,
                    'message' => 'Email or Password is incorrect.'
                ]);
            }
        }
    }




}
