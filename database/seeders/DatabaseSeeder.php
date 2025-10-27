<?php

namespace Database\Seeders;

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
        $this->call(RolesSeeder::class);
        $this->call(UsersSeeder::class);
        $this->call(OrigensSeeder::class);        
        $this->call(PaisesSeeder::class);        
        $this->call(EstadosSeeder::class);        
        $this->call(CidadesSeeder::class);        
    }
}
