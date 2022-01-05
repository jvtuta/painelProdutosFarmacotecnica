<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class Home extends Controller {

    /**
     * @return View -> Main
     */
    public function index(Request $request) 
    {
        return view('home');
    }

    /**
     * @return ExcelData
     */
    public function data(Request $request)
    {
        return redirect()->route('result');
    }
}


?>