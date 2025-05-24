<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Crear roles básicos del sistema
        $roles = [
            'admin' => 'Administrador con acceso completo al sistema',
            'usuario' => 'Usuario con acceso limitado a funciones básicas',
            'editor' => 'Usuario con permisos de edición de contenido',
            'supervisor' => 'Usuario con permisos de supervisión y reportes',
            'invitado' => 'Usuario con permisos solo de lectura',
        ];

        foreach ($roles as $name => $description) {
            Role::create([
                'name' => $name, 
                'guard_name' => 'web',
            ]);
            $this->command->info("Rol '{$name}' creado");
        }
    }
} 