<div class="max-w-md mx-auto px-6 py-16">
    @php
        $email = old('email', session('otp_email'));
        $otpSent = session('otp_sent') || $errors->has('one_time_password');
    @endphp

    <h1 class="font-display text-3xl font-bold text-gray-900 mb-2">Sign in</h1>
    <p class="text-gray-600 mb-8">Enter your email and we will send an OTP.</p>

    @if (session('status'))
        <div class="mb-5 rounded-lg border border-green-200 bg-green-50 px-4 py-3 text-sm text-green-800" role="status">
            {{ session('status') }}
        </div>
    @endif

    @if (! $otpSent)
        <form method="post" action="{{ route('sign-in.otp.send') }}" novalidate class="space-y-5">
            @csrf

            <div>
                <label for="login-email" class="block text-sm font-medium text-gray-700 mb-1">Email</label>
                <input id="login-email" name="email" type="email" value="{{ $email }}" autocomplete="email" placeholder="Enter your email" required
                    aria-invalid="{{ $errors->has('email') ? 'true' : 'false' }}"
                    class="w-full rounded-full border px-3 py-2.5 focus:ring-1 focus:ring-primary {{ $errors->has('email') ? 'border-red-500 focus:border-red-500' : 'border-gray-300 focus:border-primary' }}">
                @error('email')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <button type="submit"
                class="w-full rounded-full bg-primary text-white font-semibold py-3 hover:bg-primary-dark transition disabled:opacity-60">
                Sign in
            </button>
        </form>
    @else
        <form method="post" action="{{ route('sign-in.otp.verify') }}" novalidate class="space-y-5">
            @csrf

            <div>
                <label for="verify-email" class="block text-sm font-medium text-gray-700 mb-1">Email</label>
                <input id="verify-email" name="email" type="email" value="{{ $email }}" autocomplete="email" placeholder="Enter your email" required
                    aria-invalid="{{ $errors->has('email') ? 'true' : 'false' }}"
                    class="w-full rounded-full border px-3 py-2.5 focus:ring-1 focus:ring-primary {{ $errors->has('email') ? 'border-red-500 focus:border-red-500' : 'border-gray-300 focus:border-primary' }}">
                @error('email')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label for="one-time-password" class="block text-sm font-medium text-gray-700 mb-1">OTP</label>
                <input id="one-time-password" name="one_time_password" type="text"
                    inputmode="numeric" autocomplete="one-time-code" placeholder="Enter 4 digit code" maxlength="4" pattern="[0-9]{4}" required
                    aria-invalid="{{ $errors->has('one_time_password') ? 'true' : 'false' }}"
                    class="w-full rounded-full border px-3 py-2.5 tracking-[0.3em] text-center focus:ring-1 focus:ring-primary {{ $errors->has('one_time_password') ? 'border-red-500 focus:border-red-500' : 'border-gray-300 focus:border-primary' }}">
                @error('one_time_password')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <button type="submit"
                class="w-full rounded-full bg-primary text-white font-semibold py-3 hover:bg-primary-dark transition disabled:opacity-60">
                Verify
            </button>
        </form>

        <form method="post" action="{{ route('sign-in.otp.send') }}" class="mt-4">
            @csrf
            <input type="hidden" name="email" value="{{ $email }}">
            <button type="submit" class="text-sm font-semibold text-primary hover:underline">
                Resend code
            </button>
        </form>
    @endif
</div>
