<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class AdminUserSeeder extends Seeder
{
    public function run()
    {
        // Criar usuário administrador
        User::create([
            'name' => 'Administrador',
            'email' => 'a@g',
            'password' => Hash::make('12345678'),
            'role' => 'admin',
            'categoria' => 'tecnico_administrativo',
            'ativo' => true,
           // 'email_verified_at' => now(),
        ]);

        // Criar usuário da comissão eleitoral
        User::create([
            'name' => 'Comissão Eleitoral',
            'email' => 'c@g',
            'password' => Hash::make('12345678'),
            'role' => 'comissao',
            'categoria' => 'docente',
            'ativo' => true,
           // 'email_verified_at' => now(),
        ]);

        // Criar usuário eleitor de exemplo
        User::create([
            'name' => 'Maria Eleitora',
            'email' => 'e@g',
            'password' => Hash::make('12345678'),
            'role' => 'eleitor',
            'categoria' => 'estudante',
            'ativo' => true,
           // 'email_verified_at' => now(),
        ]);

        User::create([
            'name' => 'Mayla Bobo',
            'email' => 'e1@g',
            'password' => Hash::make('12345678'),
            'role' => 'eleitor',
            'categoria' => 'estudante',
            'ativo' => true,
           // 'email_verified_at' => now(),
        ]);


        User::create([
            'name' => 'Stelio Bobo',
            'email' => 'e2@g',
            'password' => Hash::make('12345678'),
            'role' => 'eleitor',
            'categoria' => 'estudante',
            'ativo' => true,
           // 'email_verified_at' => now(),
        ]);


        User::create([
            'name' => 'Marcia Nhantumbo',
            'email' => 'e3@g',
            'password' => Hash::make('12345678'),
            'role' => 'eleitor',
            'categoria' => 'estudante',
            'ativo' => true,
           // 'email_verified_at' => now(),
        ]);
    }
}