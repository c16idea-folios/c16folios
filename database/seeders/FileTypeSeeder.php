<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class FileTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('file_type')->insert([
            [
                'order' => 10,
                'type' => 'Acta'
            ],
            [
                'order' => 20,
                'type' => 'Poliza'
            ],
            [
                'order' => 30,
                'type' => 'INE'
            ],
            [
                'order' => 40,
                'type' => 'Acta Constitutiva'
            ],
            [
                'order' => 50,
                'type' => 'Comprobante de pago'
            ],       [
                'order' => 60,
                'type' => 'Factura'
            ],
            [
                'order' => 70,
                'type' => 'Complemento de pago'
            ]
            ],
        );
    }
}
