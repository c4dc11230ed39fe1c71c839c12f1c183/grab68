<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class EndPointTestController extends Controller
{
    public function index(Request $request)
    {
        return view('end-point-test')->with([
            'request' => $request,
        ]);
    }
}
