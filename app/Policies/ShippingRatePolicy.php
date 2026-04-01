<?php

declare(strict_types=1);

namespace App\Policies;

use App\Models\ShippingRate;
use Illuminate\Auth\Access\HandlesAuthorization;
use Illuminate\Foundation\Auth\User as AuthUser;

class ShippingRatePolicy
{
    use HandlesAuthorization;

    public function viewAny(AuthUser $authUser): bool
    {
        return $authUser->can('ViewAny:ShippingRate');
    }

    public function view(AuthUser $authUser, ShippingRate $shippingRate): bool
    {
        return $authUser->can('View:ShippingRate');
    }

    public function create(AuthUser $authUser): bool
    {
        return $authUser->can('Create:ShippingRate');
    }

    public function update(AuthUser $authUser, ShippingRate $shippingRate): bool
    {
        return $authUser->can('Update:ShippingRate');
    }

    public function delete(AuthUser $authUser, ShippingRate $shippingRate): bool
    {
        return $authUser->can('Delete:ShippingRate');
    }

    public function deleteAny(AuthUser $authUser): bool
    {
        return $authUser->can('DeleteAny:ShippingRate');
    }

    public function restore(AuthUser $authUser, ShippingRate $shippingRate): bool
    {
        return $authUser->can('Restore:ShippingRate');
    }

    public function forceDelete(AuthUser $authUser, ShippingRate $shippingRate): bool
    {
        return $authUser->can('ForceDelete:ShippingRate');
    }

    public function forceDeleteAny(AuthUser $authUser): bool
    {
        return $authUser->can('ForceDeleteAny:ShippingRate');
    }

    public function restoreAny(AuthUser $authUser): bool
    {
        return $authUser->can('RestoreAny:ShippingRate');
    }

    public function replicate(AuthUser $authUser, ShippingRate $shippingRate): bool
    {
        return $authUser->can('Replicate:ShippingRate');
    }

    public function reorder(AuthUser $authUser): bool
    {
        return $authUser->can('Reorder:ShippingRate');
    }
}
