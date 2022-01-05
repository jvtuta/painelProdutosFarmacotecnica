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
     * metodo post irá processar os dados e envia-los para a view
     * @return ExcelData
     */
    public function data(Request $request)
    {

        


        $dados = ['dado'=>'teste dados'];
        return view('resultProdutos', $dados);
    }
}


?>