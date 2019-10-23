<?php

use Illuminate\Database\Seeder;
use App\tiposDocumento;

class tiposDocumentoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        tiposDocumento::create([
            'codigo' => '1',
            'abreviacion' => 'DNI',
            'nombre' => 'DOCUMENTO NACIONAL DE IDENTIDAD',
        ]);
        tiposDocumento::create([
            'codigo' => '4',
            'abreviacion' => 'CARNET DE EXTRANJERIA',
            'nombre' => 'CARNET DE EXTRANJERIA',
        ]);
        tiposDocumento::create([
            'codigo' => '6',
            'abreviacion' => 'RUC',
            'nombre' => 'REGISTRO ÚNICO DE CONTRIBUYENTES',
        ]);
    }
}
