<?php

use Illuminate\Database\Seeder;
use App\tiposMoneda;

class tiposMonedaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        tiposMoneda::create([
            'codigo' => '1',
            'simbolo' => 'S/',
            'abreviatura' => 'PEN',
            'nombre' => 'NUEVOS SOLES',
        ]);

        tiposMoneda::create([
            'codigo' => '2',
            'simbolo' => '$',
            'abreviatura' => 'USD',
            'nombre' => 'DÓLARES AMERICANOS',
        ]);
    }
}
