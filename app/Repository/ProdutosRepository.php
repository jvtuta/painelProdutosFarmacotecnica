<?php

namespace App\Repository;
use Illuminate\Support\Facades\DB;


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
            ->select()
            ->whereRaw("GRUPO NOT IN ('D', 'O') AND INDDEL = 'N'")
            ->get()
            ->toArray();
    }

    /**
     * @return Produtos    
    */

    public function get()
    {
        $resultArray = Array();
        foreach($this->produtos as $produto) {
            $cma = @($produto->PRCOMN / $produto->PRVEN) * 100;
            $cma = number_format(($cma), 2, ',', '.');
            $mkp = @($produto->PRVEN / $produto->PRCOM);
            $mkp = number_format(($mkp), 2, ',', '.');
            $consumo = $this->consumo($produto);
            $res = [
                'Cod. Produto'=> $produto->CDPRO,
                'Produto' => $produto->DESCRPRD,
                'Curva'=> $produto->CURVA,
                'Grupo' => $produto->GRUPO,
                'Estoque Atual' => $this->estoque($produto),
                'Consumo' => $consumo,
                'Frequencia' => $this->frequencia($produto, $consumo),
                'R$ Compra' => number_format(($produto->PRCOMN), 2, ',', '.'),
                'R$ Venda' => number_format(($produto->PRVEN), 2, ',', '.'),
                'CMA %' => $cma,
                'MKP' => $mkp
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
            ->select()
            ->where('CDPRO', $produto->CDPRO)
            ->whereRaw("CDFIL IN $this->filiais")
            ->get()->sum('ESTAT - SAIDATR');
        return $estoque;                                                                            
    }
    /**
     * @param CDPRO_DO_PRODUTO_
     */
    private function consumo($produto)
    {
        $consumo = DB::table('FC03110')
            ->select()
            ->where('CDPRO', $produto->CDPRO)
            ->whereRaw("CDFIL in $this->filiais")
            ->where('ANORF', $this->ano)
            ->where('MESRF', $this->mes)
            ->get()->sum('ACSAIDAQT');
        
        return $consumo;                        
    }
    /**
     * @param CDPRO_PRODUTO_
     * @param CONSUMO_PRODUTO_
     */
    private function frequencia($produto, $consumo)
    {
        if($consumo == '0') {
            return 0;
        }
        $frequencia = DB::table('FC12110')
            ->select(DB::raw('COUNT(DISTINCT(NRRQU))'))
            ->where('CDPRO', $produto->CDPRO)
            ->whereRaw("CDFILE in $this->filialEstoque")
            ->whereRaw("DTENTR BETWEEN cast('$this->mesInicio' as date) and cast('$this->mesFim' as date)")
            ->get()->toArray();
        return $frequencia[0]->COUNT;
    }
}