<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Produtos extends Model
{
    use HasFactory;

    protected $fillable = [
        'cdpro',
        'produto',
        'curva',
        'grupo',
        'estoque_atual',
        'consumo',
        'frequencia',
        'preco_compra',
        'preco_venda',
        'cma',
        'mkp'
    ];
    
}
