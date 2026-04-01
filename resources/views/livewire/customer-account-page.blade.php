<div class="space-y-10">
    <section class="rounded-xl border border-gray-200 bg-white p-6">
        <h2 class="font-display text-lg font-bold text-gray-900 mb-1">Profile</h2>
        <p class="text-sm text-gray-600 mb-6">Update your name, email, and phone.</p>
        @if ($profileMessage)
            <div class="mb-4 text-sm text-green-700 bg-green-50 border border-green-200 rounded-lg px-3 py-2">{{ $profileMessage }}</div>
        @endif
        <form wire:submit="saveProfile" class="space-y-4 max-w-lg">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Name</label>
                <input type="text" wire:model="name" class="w-full rounded-full border border-gray-300 px-3 py-2">
                @error('name') <p class="text-sm text-red-600 mt-1">{{ $message }}</p> @enderror
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Email</label>
                <input type="email" wire:model="email" class="w-full rounded-full border border-gray-300 px-3 py-2">
                @error('email') <p class="text-sm text-red-600 mt-1">{{ $message }}</p> @enderror
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Phone <span class="text-gray-400 font-normal">(optional)</span></label>
                <input type="text" wire:model="phone" class="w-full rounded-full border border-gray-300 px-3 py-2">
                @error('phone') <p class="text-sm text-red-600 mt-1">{{ $message }}</p> @enderror
            </div>
            <button type="submit" wire:loading.attr="disabled" class="rounded-full bg-primary text-white font-semibold px-6 py-2.5 hover:bg-primary-dark disabled:opacity-60">Save profile</button>
        </form>
    </section>

    <section id="password" class="rounded-xl border border-gray-200 bg-white p-6 scroll-mt-24">
        <h2 class="font-display text-lg font-bold text-gray-900 mb-1">Change password</h2>
        <p class="text-sm text-gray-600 mb-6">Use a strong password you don’t use elsewhere.</p>
        @if ($passwordMessage)
            <div class="mb-4 text-sm text-green-700 bg-green-50 border border-green-200 rounded-lg px-3 py-2">{{ $passwordMessage }}</div>
        @endif
        <form wire:submit="savePassword" class="space-y-4 max-w-lg">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Current password</label>
                <input type="password" wire:model="current_password" autocomplete="current-password" class="w-full rounded-full border border-gray-300 px-3 py-2">
                @error('current_password') <p class="text-sm text-red-600 mt-1">{{ $message }}</p> @enderror
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">New password</label>
                <input type="password" wire:model="new_password" autocomplete="new-password" class="w-full rounded-full border border-gray-300 px-3 py-2">
                @error('new_password') <p class="text-sm text-red-600 mt-1">{{ $message }}</p> @enderror
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Confirm new password</label>
                <input type="password" wire:model="new_password_confirmation" autocomplete="new-password" class="w-full rounded-full border border-gray-300 px-3 py-2">
            </div>
            <button type="submit" wire:loading.attr="disabled" class="rounded-full bg-primary text-white font-semibold px-6 py-2.5 hover:bg-primary-dark disabled:opacity-60">Update password</button>
        </form>
    </section>

    <section class="rounded-xl border border-gray-200 bg-white p-6">
        <h2 class="font-display text-lg font-bold text-gray-900 mb-1">Notifications & address</h2>
        <p class="text-sm text-gray-600 mb-6">Default shipping details and email preferences.</p>
        @if ($settingsMessage)
            <div class="mb-4 text-sm text-green-700 bg-green-50 border border-green-200 rounded-lg px-3 py-2">{{ $settingsMessage }}</div>
        @endif
        <form wire:submit="saveSettings" class="space-y-6 max-w-xl">
            <div class="space-y-3">
                <label class="flex items-center gap-2 text-sm text-gray-800">
                    <input type="checkbox" wire:model="notify_order_updates" class="rounded border-gray-300 text-primary focus:ring-primary">
                    Order & shipping updates
                </label>
                <label class="flex items-center gap-2 text-sm text-gray-800">
                    <input type="checkbox" wire:model="notify_marketing" class="rounded border-gray-300 text-primary focus:ring-primary">
                    Tips, guides, and occasional offers
                </label>
            </div>
            <div class="border-t border-gray-100 pt-6 space-y-4">
                <h3 class="font-semibold text-gray-900">Default address</h3>
                <p class="text-xs text-gray-500">Used to pre-fill checkout when you’re logged in.</p>
                <input type="text" wire:model="ship_line1" placeholder="Address line 1" class="w-full rounded-full border border-gray-300 px-3 py-2">
                <input type="text" wire:model="ship_line2" placeholder="Address line 2 (optional)" class="w-full rounded-full border border-gray-300 px-3 py-2">
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                    <input type="text" wire:model="ship_city" placeholder="City" class="rounded-full border border-gray-300 px-3 py-2">
                    <input type="text" wire:model="ship_state" placeholder="State / region" class="rounded-full border border-gray-300 px-3 py-2">
                </div>
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                    <input type="text" wire:model="ship_postal" placeholder="Postal code" class="rounded-full border border-gray-300 px-3 py-2">
                    <input type="text" wire:model="ship_country" placeholder="Country" class="rounded-full border border-gray-300 px-3 py-2">
                </div>
            </div>
            <div class="rounded-lg bg-gray-50 border border-gray-100 p-4 text-sm text-gray-600">
                <strong class="text-gray-800">Payment methods</strong> — Cards and wallets are entered on our secure checkout. We never store your full card number.
            </div>
            <button type="submit" wire:loading.attr="disabled" class="rounded-full bg-primary text-white font-semibold px-6 py-2.5 hover:bg-primary-dark disabled:opacity-60">Save settings</button>
        </form>
    </section>
</div>
