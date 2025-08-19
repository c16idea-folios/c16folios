<?php
namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use App\Models\User;

class UserSeeder extends Seeder
{
    public function run()
    {
      
        // Crear un usuario
        $user = User::create([
            'username' => 'DESARROLLO',
            'name' => 'DESARROLLO',
            'last_name' => 'DESARROLLO',
            'second_last_name' => 'DESARROLLO',
         
            'email' => 'desarrollo@example.com',
            'password' => bcrypt('DESARROLLO'),
            'is_active' => 1,
        ]);

        // Asignar roles
        $user->assignRole('technical_support');
    }
}
