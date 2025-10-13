<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Categoria extends Model
{
    use HasFactory;

    //nombre de la tabla
    protected $table = 't_categorias';

    //campos que se pueden llenar masivamente por el usuario

    protected $fillable = [

        'nombre',
        'estado'
    ];

    //relacion. una categoria tiene muchos productos
    
    public function productos(){
        return $this->hasMany(Producto::class, 'categoria_id');
    }
}

