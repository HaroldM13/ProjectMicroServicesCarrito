<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;



class Producto extends Model
{
    use HasFactory;

    protected $table = 't_productos';

    protected $fillable = [

        'nombre',
        'categoria_id',
        'stock',
        'precio',
        'estado'
    ];

    // casts que castie el valor ingresado sea en precio a decimal o el stock a entero
    protected $casts = [
        'precio' => 'float',
        'stock' => 'integer'
    ];

    public function categoria(){
        return $this -> belongsTo(Categoria::class, 'categoria_id');
    }
    
}
