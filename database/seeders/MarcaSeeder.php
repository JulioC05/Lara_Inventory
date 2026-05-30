<?php

namespace Database\Seeders;

use App\Models\Marca;
use Illuminate\Database\Seeder;

class MarcaSeeder extends Seeder
{
    public function run(): void
    {
        $marcas = [
            'Cusqueña',
            'Pilsen',
            'Corona',
            'Heineken',
            'Budweiser',

            'Johnnie Walker',
            'Jack Daniel\'s',
            'Chivas Regal',
            'Ballantine\'s',

            'Havana Club',
            'Cartavio',
            'Bacardí',

            'Absolut',
            'Smirnoff',

            'Casillero del Diablo',
            'Tacama',
            'Tabernero',
            'Santiago Queirolo',

            'Red Bull',
            'Volt',
            'Monster',

            'Lays',
            'Pringles',
            'Doritos',
        ];

        foreach ($marcas as $marca) {
            Marca::firstOrCreate(
                ['nombre' => $marca],
                [
                    'estado' => true,
                    'user_id' => 1,
                ]
            );
        }
    }
}