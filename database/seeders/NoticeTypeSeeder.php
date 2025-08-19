<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class NoticeTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('notice_type')->insert([
            [
                'id' => 1,
                'act_id' => 1,
                'type' => 'Aviso de uso',
                'days' => 180,
                'foreigners' => 'no',
                'observations' => null,
    
            ],
            [
                'id' => 2,
                'act_id' => 1,
                'type' => 'Declaranot socios',
                'days' => 10,
                'foreigners' => 'no',
                'observations' => null,
              
            ],
            [
                'id' => 3,
                'act_id' => 1,
                'type' => 'Declaranot de omisión',
                'days' => 30,
                'foreigners' => 'no',
                'observations' => null,
 
            ],
            [
                'id' => 4,
                'act_id' => 1,
                'type' => 'Inversión extranjera',
                'days' => 10,
                'foreigners' => 'yes',
                'observations' => null,
          
            ],
            [
                'id' => 5,
                'act_id' => 1,
                'type' => 'Sipac',
                'days' => 10,
                'foreigners' => 'no',
                'observations' => null,
  
            ],
            [
                'id' => 6,
                'act_id' => 1,
                'type' => 'Antilavado',
                'days' => 30,
                'foreigners' => 'no',
                'observations' => null,
       
            ],
            [
                'id' => 7,
                'act_id' => 3,
                'type' => 'Inversión extranjera',
                'days' => 10,
                'foreigners' => 'yes',
                'observations' => null,
              
            ],
        ]);
    }
}
