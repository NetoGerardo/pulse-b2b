<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;
use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Container;
use App\Models\Origem;

class UsersSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $admin = User::create([
            'name' => 'Admin',
            'email' => 'admin@whatsz.com',
            'password' => Hash::make('admin'),
        ]);

        $user = User::create([
            'name' => 'User',
            'email' => 'user@whatsz.com',
            'password' => Hash::make('user'),
        ]);

        $admin_role = Role::findByName('admin');

        //CREATE ADMIN
        Container::create([
            'nome' => 'admin',
            'url' => 'admin',
            'chave_api' => 'admin',
            'user_id' => 1,
        ]);

        //CREATE USER
        Container::create([
            'nome' => 'user',
            'url' => 'user',
            'chave_api' => 'user',
            'user_id' => 2,
        ]);

        //CREATE CORRETOR
        $corretor = User::create([
            'name' => 'Usuario 01',
            'email' => 'usuario01@martins.com',
            'password' => Hash::make('123123123'),
        ]);

        $corretor_role = Role::findByName('user');
        $corretor->assignRole($corretor_role);

        $admin->assignRole($admin_role);
        $user->assignRole($admin_role);
    }
}
