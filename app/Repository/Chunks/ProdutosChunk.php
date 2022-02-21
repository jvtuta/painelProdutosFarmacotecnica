<?php

namespace App\Repository\Chunks;
use Illuminate\Support\Facades\DB;


class ProdutosChunk {
    // Carregar produtos no objeto
    // CDPRO deve estar ordenado
    private $estoque, $consumo, $frequencia, $ano, $mes, $filiais;
    public function __construct($ano, $mes, $filiais)
    {
        $this->ano = $ano;
        $this->mes = $mes;
        $this->filiais = $filiais;
    }

    // Todo produto terá um estoque
    // Ao final da operação será construido um array de estoque de cada produto
    // Percorrer array resultante somando o estoque dos produtos repetidos
    public function estoque()
    {
        // $filiais =  "('01', '02', '03', '04', '06', '08', '12', '15', '16')";
        $estoque = [];
        DB::table('FC03140')
            ->orderBy('CDPRO')
            ->whereRaw("CDFIL IN $this->filiais")
            ->chunk(20000, function($chunk_estoque) use (&$estoque) {
                // pegar o índice inicial do chunk que percorrerá o array
                foreach($chunk_estoque as $index => $produto) {
                    /*
                        se o cdpro do anterior for igual ao novo
                        então somar o anterior + o novo e atualizar
                        o valor do anterior no array
                    */

                    //Caso o CDPRO seja diferente do antérior então adicionar novo produto ao array de estoque

                    if(isset($estoque[$produto->CDPRO]) || array_key_exists($produto->CDPRO, $estoque)) {
                        $estoque_anterior = $estoque[$produto->CDPRO];
                    } else {
                        $estoque_anterior = 0;
                    }
                    $estoque_ = $produto->ESTAT - $produto->SAIDATR;

                    $estoque_ = $estoque_anterior + $estoque_;

                    $estoque[$produto->CDPRO] = $estoque_;

                }

            });
        $this->estoque = $estoque;

        $estoque = null;
        return $this;
    }

    public function consumo()
    {
        // $filiais =  "('01', '02', '03', '04', '06', '08', '12', '15', '16')";
        $consumo = [];
        // $ano = 2022;
        // $mes = 01;
        DB::table('FC03110')
            ->orderBy('CDPRO')
            ->whereRaw("CDFIL in $this->filiais")
            ->where('ANORF', $this->ano)
            ->where('MESRF', $this->mes)
            ->chunk(20000, function($chunk_consumo) use(&$consumo) {
                foreach($chunk_consumo as $produto) {

                    if(isset($consumo[$produto->CDPRO]) || array_key_exists($produto->CDPRO, $consumo)) {
                        $consumo_anterior = $consumo[$produto->CDPRO];
                    } else {
                        $consumo_anterior = 0;
                    }

                    $consumo_ = $produto->ACSAIDAQT;

                    $consumo_ = $consumo_anterior + $consumo_;

                    $consumo[$produto->CDPRO] = $consumo_;

                }
            });
        $this->consumo = $consumo;

        $consumo = null;
        return $this;
    }

    public function frequencia()
    {
        $frequencia = [];
        $ultimoDiaMes = date('t', mktime(0, 0, 0, $this->mes, '01', $this->ano));
        $mesFim = $ultimoDiaMes . '.' . $this->mes . '.' . $this->ano;
        $mesInicio = '01.' . $this->mes . '.' . $this->ano;
        DB::table('FC12110')
            ->orderBy('CDPRO')
            ->whereRaw("CDFILE in $this->filiais")
            ->whereRaw("DTENTR BETWEEN cast('$mesInicio' as date) and cast('$mesFim' as date)")
            ->chunk(20000, function($chunk_frequencia) use(&$frequencia) {
                foreach($chunk_frequencia as $produto) {
                    $frequencia_ = 1;
                    if(isset($frequencia[$produto->CDPRO]) || array_key_exists($produto->CDPRO, $frequencia)) {
                        $frequencia_ = $frequencia[$produto->CDPRO] + 1;
                    }
                    $frequencia[$produto->CDPRO] = $frequencia_;
                }
            });
        $this->frequencia = $frequencia;

        $frequencia = null;
        return $this;
    }

    public function get($key)
    {

        return $this->$key;


    }

}


?>
