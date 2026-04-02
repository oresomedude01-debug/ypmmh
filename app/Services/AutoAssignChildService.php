<?php

namespace App\Services;

use App\Models\User;
use App\Models\Program;
use App\Models\Enrollment;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;

class AutoAssignChildService
{
    /**
     * Synchronize a child's rolling program enrollments based on their current age.
     * It will:
     * 1. Remove rolling programs where the child no longer matches the age_target.
     * 2. Add rolling programs where the child now matches the age_target.
     *
     * @param User $child
     * @return void
     */
    public function syncRollingPrograms(User $child): void
    {
        if (!$child->hasRole('Child') || !$child->date_of_birth) {
            return;
        }

        $age = Carbon::parse($child->date_of_birth)->age;

        // 1. Handle Unassignment: Remove rolling programs where age != age_target
        $currentRollingEnrollments = Enrollment::where('user_id', $child->id)
            ->whereHas('program', function ($query) {
                $query->where('type', 'rolling');
            })
            ->with('program')
            ->get();

        foreach ($currentRollingEnrollments as $enrollment) {
            // If program is missing or age doesn't match, unassign
            if (!$enrollment->program || $enrollment->program->age_target != $age) {
                $reason = !$enrollment->program ? "Missing Program" : "Age Mismatch (Target: {$enrollment->program->age_target})";
                Log::info("Auto-unassigning Child ID: {$child->id} from Enrollment ID: {$enrollment->id} Reason: {$reason}");
                $enrollment->delete();
            }
        }

        // 2. Handle Assignment: Add active rolling programs where age == age_target
        $matchingPrograms = Program::where('type', 'rolling')
            ->where('age_target', $age)
            ->where('status', 'active')
            ->get();

        foreach ($matchingPrograms as $program) {
            $isEnrolled = Enrollment::where('user_id', $child->id)
                ->where('program_id', $program->id)
                ->exists();

            if (!$isEnrolled) {
                Log::info("Auto-assigning Child ID: {$child->id} to Rolling Program: '{$program->name}' (Age: {$age})");
                Enrollment::create([
                    'user_id' => $child->id,
                    'program_id' => $program->id,
                    'status' => 'active',
                ]);
            }
        }
    }

    /**
     * Run synchronization for all children in the system.
     * This can be called from a scheduled task.
     */
    public function syncAllChildren(): void
    {
        User::role('Child')->whereNotNull('date_of_birth')->chunk(100, function ($children) {
            foreach ($children as $child) {
                $this->syncRollingPrograms($child);
            }
        });
    }

    /**
     * Legacy assign method (kept for compatibility or one-off registrations)
     * Refactored to use the new sync logic.
     */
    public function assign(User $child): ?Program
    {
        $this->syncRollingPrograms($child);
        return null; // Return value changed in meaning, but kept signature
    }
}
