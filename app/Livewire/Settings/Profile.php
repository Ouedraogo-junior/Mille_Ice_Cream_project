<?php

namespace App\Livewire\Settings;

use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Illuminate\Validation\Rule;
use Livewire\Component;

class Profile extends Component
{
    public string $name = '';
    public ?string $email = '';
    public ?string $pseudo = '';

    public function mount(): void
    {
        $user = Auth::user();

        $this->name   = $user->name;
        $this->email  = $user->email;
        $this->pseudo = $user->pseudo;
    }

    public function updateProfileInformation(): void
    {
        $user = Auth::user();

        $this->validate([
            'name' => ['required', 'string', 'max:255'],

            'email' => [
                'required_without:pseudo',
                'nullable',
                'email',
                'max:255',
                Rule::unique(User::class)->ignore($user->id),
            ],

            'pseudo' => [
                'required_without:email',
                'nullable',
                'string',
                'max:30',
                'regex:/^[a-zA-Z0-9_]+$/',
                Rule::unique(User::class)->ignore($user->id),
            ],
        ], [
            'email.required_without'  => 'L’email est obligatoire si aucun pseudo n’est renseigné.',
            'pseudo.required_without' => 'Le pseudo est obligatoire si aucun email n’est renseigné.',
            'pseudo.regex'            => 'Le pseudo ne peut contenir que des lettres, chiffres et underscores.',
            'pseudo.unique'          => 'Ce pseudo est déjà utilisé par un autre compte.',
            'email.unique'            => 'Cet email est déjà utilisé par un autre compte.',
        ]);

        $user->fill([
            'name'   => $this->name,
            'email'  => $this->email ?: null,
            'pseudo' => $this->pseudo ?: null,
        ]);

        // Si l’email change → on désactive la vérification
        if ($user->isDirty('email')) {
            $user->email_verified_at = $user->email ? null : $user->email_verified_at;
        }

        $user->save();

        $this->dispatch('profile-updated', name: $user->name);
        $this->dispatch('toast', message: 'Profil mis à jour avec succès !', type: 'success');
    }

    public function resendVerificationNotification(): void
    {
        $user = Auth::user();

        if (!$user->email || $user->hasVerifiedEmail()) {
            $this->redirectIntended(default: route('dashboard', absolute: false));
            return;
        }

        $user->sendEmailVerificationNotification();
        Session::flash('status', 'verification-link-sent');
    }

    public function render()
    {
        if (auth()->user()->isAdmin())
            return view('livewire.settings.profile')
            ->layout('layouts.admin');
        else
             return view('livewire.settings.profile')
            ->layout('layouts.caissier');
    }
}