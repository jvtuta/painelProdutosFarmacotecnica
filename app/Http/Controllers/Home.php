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
        
        $filialName = array(
            '01'=>'Matriz', '02'=>'102Sul', '03'=> 'TagCentro','04'=>'TagNorte', 
            '06'=>'302Sul', '08'=>'CCSul',  '12'=> '316Norte', '15'=>'TeleAtendimento', 
            '16'=>'Almoxarifado', '00'=>'Todas'
        );

        $mesArray = array(
            '01' => 'Jan', '02' => 'Fev', '03' => 'Mar', '04' => 'Abr', '05' => 'Mai',
            '06' => 'Jun', '07' => 'Jul', '08' => 'Ago', '09' => 'Set', '10' => 'Out',
            '11' => 'Nov', '12' => 'Dez'
        );

        $filial = $request->options === '00' ? "('01', '02', '03', '04', '06', '08', '12', '15', '16')" : "('$request->options')";

        $produtos = new ProdutosRepository($ano, $mes, $filial, $filial);
        $produtos = $produtos->get();

        $export = new ProdutosExport($produtos);

        if($request->options != 0 ) {
            return Excel::download($export, 'RelatórioDeProdutos '. '_'. $filialName[$filial] . '_'. $mesArray[$mes] . '.xlsx');
        } else {
            return Excel::download($export, 'RelatórioDeProdutos '. '_'. $mesArray[$mes] . '.xlsx');
        }
        
        
    }

}


?>