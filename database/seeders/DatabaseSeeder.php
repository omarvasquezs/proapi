<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Crear roles y permisos
        $this->call([
            RolePermissionSeeder::class,
            AdminUserSeeder::class,
        ]);
        
        // Si estamos en ambiente de desarrollo, crear usuarios adicionales
        if (app()->environment('local', 'development')) {
            // Crear usuario normal para pruebas
            \App\Models\User::factory()->create([
                'name' => 'Usuario Prueba',
                'username' => 'usuario_test',
                'email' => 'user@example.com',
                'password' => bcrypt('password'),
            ])->assignRole('usuario');
            
            // Crear usuarios adicionales si se necesitan para desarrollo
            // \App\Models\User::factory(10)->create();
        }
    }
} 