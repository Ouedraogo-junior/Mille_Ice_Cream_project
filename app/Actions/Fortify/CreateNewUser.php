<?php

namespace App\Actions\Fortify;

use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Laravel\Fortify\Contracts\CreatesNewUsers;
use Laravel\Fortify\Rules\Password;

class CreateNewUser implements CreatesNewUsers
{
    use \Laravel\Fortify\Rules\PasswordValidationRules;

    public function create(array $input): User
    {
        Validator::make($input, [
            'name' => ['required', 'string', 'max:255'],
            'email' => [
                'required',
                'string',
                'email',
                'max:255',
                'unique:users',
            ],
            'password' => $this->passwordRules(),
            // On accepte le rôle uniquement si envoyé par l'admin (voir plus bas)
            'role' => ['sometimes', 'in:admin,caissier'],
        ])->validate();

        return User::create([
            'name' => $input['name'],
            'email' => $input['email'],
            'password' => Hash::make($input['password']),
            // Si le rôle n'est pas fourni → caissier par défaut
            // Si fourni → on l'accepte (seulement l'admin pourra l'envoyer)
            'role' => $input['role'] ?? 'caissier',
        ]);
    }
}