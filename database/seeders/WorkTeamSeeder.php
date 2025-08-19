<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class WorkTeamSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('work_team')->insert([
            [
                'order' => 10,
                'team' => 'Constituciones',
                'identifier' => '#0585FC'
    
            ],
            [
                'order' => 20,
                'team' => 'Fe de hechos y notificaciones',
                'identifier' => '#6C08D6'
    
            ],
            [
                'order' => 30,
                'team' => 'Corporativo',
                'identifier' => '#CF04D6'
    
            ],
            [
                'order' => 40,
                'team' => 'Admin',
                'identifier' => '#FAAB00'
    
            ],
            [
                'order' => 50,
                'team' => '17',
                'identifier' => '#0DB83D'
    
            ],
        ]);
    }
}
