<?php

namespace App\Actions\Fortify;

use Laravel\Fortify\Contracts\LoginResponse as LoginResponseContract;

class CustomLoginResponse implements LoginResponseContract
{
    public function toResponse($request)
    {
        $user = auth()->user();

        return $user->role === 'admin'
            ? redirect('/admin')
            : redirect('/caisse');
    }
}