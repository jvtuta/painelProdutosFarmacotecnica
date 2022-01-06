<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Repository\ProdutosRepository;
use App\Exports\ProdutosExport;
use Maatwebsite\Excel\Facades\Excel;

class Home extends Controller {

    /**
     * @return View -> Main
     */
    public function index() 
    {
        return view('home');
    }

    public function data(Request $request)
    {        
        $data = date('d-m-Y', strtotime($request->periodo));
        $data = explode('-', $data);
        $ano = $data[2];
        $mes = $data[1];

        $filial = $request->options === '00' ? "('01', '02', '03', '04', '06', '08', '12', '15', '16')" : "('$request->options')";
        
        $produtos = new ProdutosRepository($ano, $mes, $filial, $filial);
        $produtos = $produtos->get();

        $export = new ProdutosExport($produtos);

        
        
        return Excel::download($export, 'resultadoExcel.xlsx');
    }

}


?>