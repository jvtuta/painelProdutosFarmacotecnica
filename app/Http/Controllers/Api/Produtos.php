<?php
namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Repository\ProdutosRepository;
use App\Exports\ProdutosExport;
use Maatwebsite\Excel\Facades\Excel;


class Produtos extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $produtos = null;
        $export = null;

        $data = explode('-', $request->date_val);
        $ano = $data[0];
        $mes = $data[1];
        if(empty($request->options)) {
            $request->options = '00';
        }

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
            $name = 'Relatorio_produtos'. '_'. $filialName[$request->options] . '_'. $mesArray[$mes] .'_'. $ano. '.xlsx';
        } else {
            $name = 'Relatorio_produtos'. '_'. $mesArray[$mes] .'_'. $ano .'.xlsx';
        }

        $filename = 'storage/'.$name;

        if(file_exists($filename))
            unlink($filename);

        Excel::store($export, $name,'public');

        return response()->json($name);

    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}




?>
