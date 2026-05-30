<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        // ADMIN
        $admin = User::firstOrCreate(
            ['email' => 'admin@admin.com'],
            [
                'name' => 'Admin',
                'password' => Hash::make('admin123'),
                'activo' => true,
                'rol' => 'admin'
            ]
        );

        $admin->assignRole('Admin');

        // CAJERO
        $cajero = User::firstOrCreate(
            ['email' => 'cajero@cajero.com'],
            [
                'name' => 'Cajero',
                'password' => Hash::make('cajero123'),
                'activo' => true,
            ]
        );

        $cajero->assignRole('Cajero');

        // ALMACEN
        $almacen = User::firstOrCreate(
            ['email' => 'almacen@almacen.com'],
            [
                'name' => 'Almacen',
                'password' => Hash::make('almacen123'),
                'activo' => true,
            ]
        );

        $almacen->assignRole('Almacen');
    }
}
