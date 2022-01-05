<?php

namespace App\Repository;

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
        $this->mesFim = $ultimoDiaMes . '.' . $mes . '.' . $ano;
        $this->mesInicio = '01.' . $mes . '.' . $ano;

        $this->produtos();
    }

    public function produtos()
    {
        $this->produtos = DB::table('FC03000')
            ->select()
            ->whereRaw("GRUPO NOT IN ('D', 'O') AND INDDEL = 'N'")
            ->count();
        return;     
    }

    /**
     * @return Produtos    
    */

    public function getProdutos()
    {
        return $this->produtos;
    }

    /**
     * @param CDPRO_PRODUTO_
     * @return ESTOQUE_PRODUTO_
     */
    public function estoque($produto) 
    {
        $estoque = DB::table('FC03140')
            ->selectRaw('SUM(ESTAT - SAIDATR)')
            ->where('CDPRO', "'$produto->CDPRO'")
            ->whereRaw("CDFIL IN '$this->filiais'")
            ->get();
        return $estoque->SUM;                                                                            
    }
    /**
     * @param CDPRO_DO_PRODUTO_
     */
    public function consumo($produto)
    {
        $consumo = DB::table('FC03110')
            ->selectRaw('SUM(ACSAIDAQT)')
            ->where('CDPRO', "'$produto->CDPRO'")
            ->whereRaw("CDFIL in $this->filiais")
            ->where('ANORF', "'$this->ano'")
            ->where('MESRF', "'$this->mes'")
            ->get();
        return $consumo->SUM;                        
    }
    /**
     * @param CDPRO_PRODUTO_
     * @param CONSUMO_PRODUTO_
     */
    public function frequencia($produto, $consumo)
    {
        if($consumo->SUM == '0') {
            return;
        }
        $frequencia = DB::table('FC12110')
            ->selectRaw("COUNT(DISTINCT(NRRQU))")
            ->where('CDPRO', "'$produto->CDPRO'")
            ->whereRaw("CDFILE in $this->filialEstoque")
            ->whereRaw("DTENTR BETWEEN '$this->mesInicio' AND '$this->mesFim'")
            ->get();
        return $frequencia->COUNT;
    }
}