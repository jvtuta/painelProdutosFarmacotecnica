<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class Resultado extends Controller {
    public function index(Request $request) 
    {
        return view('resultProdutos');
    }
}