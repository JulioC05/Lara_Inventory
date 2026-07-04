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

        $almacen1 = User::create([
            'name' => 'Almacenista Pedro',
            'email' => 'almacen1@licorcontrol.com',
            'password' => Hash::make('Almacen2026'),
        ]);
        
        $almacen1->assignRole('almacen');

        $almacen2 = User::create([
            'name' => 'Almacenista Marta',
            'email' => 'almacen2@licorcontrol.com',
            'password' => Hash::make('Almacen2026'),
        ]);
        $almacen2->assignRole('almacen');


        
        $cajero1 = User::create([
            'name' => 'Cajero Luis',
            'email' => 'cajero1@licorcontrol.com',
            'password' => Hash::make('Cajero2026'),
        ]);
        
        $cajero1->assignRole('cajero');

        $cajero2 = User::create([
            'name' => 'Cajera Sofia',
            'email' => 'cajero2@licorcontrol.com',
            'password' => Hash::make('Cajero2026'),
        ]);
        $cajero2->assignRole('cajero');
    }
}
