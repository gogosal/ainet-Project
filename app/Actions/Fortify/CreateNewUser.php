<?php

namespace App\Actions\Fortify;

use App\Models\Customer;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Laravel\Fortify\Contracts\CreatesNewUsers;

class CreateNewUser implements CreatesNewUsers
{
    public function create(array $input): User
    {
        Validator::make($input, [
            'name'     => ['required', 'string', 'max:255'],
            'email'    => ['required', 'string', 'email', 'max:255', Rule::unique('users')],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
            'gender'   => ['required', Rule::in(['M', 'F'])],
        ], [
            'name.required'     => 'O nome é obrigatório.',
            'email.required'    => 'O email é obrigatório.',
            'email.unique'      => 'Este email já está registado.',
            'password.required' => 'A password é obrigatória.',
            'password.min'      => 'A password deve ter pelo menos 8 caracteres.',
            'password.confirmed'=> 'As passwords não coincidem.',
            'gender.required'   => 'O género é obrigatório.',
        ])->validate();

        return DB::transaction(function () use ($input) {
            $user = User::create([
                'name'      => $input['name'],
                'email'     => $input['email'],
                'password'  => Hash::make($input['password']),
                'user_type' => 'C',
                'gender'    => $input['gender'],
            ]);

            Customer::create(['id' => $user->id]);

            return $user;
        });
    }
}
