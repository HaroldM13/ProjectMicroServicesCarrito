<?php

namespace Database\Seeders;
use App\Models\Categoria;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CategoriaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
     
        $categorias = [
            ['nombre' => 'Alimentos', 'estado' => 'A'],
            ['nombre' => 'Tecnologia', 'estado' => 'A'],
            ['nombre' => 'Jugueteria', 'estado' => 'A']
        ];

        foreach ($categorias as $categoria) {
            Categoria::create($categoria);
        }
    
    }
}
