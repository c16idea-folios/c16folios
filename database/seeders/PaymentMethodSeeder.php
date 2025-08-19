<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PaymentMethodSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('payment_method')->insert([
            [
                'order' => 10,
                'method' => 'Efectivo',
                'acronym' => 'E'
    
            ],
            [
                'order' => 20,
                'method' => 'Banco',
                'acronym' => 'B'
    
            ],
            [
                'order' => 30,
                'method' => 'Transferencia',
                'acronym' => 'TR'
    
            ],
            [
                'order' => 40,
                'method' => 'Tarjeta Débito',
                'acronym' => 'TD'
    
            ],
            [
                'order' => 50,
                'method' => 'Tarjeta Crédito',
                'acronym' => 'TB'
    
            ],
        ]);
    }
}
