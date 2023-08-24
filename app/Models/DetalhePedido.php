<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DetalhePedido extends Model
{
    use HasFactory;

    protected $fillable = ['pedido_id','produto_id','quantidade', 'preco','total'];
}
