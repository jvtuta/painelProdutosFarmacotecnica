<?php

namespace App\Repository\Chunks;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;


class ProdutosChunk {
    private $cdpro, $filialEstoque, $filiais, $ano, $mes, $mesInicio, $mesFim;
    public $estoque;

    public function __construct($cdpro, $filialEstoque, $filiais, $ano, $mes, $mesInicio, $mesFim)
    {
        $this->cdpro = $cdpro;
        $this->filialEstoque = $filialEstoque

        $this->ano = $ano;
        $this->mes = $mes;
        $this->mesInicio = $mesInicio;
        $this->mesFim = $mesFim;

        // tables 
        
    }

    public function estoque_chunk()
    {
        DB::table('FC03140')
            ->orderBy('CDPRO')
            ->chunk(50000, function ($estoque_chunk) {
                $this->estoque += $this->getEstoque($estoque_chunk);
        });

    }

    public function consumo_chunk()
    {
        
    }

    public function frequencia_chunk()
    {

    }

    private function getEstoque(Collection $estoque_chunk, $cdpro) 
    {
    

        
    }

    private function consumo(Collection $consumo_chunk)
    {
        $consumo = $consumo_chunk
            ->where('CDPRO', $this->cdpro)
            ->whereIn("CDFIL", $this->filiais)
            ->where('ANORF', $this->ano)
            ->where('MESRF', $this->mes)
            ->sum('ACSAIDAQT');
        if($consumo) {
            return $consumo;
        } else {
            return '0';
        }                                
    }

    private function frequencia(Collection $frequencia_chunk, $cdpro)
    {        
        $frequencia = $frequencia_chunk            
            ->where('CDPRO', $cdpro)
            ->whereIn("CDFILE", $this->filialEstoque)
            ->whereBetween("DTENTR", [$this->mesInicio, $this->mesFim])
            ->count();
        return $frequencia;
        
    }
}

?>