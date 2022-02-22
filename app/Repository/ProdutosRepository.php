<?php

namespace App\Repository;
use Illuminate\Support\Facades\DB;
use App\Repository\Chunks\ProdutosChunk;

class ProdutosRepository {
    private $produtos;
    private $mes, $ano, $filiais, $filialEstoque;
    /**
     * @return DataFromFCerta
    */
    public function __construct($ano, $mes, $filiais, $filialEstoque)
    {
        $this->ano = $ano;
        $this->mes = $mes;
        $this->filiais = $filiais;
        $this->filialEstoque = $filialEstoque;


        $this->produtos();
    }

    private function produtos()
    {

        $this->produtos = DB::table('FC03000')
            ->orderBy('CDPRO')
            ->whereRaw("GRUPO NOT IN ('D', 'O') AND INDDEL = 'N'")
            ->get()
            ->toArray();
    }

    /**
     * @return Array
    */

    public function get()
    {
        $resultArray = Array();

        $produtosChunk = new ProdutosChunk($this->ano, $this->mes, $this->filiais);

        $estoque_table = $produtosChunk->estoque()->get('estoque');
        $consumo_table = $produtosChunk->consumo()->get('consumo');
        $frequencia_table = $produtosChunk->frequencia()->get('frequencia');

        foreach($this->produtos as $produto) {

            $cma = 0;
            $mkp = 0;
            if($produto->PRCOMN != 0 && $produto->PRVEN != 0  && $produto->PRCOM != 0) {
                $cma = ($produto->PRCOMN / $produto->PRVEN) * 100;
                $mkp = $produto->PRVEN / $produto->PRCOM;
            }
            $cma = number_format(($cma), 2, ',', '.');
            $mkp = number_format(($mkp), 2, ',', '.');

            if(isset($consumo_table[$produto->CDPRO])) {
                $consumo = $consumo_table[$produto->CDPRO];
            } else {
                $consumo = "0";
            }

            if($consumo === "0") {
                $frequencia = "0";
            } else if(isset($frequencia_table[$produto->CDPRO])){
                $frequencia = $frequencia_table[$produto->CDPRO];
            } else {
                $frequencia = "0";
            }
            if(isset($estoque_table[$produto->CDPRO])) {
                $estoque = $estoque_table[$produto->CDPRO];
            } else {
                $estoque = "0";
            }

            $res = [
                'cdpro'=> $produto->CDPRO,
                'produto' => $produto->DESCRPRD,
                'curva'=> $produto->CURVA,
                'grupo' => $produto->GRUPO,
                'estoque_atual' => $estoque,
                'consumo' => $consumo,
                'frequencia' => $frequencia,
                'preco_compra' => number_format(($produto->PRCOMN), 2, ',', '.'),
                'preco_venda' => number_format(($produto->PRVEN), 2, ',', '.'),
                'cma' => $cma,
                'mkp' => $mkp
            ];
            array_push($resultArray, $res);
        }

        return $resultArray;
    }
}
