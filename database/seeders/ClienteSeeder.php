<?php

namespace Database\Seeders;

use App\Models\Cliente;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ClienteSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $clientes = [
            [
                'user_id' => 1, // <--- Agregado para que no falle la integridad
                'nombre' => 'Clientes Varios',
                'tipo_documento' => 'DNI',
                'numero_documento' => '00000000',
                'telefono' => null,
                'email' => null,
                'direccion' => 'Venta Mostrador',
            ],
            [
                'user_id' => 1, // <--- Registrado por el Admin
                'nombre' => 'Juan Carlos Pérez',
                'tipo_documento' => 'DNI',
                'numero_documento' => '45781236',
                'telefono' => '987654321',
                'email' => 'juan.perez@gmail.com',
                'direccion' => 'Av. Los Próceres 123, Santiago de Surco',
            ],
            [
                'user_id' => 1, // <--- Registrado por el Admin
                'nombre' => 'Inversiones Discoteca Midnight S.A.C.',
                'tipo_documento' => 'RUC',
                'numero_documento' => '20601234567',
                'telefono' => '014455667',
                'email' => 'compras@midnight.pe',
                'direccion' => 'Calle Las Pizzas 456, Miraflores',
            ],
        ];

        foreach ($clientes as $cliente) {
            Cliente::create($cliente);
        }
    }
}
