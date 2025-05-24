<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;

class PermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Grupos de permisos y sus permisos
        $permissionGroups = [
            'usuarios' => [
                'ver-usuarios',
                'crear-usuarios',
                'editar-usuarios',
                'eliminar-usuarios',
                'asignar-roles',
            ],
            'roles' => [
                'ver-roles',
                'crear-roles',
                'editar-roles',
                'eliminar-roles',
            ],
            'configuracion' => [
                'ver-configuracion',
                'editar-configuracion',
            ],
            'reportes' => [
                'ver-reportes',
                'exportar-reportes',
            ],
            'logs' => [
                'ver-logs',
                'limpiar-logs',
            ],
        ];

        // Crear todos los permisos
        $allPermissions = [];
        foreach ($permissionGroups as $group => $permissions) {
            foreach ($permissions as $permission) {
                Permission::create(['name' => $permission]);
                $allPermissions[] = $permission;
                $this->command->info("Permiso '{$permission}' creado");
            }
        }
    }
} 