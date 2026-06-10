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
                'nombre' => 'Cliente Varios',
                'tipo_documento' => 'DNI',
                'numero_documento' => '00000000',
                'telefono' => null,
                'direccion' => null,
                'email' => null,
                'user_id' => 1,
                'estado' => true,
            ],

            [
                'nombre' => 'Juan Pérez',
                'tipo_documento' => 'DNI',
                'numero_documento' => '74851236',
                'telefono' => '987654321',
                'direccion' => 'Lima',
                'email' => 'juan@example.com',
                'user_id' => 1,
                'estado' => true,
            ],

            [
                'nombre' => 'María López',
                'tipo_documento' => 'DNI',
                'numero_documento' => '74125896',
                'telefono' => '912345678',
                'direccion' => 'Lima',
                'email' => 'maria@example.com',
                'user_id' => 1,
                'estado' => true,
            ],

            [
                'razon_social' => 'Inversiones Luna SAC',
                'tipo_documento' => 'RUC',
                'tipo_persona' => 'juridica',
                'numero_documento' => '20123456789',
                'telefono' => '915555555',
                'direccion' => 'San Isidro',
                'email' => 'contacto@luna.com',
                'user_id' => 1,
                'estado' => true,
            ],
        ];

        foreach ($clientes as $cliente) {
            Cliente::create($cliente);
        }
    }
}
