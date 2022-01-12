<?php

namespace App\Repository;
use Illuminate\Support\Facades\DB;

use function PHPUnit\Framework\isEmpty;

class ProdutosRepository extends Repository {
    private $produtos;
    private $mes, $ultimoDiaMes, $mesFim, $ano, $filiais, $filialEstoque;
    /**
     * @return DataFromFCerta
    */
    public function __construct($ano, $mes, $filiais, $filialEstoque) 
    {
        $this->ano = $ano;
        $this->mes = $mes;
        $this->filiais = $filiais;
        $this->filialEstoque = $filialEstoque;
        $this->ultimoDiaMes = date('t', mktime(0, 0, 0, $mes, '01', $ano));
        $this->mesFim = $this->ultimoDiaMes . '.' . $mes . '.' . $ano;
        $this->mesInicio = '01.' . $mes . '.' . $ano;
        
        $this->produtos();
    }

    private function produtos()
    {
        
        $this->produtos = DB::table('FC03000')
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
        foreach($this->produtos as $produto) {
            $cma = 0;
            if($produto->PRVEN == '0') {
                $cma = ($produto->PRCOMN / $produto->PRVEN) * 100;
            }   
            $cma = number_format(($cma), 2, ',', '.');
            $mkp = 0;
            if($produto->PRCOM == '0' {
                $mkp = ($produto->PRVEN / $produto->PRCOM);
            }            
            $mkp = number_format(($mkp), 2, ',', '.');
            $consumo = $this->consumo($produto);
            $res = [
                'cdpro'=> $produto->CDPRO,
                'produto' => $produto->DESCRPRD,
                'curva'=> $produto->CURVA,
                'grupo' => $produto->GRUPO,
                'estoque_atual' => $this->estoque($produto),
                'consumo' => $consumo,
                'frequencia' => $this->frequencia($produto, $consumo),
                'preco_compra' => number_format(($produto->PRCOMN), 2, ',', '.'),
                'preco_venda' => number_format(($produto->PRVEN), 2, ',', '.'),
                'cma' => $cma,
                'mkp' => $mkp
            ];
            array_push($resultArray, $res);
        }
        
        return $resultArray;
    }

    /**
     * @param CDPRO_PRODUTO_
     * @return ESTOQUE_PRODUTO_
     */
    private function estoque($produto) 
    {
        $estoque = DB::table('FC03140')
            ->where('CDPRO', $produto->CDPRO)
            ->whereRaw("CDFIL IN $this->filiais")
            ->get();
        $estoque = $estoque->pipe( function($produto) {
            $resultado = collect([
                'sum_estat'=>$produto->sum('ESTAT'),
                'sum_saidatr'=>$produto->sum('SAIDATR')
            ]);
            $resultado = $resultado->toArray();
            return $resultado['sum_estat'] - $resultado['sum_saidatr'];            
        });

        if($estoque) {
            return $estoque;                                                                                
        } else {
            return '0';
        }
        
    }
    /**
     * @param CDPRO_DO_PRODUTO_
     */
    private function consumo($produto)
    {
        $consumo = DB::table('FC03110')
            ->where('CDPRO', $produto->CDPRO)
            ->whereRaw("CDFIL in $this->filiais")
            ->where('ANORF', $this->ano)
            ->where('MESRF', $this->mes)
            ->get()->sum('ACSAIDAQT');
        if($consumo) {
            return $consumo;
        } else {
            return '0';
        }
                                
    }
    /**
     * @param CDPRO_PRODUTO_
     * @param CONSUMO_PRODUTO_
     */
    private function frequencia($produto, $consumo)
    {        
        $frequencia = Array();
        if($consumo > 0 ) {
            $frequencia = DB::table('FC12110')
            ->select(DB::raw('COUNT(DISTINCT(NRRQU))'))
            ->where('CDPRO', $produto->CDPRO)
            ->whereRaw("CDFILE in $this->filialEstoque")
            ->whereRaw("DTENTR BETWEEN cast('$this->mesInicio' as date) and cast('$this->mesFim' as date)")
            ->get()->toArray();
        }

        if(isset($frequencia[0]->COUNT)) {
            return $frequencia[0]->COUNT;
        } else {
            return '0';
        }
        
    }
}