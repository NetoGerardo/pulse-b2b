<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;
use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Container;
use App\Models\Origem;

class OrigensSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

        //CREATE FIRST ORIGEM
        Origem::create([
            'nome' => 'Cadastro Manual',
            'categoria' => 'Bronze',
        ]);

        Origem::create([
            'nome' => 'Google Oficial',
            'categoria' => 'Ouro',
        ]);

        Origem::create([
            'nome' => 'Nome/Telefone/CNPJ',
            'categoria' => 'Bronze',
        ]);

        Origem::create([
            'nome' => 'Martins',
            'categoria' => 'Bronze',
        ]);
    }
}
