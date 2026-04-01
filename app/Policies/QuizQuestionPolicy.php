<?php

declare(strict_types=1);

namespace App\Policies;

use App\Models\QuizQuestion;
use Illuminate\Auth\Access\HandlesAuthorization;
use Illuminate\Foundation\Auth\User as AuthUser;

class QuizQuestionPolicy
{
    use HandlesAuthorization;

    public function viewAny(AuthUser $authUser): bool
    {
        return $authUser->can('ViewAny:QuizQuestion');
    }

    public function view(AuthUser $authUser, QuizQuestion $quizQuestion): bool
    {
        return $authUser->can('View:QuizQuestion');
    }

    public function create(AuthUser $authUser): bool
    {
        return $authUser->can('Create:QuizQuestion');
    }

    public function update(AuthUser $authUser, QuizQuestion $quizQuestion): bool
    {
        return $authUser->can('Update:QuizQuestion');
    }

    public function delete(AuthUser $authUser, QuizQuestion $quizQuestion): bool
    {
        return $authUser->can('Delete:QuizQuestion');
    }

    public function deleteAny(AuthUser $authUser): bool
    {
        return $authUser->can('DeleteAny:QuizQuestion');
    }

    public function restore(AuthUser $authUser, QuizQuestion $quizQuestion): bool
    {
        return $authUser->can('Restore:QuizQuestion');
    }

    public function forceDelete(AuthUser $authUser, QuizQuestion $quizQuestion): bool
    {
        return $authUser->can('ForceDelete:QuizQuestion');
    }

    public function forceDeleteAny(AuthUser $authUser): bool
    {
        return $authUser->can('ForceDeleteAny:QuizQuestion');
    }

    public function restoreAny(AuthUser $authUser): bool
    {
        return $authUser->can('RestoreAny:QuizQuestion');
    }

    public function replicate(AuthUser $authUser, QuizQuestion $quizQuestion): bool
    {
        return $authUser->can('Replicate:QuizQuestion');
    }

    public function reorder(AuthUser $authUser): bool
    {
        return $authUser->can('Reorder:QuizQuestion');
    }
}
