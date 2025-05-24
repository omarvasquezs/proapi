<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RolePermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Ejecutar seeders específicos
        $this->call([
            RoleSeeder::class,
            PermissionSeeder::class,
        ]);
        
        // Asignar permisos a roles
        $this->assignPermissionsToRoles();
    }
    
    /**
     * Asignar permisos a roles específicos
     */
    private function assignPermissionsToRoles(): void
    {
        // Asignar todos los permisos al rol admin
        $adminRole = Role::findByName('admin');
        $adminRole->givePermissionTo(Permission::all());
        $this->command->info('Al rol "admin" se le asignaron todos los permisos');
        
        // Asignar permisos básicos al rol usuario
        $userRole = Role::findByName('usuario');
        $userRole->givePermissionTo([
            'ver-usuarios',
            'ver-reportes',
        ]);
        $this->command->info('Al rol "usuario" se le asignaron permisos básicos');
        
        // Asignar permisos al rol editor
        $editorRole = Role::findByName('editor');
        $editorRole->givePermissionTo([
            'ver-usuarios',
            'ver-roles',
            'ver-reportes',
            'exportar-reportes',
        ]);
        $this->command->info('Al rol "editor" se le asignaron permisos de edición');
        
        // Asignar permisos al rol supervisor
        $supervisorRole = Role::findByName('supervisor');
        $supervisorRole->givePermissionTo([
            'ver-usuarios',
            'ver-roles',
            'ver-reportes',
            'exportar-reportes',
            'ver-logs',
        ]);
        $this->command->info('Al rol "supervisor" se le asignaron permisos de supervisión');
        
        // Asignar permisos al rol invitado
        $invitadoRole = Role::findByName('invitado');
        $invitadoRole->givePermissionTo([
            'ver-usuarios',
            'ver-reportes',
        ]);
        $this->command->info('Al rol "invitado" se le asignaron permisos de lectura');
    }
} 