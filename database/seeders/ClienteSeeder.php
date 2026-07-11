<?php

namespace Database\Seeders;

use App\Models\Cliente;
use Illuminate\Database\Seeder;

class ClienteSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $clientes = [
            // CLIENTE COMODÍN (Para boletas menores a S/ 700)
            [
                'user_id' => 1,
                'tipo_persona' => 'natural',
                'tipo_documento' => 'DNI',
                'numero_documento' => '00000000',
                'nombre' => 'Cliente',
                'apellido' => 'Varios',
                'razon_social' => null,
                'nombre_comercial' => null,
                'telefono' => null,
                'direccion' => null,
                'email' => null,
                'estado' => true,
            ],
            // PERSONAS NATURALES (SÓLO DNI)
            [
                'user_id' => 1,
                'tipo_persona' => 'natural',
                'tipo_documento' => 'DNI',
                'numero_documento' => '74851236',
                'nombre' => 'Juan',
                'apellido' => 'Pérez',
                'razon_social' => null,
                'nombre_comercial' => null,
                'telefono' => '987654321',
                'direccion' => 'Lima',
                'email' => 'juan@example.com',
                'estado' => true,
            ],
            [
                'user_id' => 1,
                'tipo_persona' => 'natural',
                'tipo_documento' => 'DNI',
                'numero_documento' => '74125896',
                'nombre' => 'María',
                'apellido' => 'López',
                'razon_social' => null,
                'nombre_comercial' => null,
                'telefono' => '912345678',
                'direccion' => 'Lima',
                'email' => 'maria@example.com',
                'estado' => true,
            ],
            // 🔥 PERSONAS NATURALES CON NEGOCIO (RUC 10)
            // Tienen Nombre/Apellido obligatorios y opcionalmente Nombre Comercial (el de su tienda)
            [
                'user_id' => 1,
                'tipo_persona' => 'natural',
                'tipo_documento' => 'RUC',
                'numero_documento' => '10456789123',
                'nombre' => 'Carlos',
                'apellido' => 'Mendoza',
                'razon_social' => null,
                'nombre_comercial' => 'Bodega El Centro', // <--- Aquí calza perfecto tu columna
                'telefono' => '955123456',
                'direccion' => 'Los Olivos',
                'email' => 'carlos.mendoza@email.com',
                'estado' => true,
            ],
            [
                'user_id' => 1,
                'tipo_persona' => 'natural',
                'tipo_documento' => 'RUC',
                'numero_documento' => '10741258963',
                'nombre' => 'Ana',
                'apellido' => 'Gómez',
                'razon_social' => null,
                'nombre_comercial' => 'Licorería Ana',
                'telefono' => '966321456',
                'direccion' => 'Miraflores',
                'email' => 'ana.gomez@email.com',
                'estado' => true,
            ],
            // PERSONAS JURÍDICAS (RUC 20)
            // No llevan Nombre/Apellido. Llevan Razón Social, Contacto y opcionalmente Nombre Comercial
            [
                'user_id' => 1,
                'tipo_persona' => 'juridica',
                'tipo_documento' => 'RUC',
                'numero_documento' => '20123456789',
                'nombre' => null,
                'apellido' => null,
                'razon_social' => 'Inversiones Luna SAC',
                'nombre_comercial' => 'Distribuidora Luna',
                'contacto_nombre' => 'Jorge Luna',
                'contacto_cargo' => 'Gerente General',
                'telefono' => '915555555',
                'direccion' => 'San Isidro',
                'email' => 'contacto@luna.com',
                'estado' => true,
            ],
            [
                'user_id' => 1,
                'tipo_persona' => 'juridica',
                'tipo_documento' => 'RUC',
                'numero_documento' => '20789456123',
                'nombre' => null,
                'apellido' => null,
                'razon_social' => 'Distribuidores del Sur EIRL',
                'nombre_comercial' => 'Almacenes del Sur',
                'contacto_nombre' => 'Luis Estrada',
                'contacto_cargo' => 'Administrador',
                'telefono' => '944888777',
                'direccion' => 'Santiago de Surco',
                'email' => 'ventas@distsur.com',
                'estado' => true,
            ],
        ];

        foreach ($clientes as $cliente) {
            Cliente::create($cliente);
        }
    }
}
