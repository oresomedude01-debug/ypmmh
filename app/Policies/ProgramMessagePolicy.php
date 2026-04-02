<?php

namespace App\Policies;

use App\Models\ProgramMessage;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class ProgramMessagePolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return false;
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, ProgramMessage $programMessage): bool
    {
        return false;
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return false;
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, ProgramMessage $programMessage): bool
    {
        return false;
    }

    public function delete(User $user, ProgramMessage $programMessage): bool
    {
        // Admin always can
        if ($user->hasRole('Admin'))
            return true;

        // Mentor of the program can
        if ($user->id === $programMessage->program->mentor_id)
            return true;

        // Owner can delete their own message IF they are not a Child and not suspended
        if ($user->id === $programMessage->user_id && !$user->hasRole('Child')) {
            $enrollment = \App\Models\Enrollment::where('program_id', $programMessage->program_id)
                ->where('user_id', $user->id)
                ->first();
            return $enrollment && $enrollment->chat_status === 'active';
        }

        return false;
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, ProgramMessage $programMessage): bool
    {
        return false;
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, ProgramMessage $programMessage): bool
    {
        return false;
    }
}
