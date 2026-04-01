<div class="max-w-md mx-auto px-6 py-16">
    <h1 class="font-display text-3xl font-bold text-gray-900 mb-2">Create your account</h1>
    <p class="text-gray-600 mb-8">Track orders, save puppy profiles, and leave reviews.</p>

    <form wire:submit.prevent="register" novalidate class="space-y-5">
        @if ($formHasErrors)
            <div class="rounded-lg border border-red-200 bg-red-50 px-4 py-3 text-sm text-red-800" role="alert" aria-live="polite">
                <p class="font-semibold text-red-900">Please check the form</p>
                <ul class="mt-2 list-disc list-inside space-y-1">
                    @foreach ($formErrorMessages as $message)
                        <li>{{ $message }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <div>
            <label for="reg-name" class="block text-sm font-medium text-gray-700 mb-1">Name</label>
            <input id="reg-name" type="text" wire:model="name" autocomplete="name"
                aria-invalid="{{ $errors->has('name') ? 'true' : 'false' }}"
                class="w-full rounded-full border px-3 py-2.5 focus:ring-1 focus:ring-primary {{ $errors->has('name') ? 'border-red-500 focus:border-red-500' : 'border-gray-300 focus:border-primary' }}">
            @error('name')
                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
            @enderror
        </div>
        <div>
            <label for="reg-email" class="block text-sm font-medium text-gray-700 mb-1">Email</label>
            <input id="reg-email" type="email" wire:model="email" autocomplete="email"
                aria-invalid="{{ $errors->has('email') ? 'true' : 'false' }}"
                class="w-full rounded-full border px-3 py-2.5 focus:ring-1 focus:ring-primary {{ $errors->has('email') ? 'border-red-500 focus:border-red-500' : 'border-gray-300 focus:border-primary' }}">
            @error('email')
                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
            @enderror
        </div>
        <div>
            <label for="reg-password" class="block text-sm font-medium text-gray-700 mb-1">Password</label>
            <input id="reg-password" type="password" wire:model="password" autocomplete="new-password"
                aria-invalid="{{ $errors->has('password') ? 'true' : 'false' }}"
                class="w-full rounded-full border px-3 py-2.5 focus:ring-1 focus:ring-primary {{ $errors->has('password') ? 'border-red-500 focus:border-red-500' : 'border-gray-300 focus:border-primary' }}">
            @error('password')
                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
            @enderror
        </div>
        <div>
            <label for="reg-password-confirm" class="block text-sm font-medium text-gray-700 mb-1">Confirm password</label>
            <input id="reg-password-confirm" type="password" wire:model="password_confirmation" autocomplete="new-password"
                aria-invalid="{{ $errors->has('password_confirmation') ? 'true' : 'false' }}"
                class="w-full rounded-full border px-3 py-2.5 focus:ring-1 focus:ring-primary {{ $errors->has('password_confirmation') ? 'border-red-500 focus:border-red-500' : 'border-gray-300 focus:border-primary' }}">
            @error('password_confirmation')
                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
            @enderror
        </div>
        <button type="submit" wire:loading.attr="disabled"
            class="w-full rounded-full bg-primary text-white font-semibold py-3 hover:bg-primary-dark transition disabled:opacity-60">
            <span wire:loading.remove wire:target="register">Create account</span>
            <span wire:loading wire:target="register">Creating…</span>
        </button>
    </form>

    <p class="mt-8 text-center text-sm text-gray-600">
        Already have an account?
        <a href="{{ route('sign-in') }}" class="font-semibold text-primary hover:underline">Sign in</a>
    </p>
</div>
