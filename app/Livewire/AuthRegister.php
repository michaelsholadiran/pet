<?php

namespace App\Livewire;

use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;
use Livewire\Attributes\Layout;
use Livewire\Component;

class AuthRegister extends Component
{
    public string $name = '';

    public string $email = '';

    public string $password = '';

    public string $password_confirmation = '';

    #[Layout('layouts.app')]
    public function mount(): void
    {
        view()->share([
            'page_title' => 'Create account - '.config('puppiary.name'),
            'page_canonical' => '/register',
        ]);
    }

    public function register(): void
    {
        $validated = $this->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:'.User::class],
            'password' => ['required', 'confirmed', Password::defaults()],
        ]);

        $user = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
        ]);

        event(new Registered($user));

        Auth::login($user);

        session()->regenerate();

        $this->redirect(route('customer.dashboard'), navigate: false);
    }

    public function render()
    {
        $bag = $this->getErrorBag();

        return view('livewire.auth-register', [
            'formErrorMessages' => $bag->all(),
            'formHasErrors' => $bag->isNotEmpty(),
        ]);
    }
}
