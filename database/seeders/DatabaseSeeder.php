<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Database\Seeders\UserSeeder; 
use Database\Seeders\RolSeeder; 

class DatabaseSeeder extends Seeder
{
    public function run()
    {
        // Llama a tu seeder específico
        $this->call(RoleSeeder::class);
        $this->call(UserSeeder::class);
        $this->call(ActsSeeder::class);
        $this->call(PaymentMethodSeeder::class);
        $this->call(WorkTeamSeeder::class);
        $this->call(FileTypeSeeder::class);
        $this->call(DenominationsSeeder::class);
        $this->call(NoticeTypeSeeder::class);

    }
}
