<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Models\Customer;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        // Admin
        User::create([
            'name' => 'Admin FunShirt',
            'email' => 'admin@funshirt.pt',
            'password' => Hash::make('123'),
            'user_type' => 'A',
            'gender' => 'M',
            'email_verified_at' => now(),
        ]);

        // Employee
        User::create([
            'name' => 'João Funcionário',
            'email' => 'func@funshirt.pt',
            'password' => Hash::make('123'),
            'user_type' => 'F',
            'gender' => 'M',
            'email_verified_at' => now(),
        ]);

        // Clients
        $clients = [
            ['name' => 'Maria Silva', 'email' => 'maria@example.pt', 'gender' => 'F', 'nif' => '123456789', 'address' => 'Rua das Flores 10, Lisboa'],
            ['name' => 'Pedro Costa', 'email' => 'pedro@example.pt', 'gender' => 'M', 'nif' => '987654321', 'address' => 'Av. da Liberdade 45, Porto'],
            ['name' => 'Ana Rodrigues', 'email' => 'ana@example.pt', 'gender' => 'F', 'nif' => '111222333', 'address' => 'Rua do Comércio 5, Coimbra'],
        ];

        foreach ($clients as $clientData) {
            $user = User::create([
                'name' => $clientData['name'],
                'email' => $clientData['email'],
                'password' => Hash::make('123'),
                'user_type' => 'C',
                'gender' => $clientData['gender'],
                'email_verified_at' => now(),
            ]);
            Customer::create([
                'id' => $user->id,
                'nif' => $clientData['nif'],
                'address' => $clientData['address'],
                'default_payment_type' => 'Visa',
                'default_payment_ref' => '4111111111111111',
            ]);
        }
    }
}
