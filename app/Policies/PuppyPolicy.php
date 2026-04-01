<?php

declare(strict_types=1);

namespace App\Policies;

use App\Models\Puppy;
use Illuminate\Auth\Access\HandlesAuthorization;
use Illuminate\Foundation\Auth\User as AuthUser;

class PuppyPolicy
{
    use HandlesAuthorization;

    public function viewAny(AuthUser $authUser): bool
    {
        return $authUser->can('ViewAny:Puppy');
    }

    public function view(AuthUser $authUser, Puppy $puppy): bool
    {
        return $authUser->can('View:Puppy');
    }

    public function create(AuthUser $authUser): bool
    {
        return $authUser->can('Create:Puppy');
    }

    public function update(AuthUser $authUser, Puppy $puppy): bool
    {
        return $authUser->can('Update:Puppy');
    }

    public function delete(AuthUser $authUser, Puppy $puppy): bool
    {
        return $authUser->can('Delete:Puppy');
    }

    public function deleteAny(AuthUser $authUser): bool
    {
        return $authUser->can('DeleteAny:Puppy');
    }

    public function restore(AuthUser $authUser, Puppy $puppy): bool
    {
        return $authUser->can('Restore:Puppy');
    }

    public function forceDelete(AuthUser $authUser, Puppy $puppy): bool
    {
        return $authUser->can('ForceDelete:Puppy');
    }

    public function forceDeleteAny(AuthUser $authUser): bool
    {
        return $authUser->can('ForceDeleteAny:Puppy');
    }

    public function restoreAny(AuthUser $authUser): bool
    {
        return $authUser->can('RestoreAny:Puppy');
    }

    public function replicate(AuthUser $authUser, Puppy $puppy): bool
    {
        return $authUser->can('Replicate:Puppy');
    }

    public function reorder(AuthUser $authUser): bool
    {
        return $authUser->can('Reorder:Puppy');
    }
}
