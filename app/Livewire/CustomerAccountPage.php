<?php

namespace App\Livewire;

use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;
use Livewire\Attributes\Layout;
use Livewire\Component;

class CustomerAccountPage extends Component
{
    public string $name = '';

    public string $email = '';

    public string $phone = '';

    public string $current_password = '';

    public string $new_password = '';

    public string $new_password_confirmation = '';

    public string $ship_line1 = '';

    public string $ship_line2 = '';

    public string $ship_city = '';

    public string $ship_state = '';

    public string $ship_postal = '';

    public string $ship_country = '';

    public bool $notify_order_updates = true;

    public bool $notify_marketing = false;

    public ?string $profileMessage = null;

    public ?string $passwordMessage = null;

    public ?string $settingsMessage = null;

    #[Layout('layouts.dashboard')]
    public function mount(): void
    {
        $user = auth()->user();
        $this->name = $user->name;
        $this->email = $user->email;
        $this->phone = (string) ($user->phone ?? '');
        $this->notify_order_updates = (bool) $user->notify_order_updates;
        $this->notify_marketing = (bool) $user->notify_marketing;

        $addr = $user->shipping_address ?? [];
        $this->ship_line1 = (string) ($addr['line1'] ?? '');
        $this->ship_line2 = (string) ($addr['line2'] ?? '');
        $this->ship_city = (string) ($addr['city'] ?? '');
        $this->ship_state = (string) ($addr['state'] ?? '');
        $this->ship_postal = (string) ($addr['postal'] ?? '');
        $this->ship_country = (string) ($addr['country'] ?? '');
    }

    public function saveProfile(): void
    {
        $this->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', 'unique:users,email,'.auth()->id()],
            'phone' => ['nullable', 'string', 'max:50'],
        ]);

        auth()->user()->update([
            'name' => $this->name,
            'email' => $this->email,
            'phone' => $this->phone ?: null,
        ]);

        $this->profileMessage = 'Profile updated.';
    }

    public function savePassword(): void
    {
        $this->validate([
            'current_password' => ['required', 'current_password'],
            'new_password' => ['required', 'confirmed', Password::defaults()],
        ]);

        auth()->user()->update([
            'password' => Hash::make($this->new_password),
        ]);

        $this->reset('current_password', 'new_password', 'new_password_confirmation');
        $this->passwordMessage = 'Password changed.';
    }

    public function saveSettings(): void
    {
        $this->validate([
            'ship_line1' => ['nullable', 'string', 'max:255'],
            'ship_line2' => ['nullable', 'string', 'max:255'],
            'ship_city' => ['nullable', 'string', 'max:120'],
            'ship_state' => ['nullable', 'string', 'max:120'],
            'ship_postal' => ['nullable', 'string', 'max:32'],
            'ship_country' => ['nullable', 'string', 'max:120'],
        ]);

        auth()->user()->update([
            'shipping_address' => [
                'line1' => $this->ship_line1,
                'line2' => $this->ship_line2,
                'city' => $this->ship_city,
                'state' => $this->ship_state,
                'postal' => $this->ship_postal,
                'country' => $this->ship_country,
            ],
            'notify_order_updates' => $this->notify_order_updates,
            'notify_marketing' => $this->notify_marketing,
        ]);

        $this->settingsMessage = 'Settings saved.';
    }

    public function render()
    {
        view()->share('dashboardPageTitle', 'Profile & settings');

        return view('livewire.customer-account-page');
    }
}
