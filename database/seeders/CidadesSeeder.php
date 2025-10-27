<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CidadesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $path = storage_path() . "/json/cidades.json";
        $json = json_decode(file_get_contents($path), true);

        foreach ($json['data'] as $row) {

            if (empty($row)) return false;

            DB::table('cidades')->insert(
                array(
                    'id' => $row['id'],
                    'estado_id' => $row['estado_id'],
                    'nome' => $row['nome'],
                    'iso' => $row['iso'],
                    'iso_ddd' => $row['iso_ddd'],
                    'status' => $row['status'],
                    'slug' => $row['slug'],
                    'populacao' => $row['populacao'],
                    'lat' => $row['lat'],
                    'long' => $row['long'],
                    'renda_per_capita' => $row['renda_per_capita'],
                )
            );
        }
    }
}
