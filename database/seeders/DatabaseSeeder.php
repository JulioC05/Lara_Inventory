<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        User::firstOrCreate(
            ['email' => env('ADMIN_EMAIL', 'admin@admin.com')],
            [
                'name' => 'Admin',
                'password' => Hash::make(env('ADMIN_PASSWORD', 'admin123')),
                'activo' => true,
                'rol' => 'admin'
            ]
        );
        $this->call([
            MetodosPagoSeeder::class,
            ClienteSeeder::class,
            // Aquí irán tus otros seeders más adelante...
        ]);
    }
}
