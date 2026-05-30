<?php

namespace Database\Seeders;

use App\Models\Categoria;
use Illuminate\Database\Seeder;

class CategoriaSeeder extends Seeder
{
    public function run(): void
    {
        $categorias = [
            'Cervezas',
            'Whisky',
            'Ron',
            'Vodka',
            'Vino Tinto',
            'Vino Blanco',
            'Vino Rosado',
            'Energizantes',
            'Snacks',
        ];

        foreach ($categorias as $categoria) {
            Categoria::firstOrCreate(
                ['nombre' => $categoria],
                [
                    'estado' => true,
                    'user_id' => 1,
                ]
            );
        }
    }
}