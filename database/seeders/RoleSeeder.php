<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;

class RoleSeeder extends Seeder
{
    public function run()
    {
        // Crear roles
        Role::create(['name' => 'administrator']);
        Role::create(['name' => 'operator']);
        Role::create(['name' => 'technical_support']);
    }
}
