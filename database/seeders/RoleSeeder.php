<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Role;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Role::create([
            'id_rol' => Role::generateRoleId(),
            'tipo_rol' => 'DOCENTE',
            'descripcion' => 'Rol de docente',
        ]);

        Role::create([
            'id_rol' => Role::generateRoleId(),
            'tipo_rol' => 'SUPERVISOR',
            'descripcion' => 'Rol de supervisor',
        ]);

        Role::create([
            'id_rol' => Role::generateRoleId(),
            'tipo_rol' => 'ADMINISTRADOR',
            'descripcion' => 'Rol de administrador',
        ]);
    }
}
