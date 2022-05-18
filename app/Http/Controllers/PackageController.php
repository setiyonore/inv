<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class PackageController extends Controller
{
    public function index(Request $request){
        return view('package.index');
    }
}
