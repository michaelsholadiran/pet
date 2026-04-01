<?php

namespace App\Livewire;

use App\Models\Puppy;
use Livewire\Attributes\Layout;
use Livewire\Component;

class CustomerPuppiesPage extends Component
{
    public ?int $editingId = null;

    public string $name = '';

    public string $breed = '';

    public ?string $birth_date = null;

    public string $weight = '';

    public string $size_category = '';

    public string $health_notes = '';

    public ?string $formMessage = null;

    #[Layout('layouts.dashboard')]
    public function mount(): void
    {
        view()->share('dashboardPageTitle', 'My puppies');
    }

    public function startAdd(): void
    {
        $this->resetForm();
        $this->editingId = null;
    }

    public function startEdit(int $id): void
    {
        $puppy = Puppy::query()->where('user_id', auth()->id())->findOrFail($id);
        $this->editingId = $puppy->id;
        $this->name = $puppy->name;
        $this->breed = (string) ($puppy->breed ?? '');
        $this->birth_date = $puppy->birth_date?->format('Y-m-d');
        $this->weight = $puppy->weight !== null ? (string) $puppy->weight : '';
        $this->size_category = (string) ($puppy->size_category ?? '');
        $this->health_notes = (string) ($puppy->health_notes ?? '');
        $this->formMessage = null;
    }

    public function cancelForm(): void
    {
        $this->resetForm();
    }

    public function savePuppy(): void
    {
        $rules = [
            'name' => ['required', 'string', 'max:255'],
            'breed' => ['nullable', 'string', 'max:255'],
            'birth_date' => ['nullable', 'date'],
            'weight' => ['nullable', 'numeric', 'min:0', 'max:999.99'],
            'size_category' => ['nullable', 'string', 'in:small,medium,large'],
            'health_notes' => ['nullable', 'string', 'max:5000'],
        ];

        $this->validate($rules);

        $data = [
            'name' => $this->name,
            'breed' => $this->breed !== '' ? $this->breed : null,
            'birth_date' => $this->birth_date ?: null,
            'weight' => $this->weight !== '' ? $this->weight : null,
            'size_category' => $this->size_category !== '' ? $this->size_category : null,
            'health_notes' => $this->health_notes !== '' ? $this->health_notes : null,
        ];

        if ($this->editingId) {
            $puppy = Puppy::query()->where('user_id', auth()->id())->findOrFail($this->editingId);
            $puppy->update($data);
            $this->formMessage = 'Puppy profile updated.';
        } else {
            Puppy::create(array_merge($data, ['user_id' => auth()->id()]));
            $this->formMessage = 'Puppy added.';
            $this->resetForm();
        }
    }

    public function deletePuppy(int $id): void
    {
        Puppy::query()->where('user_id', auth()->id())->whereKey($id)->delete();
        if ($this->editingId === $id) {
            $this->resetForm();
        }
        $this->formMessage = 'Removed from your profiles.';
    }

    private function resetForm(): void
    {
        $this->editingId = null;
        $this->name = '';
        $this->breed = '';
        $this->birth_date = null;
        $this->weight = '';
        $this->size_category = '';
        $this->health_notes = '';
        $this->resetValidation();
    }

    public function render()
    {
        $puppies = auth()->user()
            ->puppies()
            ->orderBy('name')
            ->get();

        return view('livewire.customer-puppies-page', [
            'puppies' => $puppies,
        ]);
    }
}
