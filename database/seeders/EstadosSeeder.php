<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class EstadosSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $path = storage_path() . "/json/estados.json";
        $json = json_decode(file_get_contents($path), true);

        foreach ($json['data'] as $row) {

            if (empty($row)) return false;

            DB::table('estados')->insert(
                array(
                    'id' => $row['id'],
                    'nome' => $row['nome'],
                    'sigla' => $row['sigla'],
                    'iso' => $row['iso'],
                    'slug' => $row['slug'],
                    'populacao' => $row['populacao'],
                    'pais_id' => $row['pais_id'],
                )
            );
        }
    }
}
