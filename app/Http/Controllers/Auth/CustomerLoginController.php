<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class CustomerLoginController extends Controller
{
    public function sendOtp(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'email' => ['required', 'email'],
        ]);

        $email = mb_strtolower(trim($validated['email']));
        $user = $this->firstOrCreateCustomer($email);
        $user->sendOneTimePassword();

        return back()
            ->with('otp_sent', true)
            ->with('otp_email', $email)
            ->with('status', 'A sign-in code has been sent to your email.')
            ->withInput(['email' => $email]);
    }

    public function verifyOtp(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'email' => ['required', 'email'],
            'one_time_password' => ['required', 'string'],
        ]);

        $email = mb_strtolower(trim($validated['email']));
        $user = User::query()->where('email', $email)->first();

        if (! $user) {
            return back()->withErrors([
                'email' => __('Start by requesting a sign-in code.'),
            ])->withInput(['email' => $email]);
        }

        $result = $user->attemptLoginUsingOneTimePassword($validated['one_time_password']);

        if (! $result->isOk()) {
            return back()->withErrors([
                'one_time_password' => $result->validationMessage(),
            ])->with('otp_sent', true)
                ->with('otp_email', $email)
                ->withInput(['email' => $email]);
        }

        Auth::login($user, remember: true);
        $request->session()->regenerate();

        return redirect()->intended(route('customer.dashboard'));
    }

    protected function firstOrCreateCustomer(string $email): User
    {
        return User::query()->firstOrCreate(
            ['email' => $email],
            [
                'name' => Str::before($email, '@') ?: 'Customer',
                'password' => Hash::make(Str::random(40)),
            ]
        );
    }
}
