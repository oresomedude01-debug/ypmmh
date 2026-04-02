<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Program;
use App\Models\User;
use Illuminate\Http\Request;
use App\Services\AutoAssignChildService;


class ProgramController extends Controller
{
    public function index(Request $request)
    {
        $type = $request->get('type');
        $status = $request->get('status');
        $hasMentor = $request->get('hasMentor');

        $query = Program::with('mentor')->withCount('children as enrollments_count', 'contents')->orderBy('created_at', 'desc');

        if ($type) {
            $query->where('type', $type);
        }

        if ($status) {
            $query->where('status', $status);
        }

        if ($hasMentor === 'yes') {
            $query->whereNotNull('mentor_id');
        } elseif ($hasMentor === 'no') {
            $query->whereNull('mentor_id');
        }

        // Calculate global stats before paginating
        $totalPrograms = Program::count();
        $activePrograms = Program::where('status', 'active')->count();
        $totalStudents = Program::withCount('children')->get()->sum('children_count');
        $featuredPrograms = Program::where('is_featured', true)->count();

        return view('Admin/Programs/index', [
            'programs' => $query->paginate(15),
            'stats' => [
                'totalPrograms' => $totalPrograms,
                'activePrograms' => $activePrograms,
                'totalStudents' => $totalStudents,
                'featuredPrograms' => $featuredPrograms,
            ],
            'filterType' => $type,
            'filterStatus' => $status,
            'filterHasMentor' => $hasMentor,
        ]);
    }

    public function create()
    {
        $mentors = User::role('Mentor')
            ->select('id', 'first_name', 'last_name', 'email')
            ->get()
            ->map(fn($u) => [
                'id' => $u->id,
                'first_name' => $u->first_name,
                'last_name' => $u->last_name,
                'name' => trim($u->first_name . ' ' . $u->last_name),
                'email' => $u->email,
            ]);

        return view('Admin/Programs/create', [
            'mentors' => $mentors,
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'type' => 'required|in:rolling,scheduled,journey,offline',
            'description' => 'nullable|string',
            'mentor_id' => 'nullable|exists:users,id',
            'status' => 'required|in:draft,active,archived',
            // Rolling program: single year age target
            'age_target' => 'nullable|integer|min:0|max:120',
            // Cohort program: age range
            'cohort_age_min' => 'nullable|integer|min:0',
            'cohort_age_max' => 'nullable|integer|gte:cohort_age_min',
            // Cohort dates
            'start_date' => 'nullable|date',
            'end_date' => 'nullable|date|after_or_equal:start_date',
            // Pricing & Media
            'price' => 'nullable|numeric|min:0',
            'is_free' => 'boolean',
            'youtube_url' => 'nullable|url',
            'thumbnail' => 'nullable|image|max:2048',
            'is_featured' => 'boolean',
        ]);

        if ($request->hasFile('thumbnail')) {
            $validated['thumbnail_path'] = $request->file('thumbnail')->store('programs', 'public');
        }

        // Clear fields based on program type
        if ($validated['type'] === 'rolling') {
            // Rolling programs use age_target only
            $validated['cohort_age_min'] = null;
            $validated['cohort_age_max'] = null;
            $validated['start_date'] = null;
            $validated['end_date'] = null;
            $validated['price'] = null;
            $validated['is_free'] = false;
            $validated['youtube_url'] = null;
        } elseif ($validated['type'] === 'journey') {
            // Journey programs use age range but no fixed dates
            $validated['age_target'] = null;
            $validated['start_date'] = null;
            $validated['end_date'] = null;
            if (isset($validated['is_free']) && $validated['is_free']) {
                $validated['price'] = 0;
            }
        } elseif ($validated['type'] === 'offline') {
            // Offline programs use age range and CAN have dates (handled like scheduled)
            $validated['age_target'] = null;
            if (isset($validated['is_free']) && $validated['is_free']) {
                $validated['price'] = 0;
            }
        } else {
            // Scheduled (cohort) programs use cohort ages and dates
            $validated['age_target'] = null;
            if (isset($validated['is_free']) && $validated['is_free']) {
                $validated['price'] = 0;
            }
        }

        $validated['is_featured'] = $request->has('is_featured');

        $program = Program::create($validated);

        // If a rolling program is created as active, sync matching children immediately
        if ($program->type === 'rolling' && $program->status === 'active') {
            app(AutoAssignChildService::class)->syncAllChildren();
        }

        return redirect()
            ->route('admin.programs.edit', $program)
            ->with('success', 'Program created successfully.');
    }

    public function edit(Program $program)
    {
        $mentors = User::role('Mentor')
            ->select('id', 'first_name', 'last_name', 'email')
            ->get()
            ->map(fn($u) => [
                'id' => $u->id,
                'first_name' => $u->first_name,
                'last_name' => $u->last_name,
                'name' => trim($u->first_name . ' ' . $u->last_name),
                'email' => $u->email,
            ]);

        return view('Admin/Programs/edit', [
            'program' => $program->load('mentor'),
            'mentors' => $mentors,
        ]);
    }

    public function show(Program $program)
    {
        $program->load([
            'mentor',
            'children.observations.mentor',
            'contents' => function ($query) use ($program) {
                if ($program->type === 'rolling') {
                    $query->orderBy('week_number')
                        ->orderBy('day_number')
                        ->orderBy('time_of_day');
                } elseif ($program->type === 'journey') {
                    $query->orderBy('week_offset')
                        ->orderBy('day_offset')
                        ->orderBy('time_of_day');
                } else {
                    $query->orderBy('publish_at');
                }
            }
        ]);

        return view('Admin/Programs/show', [
            'program' => $program,
        ]);
    }

    public function update(Request $request, Program $program)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'type' => 'required|in:rolling,scheduled,journey,offline',
            'description' => 'nullable|string',
            'mentor_id' => 'nullable|exists:users,id',
            'status' => 'required|in:draft,active,archived',
            // Rolling program: single year age target
            'age_target' => 'nullable|integer|min:0|max:120',
            // Cohort program: age range
            'cohort_age_min' => 'nullable|integer|min:0',
            'cohort_age_max' => 'nullable|integer|gte:cohort_age_min',
            // Cohort dates
            'start_date' => 'nullable|date',
            'end_date' => 'nullable|date|after_or_equal:start_date',
            // Pricing & Media
            'price' => 'nullable|numeric|min:0',
            'is_free' => 'boolean',
            'youtube_url' => 'nullable|url',
            'thumbnail' => 'nullable|image|max:2048',
            'is_featured' => 'boolean',
        ]);

        if ($request->hasFile('thumbnail')) {
            // Delete old thumbnail if exists
            if ($program->thumbnail_path) {
                \Illuminate\Support\Facades\Storage::disk('public')->delete($program->thumbnail_path);
            }
            $validated['thumbnail_path'] = $request->file('thumbnail')->store('programs', 'public');
        }

        // Clear fields based on program type
        if ($validated['type'] === 'rolling') {
            // Rolling programs use age_target only
            $validated['cohort_age_min'] = null;
            $validated['cohort_age_max'] = null;
            $validated['start_date'] = null;
            $validated['end_date'] = null;
            $validated['price'] = null;
            $validated['is_free'] = false;
            $validated['youtube_url'] = null;
        } elseif ($validated['type'] === 'journey') {
            // Journey programs use age range but no fixed dates
            $validated['age_target'] = null;
            $validated['start_date'] = null;
            $validated['end_date'] = null;
            if (isset($validated['is_free']) && $validated['is_free']) {
                $validated['price'] = 0;
            } else {
                $validated['is_free'] = false;
            }
        } elseif ($validated['type'] === 'offline') {
            // Offline programs use age range and CAN have dates
            $validated['age_target'] = null;
            if (isset($validated['is_free']) && $validated['is_free']) {
                $validated['price'] = 0;
            } else {
                $validated['is_free'] = false;
            }
        } else {
            // Scheduled (cohort) programs use cohort ages and dates
            $validated['age_target'] = null;
            if (isset($validated['is_free']) && $validated['is_free']) {
                $validated['price'] = 0;
            } else {
                $validated['is_free'] = false; // Ensure it's false if not checked
            }
        }

        $validated['is_featured'] = $request->has('is_featured');

        $program->update($validated);

        // If a program status or age target changes, sync rolling programs for all children
        if ($program->type === 'rolling') {
            app(AutoAssignChildService::class)->syncAllChildren();
        }

        return redirect()
            ->route('admin.programs.index')
            ->with('success', 'Program updated successfully.');
    }

    public function destroy(Program $program)
    {
        $program->delete();

        return redirect()
            ->route('admin.programs.index')
            ->with('success', 'Program deleted.');
    }

    public function unassignChild(Program $program, User $child)
    {
        if ($program->type === 'rolling') {
            return back()->with('error', 'Cannot manually unassign children from rolling programs.');
        }

        $program->children()->detach($child->id);

        return back()->with('success', 'Child unassigned successfully.');
    }
}
