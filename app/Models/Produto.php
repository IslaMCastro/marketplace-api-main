<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Produto extends Model
{
    use HasFactory;

    protected $fillable = ['nome','descricao','preco', 'estoque','tipo_id',];


    public function tipo()
    {
        return $this->belongsTo(Tipo::class,"tipo_id");
    }
}
