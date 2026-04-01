<?php

declare(strict_types=1);

namespace App\Policies;

use App\Models\QuizResult;
use Illuminate\Auth\Access\HandlesAuthorization;
use Illuminate\Foundation\Auth\User as AuthUser;

class QuizResultPolicy
{
    use HandlesAuthorization;

    public function viewAny(AuthUser $authUser): bool
    {
        return $authUser->can('ViewAny:QuizResult');
    }

    public function view(AuthUser $authUser, QuizResult $quizResult): bool
    {
        return $authUser->can('View:QuizResult');
    }

    public function create(AuthUser $authUser): bool
    {
        return $authUser->can('Create:QuizResult');
    }

    public function update(AuthUser $authUser, QuizResult $quizResult): bool
    {
        return $authUser->can('Update:QuizResult');
    }

    public function delete(AuthUser $authUser, QuizResult $quizResult): bool
    {
        return $authUser->can('Delete:QuizResult');
    }

    public function deleteAny(AuthUser $authUser): bool
    {
        return $authUser->can('DeleteAny:QuizResult');
    }

    public function restore(AuthUser $authUser, QuizResult $quizResult): bool
    {
        return $authUser->can('Restore:QuizResult');
    }

    public function forceDelete(AuthUser $authUser, QuizResult $quizResult): bool
    {
        return $authUser->can('ForceDelete:QuizResult');
    }

    public function forceDeleteAny(AuthUser $authUser): bool
    {
        return $authUser->can('ForceDeleteAny:QuizResult');
    }

    public function restoreAny(AuthUser $authUser): bool
    {
        return $authUser->can('RestoreAny:QuizResult');
    }

    public function replicate(AuthUser $authUser, QuizResult $quizResult): bool
    {
        return $authUser->can('Replicate:QuizResult');
    }

    public function reorder(AuthUser $authUser): bool
    {
        return $authUser->can('Reorder:QuizResult');
    }
}
