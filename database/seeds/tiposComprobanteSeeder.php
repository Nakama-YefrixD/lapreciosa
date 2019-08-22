<?php

use Illuminate\Database\Seeder;
use App\tiposComprobante;

class tiposComprobanteSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        tiposComprobante::create([
            'codigo' => '01',
            'serie' => 'F001',
            'nombre' => 'FACTURA',
	    'correlativo' => 1
        ]);

        tiposComprobante::create([
            'codigo' => '03',
            'serie' => 'B001',
            'nombre' => 'BOLETA DE VENTA',
	    'correlativo' => 1,
        ]);
    }
}
