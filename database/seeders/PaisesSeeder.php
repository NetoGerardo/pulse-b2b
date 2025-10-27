<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PaisesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $path = storage_path() . "/json/paises.json";
        $json = json_decode(file_get_contents($path), true);

        foreach ($json['data'] as $row) {

            if (empty($row)) return false;

            DB::table('paises')->insert(
                array(
                    'id' => $row['id'],
                    'nome' => $row['nome'],
                )
            );
        }
    }
}
