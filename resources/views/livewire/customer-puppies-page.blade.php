<div>
    <h1 class="font-display text-2xl font-bold text-gray-900 mb-2">My puppies</h1>
    <p class="text-gray-600 mb-8">Save each pup’s details so we can tailor recommendations over time.</p>

    @if ($formMessage)
        <div class="mb-6 text-sm text-green-800 bg-green-50 border border-green-200 rounded-lg px-4 py-3">{{ $formMessage }}</div>
    @endif

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
        <section class="rounded-xl border border-gray-200 bg-white p-6">
            <h2 class="font-display text-lg font-bold text-gray-900 mb-4">{{ $editingId ? 'Edit puppy' : 'Add a puppy' }}</h2>
            <form wire:submit="savePuppy" class="space-y-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Name</label>
                    <input type="text" wire:model="name" class="w-full rounded-full border border-gray-300 px-3 py-2">
                    @error('name') <p class="text-sm text-red-600 mt-1">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Breed <span class="text-gray-400 font-normal">(optional)</span></label>
                    <input type="text" wire:model="breed" class="w-full rounded-full border border-gray-300 px-3 py-2" placeholder="e.g. Golden Retriever">
                    @error('breed') <p class="text-sm text-red-600 mt-1">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Birth date <span class="text-gray-400 font-normal">(optional)</span></label>
                    <input type="date" wire:model="birth_date" class="w-full rounded-full border border-gray-300 px-3 py-2">
                    @error('birth_date') <p class="text-sm text-red-600 mt-1">{{ $message }}</p> @enderror
                </div>
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Size</label>
                        <select wire:model="size_category" class="w-full rounded-full border border-gray-300 px-3 py-2">
                            <option value="">—</option>
                            @foreach (\App\Models\Puppy::sizeCategories() as $key => $label)
                                <option value="{{ $key }}">{{ $label }}</option>
                            @endforeach
                        </select>
                        @error('size_category') <p class="text-sm text-red-600 mt-1">{{ $message }}</p> @enderror
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Weight (kg) <span class="text-gray-400 font-normal">(optional)</span></label>
                        <input type="text" inputmode="decimal" wire:model="weight" class="w-full rounded-full border border-gray-300 px-3 py-2" placeholder="e.g. 4.5">
                        @error('weight') <p class="text-sm text-red-600 mt-1">{{ $message }}</p> @enderror
                    </div>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Health notes <span class="text-gray-400 font-normal">(optional)</span></label>
                    <textarea wire:model="health_notes" rows="3" class="w-full rounded-full border border-gray-300 px-3 py-2" placeholder="Allergies, vet notes — helps us suggest safer products."></textarea>
                    @error('health_notes') <p class="text-sm text-red-600 mt-1">{{ $message }}</p> @enderror
                </div>
                <div class="flex flex-wrap gap-2">
                    <button type="submit" wire:loading.attr="disabled" class="rounded-full bg-primary text-white font-semibold px-6 py-2.5 hover:bg-primary-dark disabled:opacity-60">
                        {{ $editingId ? 'Save changes' : 'Add puppy' }}
                    </button>
                    @if ($editingId)
                        <button type="button" wire:click="cancelForm" class="rounded-full border border-gray-300 font-semibold px-6 py-2.5 hover:bg-gray-50">Cancel</button>
                    @endif
                </div>
            </form>
        </section>

        <section class="rounded-xl border border-gray-200 bg-white p-6">
            <div class="flex items-center justify-between gap-4 mb-4">
                <h2 class="font-display text-lg font-bold text-gray-900">Your profiles</h2>
                <button type="button" wire:click="startAdd" class="text-sm font-semibold text-primary hover:underline">New puppy</button>
            </div>
            @if ($puppies->isEmpty())
                <p class="text-gray-600 text-sm">No puppies yet. Add one on the left.</p>
            @else
                <ul class="space-y-4">
                    @foreach ($puppies as $puppy)
                        <li class="border border-gray-100 rounded-lg p-4">
                            <div class="flex justify-between gap-3 items-start">
                                <div>
                                    <p class="font-semibold text-gray-900">{{ $puppy->name }}</p>
                                    <p class="text-sm text-gray-600 mt-1">
                                        @if ($puppy->breed) {{ $puppy->breed }} · @endif
                                        @if ($puppy->birth_date)
                                            ~{{ $puppy->birth_date->diffInWeeks(now()) }} wks old
                                        @else
                                            Age not set
                                        @endif
                                        @if ($puppy->size_category)
                                            · {{ \App\Models\Puppy::sizeCategories()[$puppy->size_category] ?? $puppy->size_category }}
                                        @endif
                                        @if ($puppy->weight)
                                            · {{ $puppy->weight }} kg
                                        @endif
                                    </p>
                                    @if ($puppy->health_notes)
                                        <p class="text-xs text-gray-500 mt-2 line-clamp-3">{{ $puppy->health_notes }}</p>
                                    @endif
                                </div>
                                <div class="flex flex-col gap-1 shrink-0">
                                    <button type="button" wire:click="startEdit({{ $puppy->id }})" class="text-sm font-semibold text-primary hover:underline">Edit</button>
                                    <button type="button" wire:click="deletePuppy({{ $puppy->id }})" wire:confirm="Remove this puppy profile?" class="text-sm font-semibold text-red-600 hover:underline">Remove</button>
                                </div>
                            </div>
                        </li>
                    @endforeach
                </ul>
            @endif
        </section>
    </div>
</div>
