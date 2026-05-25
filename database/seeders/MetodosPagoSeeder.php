<?php

namespace Database\Seeders;

use App\Models\MetodoPago;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class MetodosPagoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
    {
        $metodos = [
            ['nombre' => 'Efectivo', 'user_id' => 1],
            ['nombre' => 'Yape', 'user_id' => 1],
            ['nombre' => 'Plin', 'user_id' => 1],
            ['nombre' => 'Tarjeta Débito', 'user_id' => 1],
            ['nombre' => 'Tarjeta Crédito', 'user_id' => 1],
            ['nombre' => 'Transferencia Bancaria', 'user_id' => 1],
        ];

        foreach ($metodos as $metodo) {
            MetodoPago::create($metodo);
        }
    }
}
