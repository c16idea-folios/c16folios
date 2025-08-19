<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ActsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $acts = [
            ['order' => 1, 'act' => 'Constitución', 'extract' => 'yes'],
            ['order' => 2, 'act' => 'Cotejo', 'extract' => 'yes'],
            ['order' => 3, 'act' => 'Designación', 'extract' => 'yes'],
            ['order' => 4, 'act' => 'Fe de hechos', 'extract' => 'yes'],
            ['order' => 5, 'act' => 'Formalización', 'extract' => 'yes'],
            ['order' => 6, 'act' => 'Notificación', 'extract' => 'yes'],
            ['order' => 7, 'act' => 'Revocación', 'extract' => 'yes'],
            ['order' => 8, 'act' => 'Ratificación', 'extract' => 'yes'],
            ['order' => 9, 'act' => 'Intermediario', 'extract' => 'no'],
            ['order' => 10, 'act' => 'Formalización de Contrato / Convenio', 'extract' => 'yes'],
            ['order' => 11, 'act' => 'Compulsa', 'extract' => 'yes'],
            ['order' => 12, 'act' => 'Certificado de depósitos de acciones', 'extract' => 'yes'],
            ['order' => 13, 'act' => 'Declaraciones mercantiles', 'extract' => 'yes'],
            ['order' => 14, 'act' => 'Comisión mercantil', 'extract' => 'yes'],
        ];

        DB::table('acts')->insert($acts);
    }
}
