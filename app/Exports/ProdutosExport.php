<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithHeadings;

class ProdutosExport implements FromArray, WithHeadings
{
    protected $produtos;
    public function __construct(array $produtos)
    {
        $this->produtos = $produtos;
    }
    /**
    * @return \Illuminate\Support\Collection
    */
    public function array(): array
    {
        return $this->produtos;
    }

    public function headings(): array
    {
        return [
            'Cod. Produto',
            'Produto',
            'Curva',
            'Grupo',
            'Estoque Atual',
            'Consumo',
            'Frequencia',
            'R$ Compra',
            'R$ Venda',
            'CMA %',
            'MKP'
        ];
    }
}
