<?php

namespace App\Actions\Fortify;

use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Laravel\Fortify\Contracts\CreatesNewUsers;

class CreateNewUser implements CreatesNewUsers
{
    use PasswordValidationRules;

    public function create(array $input): User
    {
        Validator::make($input, [
            'name' => ['required', 'string', 'max:255'],

            // Au moins un des deux doit être présent
            'email' => [
                'required_without:pseudo',
                'nullable',
                'email',
                'max:255',
                'unique:users,email',
            ],
            'pseudo' => [
                'required_without:email',
                'nullable',
                'string',
                'max:30',
                'regex:/^[a-zA-Z0-9_]+$/',
                'unique:users,pseudo',
            ],

            'password' => $this->passwordRules(),

            // Rôle autorisé seulement pour l'admin (via formulaire caché ou API)
            'role' => ['sometimes', 'in:admin,caissier'],
        ], [
            'email.required_without' => 'L’email est obligatoire si aucun pseudo n’est renseigné.',
            'pseudo.required_without' => 'Le pseudo est obligatoire si aucun email n’est renseigné.',
            'pseudo.regex' => 'Le pseudo ne peut contenir que des lettres, chiffres et underscores.',
            'pseudo.unique' => 'Ce pseudo est déjà utilisé.',
            'email.unique' => 'Cet email est déjà utilisé.',
        ])->validate();

        return User::create([
            'name'     => $input['name'],
            'email'    => $input['email'] ?? null,
            'pseudo'   => $input['pseudo'] ?? null,
            'password' => Hash::make($input['password']),
            'role'     => $input['role'] ?? 'caissier',
        ]);
    }
}