<?php

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $this->call(tiposComprobanteSeeder::class);
        $this->call(tiposDocumentoSeeder::class);
        $this->call(tiposMonedaSeeder::class);
    }
}
