<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DenominationsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('denominations')->insert([
            ['order' => 10, 'acronym' => 'A.C', 'denomination' => 'Asociación Civil'],
            ['order' => 20, 'acronym' => 'S.C.', 'denomination' => 'Sociedad Civil'],
            ['order' => 30, 'acronym' => 'S. EN C.', 'denomination' => 'Sociedad en Comandita Simple'],
            ['order' => 40, 'acronym' => 'S. EN C. POR A.', 'denomination' => 'Sociedad en Comandita por Acciones'],
            ['order' => 50, 'acronym' => 'S.A.', 'denomination' => 'Sociedad Anónima'],
            ['order' => 55, 'acronym' => 'S.A. DE C.V.', 'denomination' => 'Sociedad Anónima de Capital Variable'],
            ['order' => 60, 'acronym' => 'S. DE R. L.', 'denomination' => 'Sociedad de Responsabilidad Limitada'],
            ['order' => 65, 'acronym' => 'S. DE R. L. DE C.V.', 'denomination' => 'Sociedad de Responsabilidad Limitada de Capital Variable'],
            ['order' => 70, 'acronym' => 'S. C. L', 'denomination' => 'Sociedad Cooperativa'],
            ['order' => 80, 'acronym' => 'S. C. S', 'denomination' => 'Sociedad Cooperativa Suplementada'],
            ['order' => 90, 'acronym' => 'A. P.', 'denomination' => 'Asociación en Participación'],
            ['order' => 100, 'acronym' => 'S. DE R. L. DE I. P.', 'denomination' => 'Sociedad de Responsabilidad Limitada de Interés Público'],
            ['order' => 110, 'acronym' => 'S. N. C.', 'denomination' => 'Sociedad Nacional de Crédito'],
            ['order' => 120, 'acronym' => 'A. F.', 'denomination' => 'Agrupación Financiera'],
            ['order' => 130, 'acronym' => 'SOFOL', 'denomination' => 'Sociedad Financiera de Objeto Limitado'],
            ['order' => 140, 'acronym' => 'AFORE', 'denomination' => 'Administradoras de Fondos para el Retiro'],
            ['order' => 150, 'acronym' => 'SIEFORE', 'denomination' => 'Sociedad de inversión especializada de fondos para el retiro'],
            ['order' => 160, 'acronym' => 'S.A.P.I.', 'denomination' => 'Sociedad Anónima Promotora de Inversión'],
            ['order' => 170, 'acronym' => 'E.P.E.', 'denomination' => 'Empresa Productiva del Estado'],
            ['order' => 180, 'acronym' => 'S.A. I.B.M.', 'denomination' => 'Sociedad Anónima, Institución de Banca Múltiple'],
            ['order' => 190, 'acronym' => 'S.A.P.I. de C.V.', 'denomination' => 'Sociedad Anónima Promotora de Inversión de Capital Variable'],
            ['order' => 200, 'acronym' => 'S.A. de C.V., SOFOM, E.N.R.', 'denomination' => 'Sociedad Anónima de Capital Variable, Sociedad Financiera de Objeto Múltiple, Entidad No Regulada'],
            ['order' => 210, 'acronym' => 'S.P.R. de R.L.', 'denomination' => 'Producción Rural de responsabilidad limitada'],
            ['order' => 220, 'acronym' => 'S.P.R. de R.L. de C.V.', 'denomination' => 'Sociedad de Producción Rural de Responsabilidad Limitada de Capital Variable'],
            ['order' => 230, 'acronym' => 'S. A. O. A. C.', 'denomination' => 'Sociedad Anónima Organización Auxiliar del Crédito'],
            ['order' => 240, 'acronym' => 'SAPI DE CV, SOFOM ENR', 'denomination' => 'Sociedad Anónima Promotora de Inversión de Capital Variable, Sociedad Financiera de Objeto Múltiple, Entidad no regulada'],
            ['order' => 250, 'acronym' => 'S. N. E.', 'denomination' => 'Sociedad de Nacionalidad Extranjera'],
            ['order' => 260, 'acronym' => 'S.A.S. DE C.V.', 'denomination' => 'Sociedad por Acciones Simplificada de Capital Variable'],
            ['order' => 270, 'acronym' => 'S.A.S.', 'denomination' => 'Sociedad por Acciones Simplificada'],
            ['order' => 280, 'acronym' => 'S.A.S. DE C.V.', 'denomination' => 'Sociedad por Acciones Simplificada de Capital Variable'],
            ['order' => 290, 'acronym' => 'S.A. DE C.V. S.O.F.O.M. E.R.', 'denomination' => 'Sociedad Anónima de Capital Variable, Sociedad Financiera de Objeto Múltiple, Entidad Regulada'],
            ['order' => 300, 'acronym' => 'S.A.B. DE C.V.', 'denomination' => 'Sociedad Anónima Bursátil de Capital Variable'],
            ['order' => 310, 'acronym' => 'S. C. de C. de R.L. de C.V.', 'denomination' => 'Sociedad cooperativa de consumo de responsabilidad limitada de capital variable'],
            ['order' => 320, 'acronym' => 'S.C. DE P. DE R.L. DE C.V.', 'denomination' => 'Sociedad Cooperativa de Productores de Responsabilidad Limitada de Capital Variable'],
            ['order' => 330, 'acronym' => 'S.A.B. DE C.V. SOFOM E.N.R.', 'denomination' => 'Sociedad Anónima Bursátil de Capital Variable, Sociedad Financiera de Objeto Múltiple, Entidad no regulada'],
            ['order' => 340, 'acronym' => 'S. C. DE R. L.', 'denomination' => 'Sociedad Cooperativa de Responsabilidad Limitada'],
            ['order' => 350, 'acronym' => 'S.N.C., I.B.D.', 'denomination' => 'Sociedad Nacional de Crédito, Institución de Banca de Desarrollo'],
            ['order' => 360, 'acronym' => 'S. en N.C. de C.V.', 'denomination' => 'Sociedad en Nombre Colectivo de Capital Variable'],
            ['order' => 370, 'acronym' => 'S.A.P.I. DE C.V. SOFOM, E.N.R.', 'denomination' => 'Sociedad Anónima Promotora de Inversión de Capital Variable, Sociedad Financiera de Objeto Múltiple, Entidad No Regulada'],
            ['order' => 390, 'acronym' => 'I. DE G. S.A.', 'denomination' => 'Institución de Garantías Sociedad Anónima'],
            ['order' => 400, 'acronym' => 'S.A. DE C.V. O.A.C.', 'denomination' => 'Sociedad Anónima de Capital Variable, Organización Auxiliar del Crédito'],
        ]);
    }
}
