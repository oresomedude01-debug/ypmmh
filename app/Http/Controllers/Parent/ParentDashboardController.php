<?php

namespace App\Http\Controllers\Parent;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Models\Observation;
use App\Models\Report;
use App\Models\Enrollment;
use App\Models\Event;
use App\Models\Program;
use App\Services\ProgramRecommendationService;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class ParentDashboardController extends Controller
{
    public function toggleEnrollment(Request $request, Enrollment $enrollment)
    {
        $child = $enrollment->user;
        $this->authorizeAccess($child);

        $enrollment->update([
            'is_active' => !$enrollment->is_active
        ]);

        $statusLabel = $enrollment->is_active ? 'activated' : 'deactivated';
        return back()->with('success', 'Program ' . $statusLabel . ' for ' . $child->first_name . '.');
    }

    public function createChild()
    {
        return view('parent.children.create');
    }

    public function storeChild(Request $request)
    {
        $request->validate([
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'date_of_birth' => 'required|date|before:today',
            'gender' => 'required|in:male,female',
            'email' => 'required|email|unique:users,email',
            'relationship' => 'nullable|string|max:100',
            'profile_picture' => 'nullable|image|max:2048',
        ]);

        $childData = [
            'first_name' => $request->first_name,
            'last_name' => $request->last_name,
            'date_of_birth' => $request->date_of_birth,
            'gender' => $request->gender,
            'email' => $request->email,
            'password' => Hash::make(Str::random(32)), // Random unset password
            'parent_id' => Auth::id(),
            'relationship' => $request->relationship,
            'must_change_password' => true,
            'email_verified_at' => null, // Not yet verified
        ];

        if ($request->hasFile('profile_picture')) {
            $childData['profile_picture'] = $request->file('profile_picture')->store('profile-photos', 'public');
        }

        $child = User::create($childData);

        $child->assignRole('Child');

        // Unique ID
        $child->unique_number = 'CH' . date('Y') . str_pad($child->id, 4, '0', STR_PAD_LEFT);
        $child->save();

        // Auto-start premium trial if enabled in admin settings
        $child->startPremiumTrial();

        // Send Email Verification Notification
        $child->sendEmailVerificationNotification();

        return redirect()->route('parent.dashboard')->with('success', $child->first_name . ' has been added to your family. A verification email has been sent to ' . $child->email . '.');
    }

    public function reportProfileIssue(Request $request, User $child)
    {
        $this->authorizeAccess($child);

        $request->validate(['issue' => 'required|string|max:1000']);

        // In a real app, this would send an email or notification to admins
        // For now, we'll simulate a successful request

        // Notify Admins (Placeholder)
        // Notification::send(User::role('Admin')->get(), new ProfileIssueReported($child, $request->issue));

        return back()->with('success', 'Your report has been submitted. An administrator will review your request.');
    }

    public function index()
    {
        $parent = Auth::user();
        $children = $parent->children()->with(['lessonProgress', 'achievements', 'enrollments.program'])->get();

        // Get recent observations for all children
        $recentObservations = Observation::whereIn('child_id', $children->pluck('id'))
            ->with(['mentor', 'child'])
            ->orderBy('created_at', 'desc')
            ->take(5)
            ->get();

        return view('parent.dashboard', compact('parent', 'children', 'recentObservations'));
    }

    public function showChild(User $child)
    {
        $this->authorizeAccess($child);

        $child->load(['lessonProgress', 'achievements', 'enrollments.program', 'observations.mentor']);

        // Reports - Placeholder
        $reports = [];

        return view('parent.children.show', compact('child', 'reports'));
    }

    public function editChild(User $child)
    {
        $this->authorizeAccess($child);
        $child->load(['enrollments', 'achievements']);
        return view('parent.children.edit', compact('child'));
    }

    public function updateChild(Request $request, User $child)
    {
        $this->authorizeAccess($child);

        $validated = $request->validate([
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'date_of_birth' => 'required|date|before:today',
            'gender' => 'required|in:male,female',
            'phone' => 'nullable|string|max:20',
            'relationship' => 'nullable|string|max:100',
        ]);

        $child->update($validated);

        return redirect()->route('parent.children.show', $child)
            ->with('success', $child->first_name . "'s profile has been updated successfully.");
    }

    public function observations()
    {
        $parent = Auth::user();
        $children = $parent->children;
        $observations = Observation::whereIn('child_id', $children->pluck('id'))
            ->with(['mentor', 'child'])
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return view('parent.observations.index', compact('observations'));
    }

    public function notifications()
    {
        $notifications = Auth::user()->notifications()->paginate(15);
        return view('parent.notifications.index', compact('notifications'));
    }

    public function events()
    {
        $events = Event::where('start_time', '>=', now()->startOfDay())
            ->orderBy('start_time', 'asc')
            ->get();

        return view('parent.events.index', compact('events'));
    }

    public function downloadReport(User $child)
    {
        $this->authorizeAccess($child);
        return back()->with('info', 'Monthly reports are being prepared. You will be notified when the next review is available.');
    }

    public function programs()
    {
        $user = Auth::user();
        $isParent = $user->hasRole('Parent');
        $children = $isParent
            ? $user->children()->with('enrollments')->get()
            : collect([$user->load('enrollments')]);

        // Define eligibility criteria:
        // A program is considered suitable if at least one child:
        // 1. Matches the age requirements (range for scheduled/journey, specific target for rolling)
        // 2. Is NOT already enrolled in that program

        $eligibleProgramIds = \App\Models\Program::where('status', 'active')
            ->where(function ($query) use ($children) {
                if ($children->isEmpty()) {
                    $query->whereRaw('1 = 0'); // No children, no programs
                    return;
                }

                foreach ($children as $child) {
                    $age = $child->age;
                    if ($age === null)
                        continue;

                    $query->orWhere(function ($q) use ($child, $age) {
                        // Age Match Check
                        $q->where(function ($ageQ) use ($age) {
                            // Scheduled, Journey & Offline programs: age must be within range
                            $ageQ->where(function ($sq) use ($age) {
                                $sq->whereIn('type', ['scheduled', 'journey', 'offline'])
                                    ->where(function ($rangeQ) use ($age) {
                                        $rangeQ->where(function ($minQ) use ($age) {
                                            $minQ->whereNull('cohort_age_min')->orWhere('cohort_age_min', '<=', $age);
                                        })->where(function ($maxQ) use ($age) {
                                            $maxQ->whereNull('cohort_age_max')->orWhere('cohort_age_max', '>=', $age);
                                        });
                                    });
                            })
                                // Rolling programs: age must match target exactly
                                ->orWhere(function ($rq) use ($age) {
                                $rq->where('type', 'rolling')
                                    ->where(function ($rtQ) use ($age) {
                                        $rtQ->whereNull('age_target')->orWhere('age_target', $age);
                                    });
                            });
                        });

                        // Enrollment Check: This specific child must not be enrolled
                        $q->whereDoesntHave('enrollments', function ($eq) use ($child) {
                            $eq->where('user_id', $child->id);
                        });
                    });
                }
            })
            ->pluck('id');

        // Fetch featured programs for the carousel (Limit 3) - MUST be eligible
        $featuredPrograms = \App\Models\Program::whereIn('id', $eligibleProgramIds)
            ->where('is_featured', true)
            ->where('status', 'active')
            ->take(3)
            ->get();

        // Fetch all active programs for the catalog - MUST be eligible
        $availablePrograms = \App\Models\Program::whereIn('id', $eligibleProgramIds)
            ->whereIn('type', ['scheduled', 'journey', 'offline'])
            ->where('status', 'active')
            ->where(function ($query) {
                $query->whereNull('end_date')
                    ->orWhere('end_date', '>', now());
            })
            ->orderBy('created_at', 'desc')
            ->get();

        // Prepare eligibility mapping for the view (Helper function logic)
        $calculateEligibility = function ($program) use ($children) {
            return $children->filter(function ($child) use ($program) {
                $age = $child->age;
                if ($age === null)
                    return false;

                $isAgeSuitable = false;
                if (in_array($program->type, ['scheduled', 'journey', 'offline'])) {
                    $isAgeSuitable = (
                        ($program->cohort_age_min === null || $program->cohort_age_min <= $age) &&
                        ($program->cohort_age_max === null || $program->cohort_age_max >= $age)
                    );
                } elseif ($program->type === 'rolling') {
                    $isAgeSuitable = ($program->age_target === null || $program->age_target == $age);
                }

                if (!$isAgeSuitable)
                    return false;

                $isEnrolled = $child->enrollments->where('program_id', $program->id)->isNotEmpty();
                return !$isEnrolled;
            })->pluck('id')->values()->toArray();
        };

        $availablePrograms->each(function ($p) use ($calculateEligibility) {
            $p->eligible_child_ids = $calculateEligibility($p);
        });

        $featuredPrograms->each(function ($p) use ($calculateEligibility) {
            $p->eligible_child_ids = $calculateEligibility($p);
        });

        // Fire a throttled spotlight notification (once per 7 days per program)
        // and pass the best recommendation to the view for the in-app card
        $recommender = app(ProgramRecommendationService::class);
        $spotlight   = $recommender->getSpotlightForParent($user);

        // Dispatch notification asynchronously so it doesn't slow page load
        dispatch(function () use ($user, $recommender) {
            $recommender->maybeSendSpotlightNotification($user);
        })->afterResponse();

        return view('parent.programs.index', compact('availablePrograms', 'children', 'featuredPrograms', 'spotlight'));
    }

    public function subscribe(Request $request)
    {
        $user = Auth::user();

        $validated = $request->validate([
            'program_id' => 'required|exists:programs,id',
            'child_id' => 'required_if:user_role,Parent|exists:users,id',
        ]);

        $program = \App\Models\Program::findOrFail($validated['program_id']);

        // If parent, use child_id from request. If child, use current user.
        $targetUser = $user->hasRole('Parent') ? User::findOrFail($validated['child_id']) : $user;

        if ($user->hasRole('Parent')) {
            $this->authorizeAccess($targetUser);
        } else {
            // If child, ensure they are the target
            if ($targetUser->id !== $user->id) {
                abort(403);
            }
        }

        // Check if already enrolled
        $existingEnrollment = \App\Models\Enrollment::where('program_id', $program->id)
            ->where('user_id', $targetUser->id)
            ->first();

        if ($existingEnrollment) {
            return back()->with('error', $targetUser->first_name . ' is already enrolled in this program.');
        }

        // Enrollment logic
        \App\Models\Enrollment::create([
            'program_id' => $program->id,
            'user_id' => $targetUser->id,
            'status' => 'active',
            'is_active' => true,
        ]);

        $successMsg = ($user->id === $targetUser->id)
            ? 'You have successfully enrolled in ' . $program->name . '!'
            : $targetUser->first_name . ' has been enrolled in ' . $program->name . ' successfully!';

        return redirect()->route($user->hasRole('Parent') ? 'parent.dashboard' : 'child.dashboard')->with('success', $successMsg);
    }

    public function printPass(\App\Models\Program $program, User $child)
    {
        $this->authorizeAccess($child);

        // Ensure the child is enrolled in this program
        $enrollment = \App\Models\Enrollment::where('program_id', $program->id)
            ->where('user_id', $child->id)
            ->firstOrFail();

        return view('parent.programs.pass', compact('program', 'child', 'enrollment'));
    }

    public function requestEnrollment(Request $request)
    {
        $child = Auth::user();
        if (!$child->hasRole('Child')) {
            return back()->with('error', 'Only children can request enrollment.');
        }

        $validated = $request->validate([
            'program_id' => 'required|exists:programs,id',
        ]);

        $program = \App\Models\Program::findOrFail($validated['program_id']);
        $parent = $child->parent;

        if ($parent) {
            $parent->notify(new \App\Notifications\ProgramUpdateNotification([
                'type' => 'enrollment_request',
                'message' => "{$child->first_name} is interested in joining the '{$program->name}' program. You can enroll them from the Program Catalog.",
                'program_id' => $program->id,
                'child_id' => $child->id,
            ]));

            return back()->with('success', 'A request has been sent to your parent for ' . $program->name . '.');
        }

        return back()->with('error', 'No parent found to receive the request.');
    }

    private function authorizeAccess(User $child)
    {
        if ($child->parent_id !== Auth::id()) {
            abort(403, 'Unauthorized access to this child data.');
        }
    }
}
