<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AdminUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Crear usuario administrador principal
        $admin = User::create([
            'name' => 'Super Admin',
            'username' => 'mario',
            'email' => 'mario@gmail.com',
            'password' => Hash::make('desconocida4*'),
        ]);

        // Asignar rol admin
        $admin->assignRole('admin');
        
        $this->command->info('Usuario administrador creado: username=superadmin / password=superadmin123');
    }
} 