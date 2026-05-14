<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Program;
use App\Models\Enrollment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use App\Services\AutoAssignChildService;

class UserController extends Controller
{
    /**
     * Display a listing of the users.
     */
    public function index(Request $request)
    {
        $query = User::with('roles', 'parent');

        // Search by name or email
        if ($request->has('search') && $request->search) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('first_name', 'like', "%{$search}%")
                    ->orWhere('last_name', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%");
            });
        }

        // Filter by role
        if ($request->has('role') && $request->role) {
            $query->whereHas('roles', function ($q) use ($request) {
                $q->where('name', $request->role);
            });
        }

        // Filter by verification status
        if ($request->has('status') && $request->status) {
            if ($request->status === 'verified') {
                $query->whereNotNull('email_verified_at');
            } elseif ($request->status === 'pending') {
                $query->whereNull('email_verified_at');
            } elseif ($request->status === 'deleted') {
                $query->onlyTrashed();
            }
        }

        // Include trashed users if specified
        if ($request->has('include_deleted')) {
            $query->withTrashed();
        }

        $users = $query->latest()->paginate(15);

        // Calculate stats
        $stats = [
            'totalUsers' => User::count(),
            'adminCount' => User::role('Admin')->count(),
            'mentorCount' => User::role('Mentor')->count(),
            'parentCount' => User::role('Parent')->count(),
            'childCount' => User::role('Child')->count(),
            'verifiedCount' => User::whereNotNull('email_verified_at')->count(),
            'pendingCount' => User::whereNull('email_verified_at')->count(),
            'deletedCount' => User::onlyTrashed()->count(),
        ];

        return view('Admin/Users/index', [
            'users' => $users,
            'stats' => $stats,
            'filters' => [
                'search' => $request->search,
                'role' => $request->role,
                'status' => $request->status,
            ],
        ]);
    }

    /**
     * Show the form for creating a new user.
     */
    public function create()
    {
        return view('Admin/Users/create');
    }

    /**
     * Store a newly created user in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'email' => 'required|email|unique:users',
            'password' => 'required|string|min:8|confirmed',
            'gender' => 'required|in:male,female',
            'date_of_birth' => 'nullable|date',
            'address' => 'nullable|string|max:500',
            'profile_picture' => 'nullable|image|max:2048',
            'phone_number' => 'nullable|string|max:20',
            'role' => 'required|string',
            'parent_email' => 'nullable|email',
            'parent_first_name' => 'nullable|string|max:255',
            'parent_last_name' => 'nullable|string|max:255',
            'parent_phone_number' => 'nullable|string|max:20',
            'parent_address' => 'nullable|string|max:500',
            'relationship' => 'nullable|string|max:100',
        ]);

        $profilePicturePath = null;
        if ($request->hasFile('profile_picture')) {
            $profilePicturePath = $request->file('profile_picture')->store('profile-pictures', 'public');
        }

        // Handle parent assignment if role is Child
        $parentId = null;
        if ($validated['role'] === 'Child' && $validated['parent_email']) {
            $parent = User::where('email', $validated['parent_email'])->first();

            if (!$parent) {
                // Create new parent if doesn't exist
                $parent = User::create([
                    'first_name' => $validated['parent_first_name'] ?? null,
                    'last_name' => $validated['parent_last_name'] ?? null,
                    'email' => $validated['parent_email'],
                    'password' => bcrypt('password'),
                    'phone_number' => $validated['parent_phone_number'] ?? null,
                    'address' => $validated['parent_address'] ?? null,
                ]);
                $parent->assignRole('Parent');
            }

            $parentId = $parent->id;
        }

        // Generate unique number for child
        $uniqueNumber = null;
        if ($validated['role'] === 'Child') {
            $uniqueNumber = 'CHD' . strtoupper(uniqid());
        }

        $user = User::create([
            'first_name' => $validated['first_name'],
            'last_name' => $validated['last_name'],
            'email' => $validated['email'],
            'password' => bcrypt($validated['password']),
            'gender' => $validated['gender'],
            'date_of_birth' => $validated['date_of_birth'] ?? null,
            'address' => $validated['address'] ?? null,
            'profile_picture' => $profilePicturePath,
            'phone_number' => $validated['phone_number'] ?? null,
            'parent_id' => $parentId,
            'unique_number' => $uniqueNumber,
            'relationship' => $validated['relationship'] ?? null,
        ]);

        if ($validated['role']) {
            $user->assignRole($validated['role']);
        }

        if ($validated['role'] === 'Child') {
            app(AutoAssignChildService::class)->syncRollingPrograms($user);

            // Generate a temporary signed URL for verification and password setup
            $verificationUrl = \Illuminate\Support\Facades\URL::temporarySignedRoute(
                'child.setup',
                now()->addDays(7),
                ['id' => $user->id, 'hash' => sha1($user->email)]
            );

            // Send Custom Welcome Email
            \Illuminate\Support\Facades\Mail::to($user->email)->send(new \App\Mail\ChildWelcomeMail($user, $user->parent, $verificationUrl, $validated['password']));

            // Notify Parent if exists
            if ($user->parent) {
                $user->parent->notify(new \App\Notifications\MenteeCreatedNotification($user));
            }
        }

        return redirect()->route('admin.users.index')
            ->with('success', 'User created successfully.');
    }

    /**
     * Show the form for editing the specified user.
     */
    public function edit(User $user)
    {
        return view('Admin.Users.edit', [
            'user' => $user->load('roles', 'parent'),
        ]);
    }

    /**
     * Update the specified user in storage.
     */
    public function update(Request $request, User $user)
    {
        $validated = $request->validate([
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $user->id,
            'password' => 'nullable|string|min:8|confirmed',
            'gender' => 'required|in:male,female',
            'date_of_birth' => 'nullable|date',
            'address' => 'nullable|string|max:500',
            'profile_picture' => 'nullable|image|max:2048',
            'phone_number' => 'nullable|string|max:20',
            'role' => 'required|string',
            'parent_email' => 'nullable|email',
            'parent_first_name' => 'nullable|string|max:255',
            'parent_last_name' => 'nullable|string|max:255',
            'parent_phone_number' => 'nullable|string|max:20',
            'parent_address' => 'nullable|string|max:500',
            'relationship' => 'nullable|string|max:100',
        ]);

        // Handle parent assignment if role is Child
        $parentId = $user->parent_id;
        if ($validated['role'] === 'Child' && $validated['parent_email']) {
            $parent = User::where('email', $validated['parent_email'])->first();

            if (!$parent) {
                // Create new parent if doesn't exist
                $parent = User::create([
                    'first_name' => $validated['parent_first_name'] ?? null,
                    'last_name' => $validated['parent_last_name'] ?? null,
                    'email' => $validated['parent_email'],
                    'password' => bcrypt('password'),
                    'phone_number' => $validated['parent_phone_number'] ?? null,
                    'address' => $validated['parent_address'] ?? null,
                ]);
                $parent->assignRole('Parent');
            }

            $parentId = $parent->id;
        }

        // Generate unique number for child if not exists
        $uniqueNumber = $user->unique_number;
        if ($validated['role'] === 'Child' && !$uniqueNumber) {
            $uniqueNumber = 'CHD' . strtoupper(uniqid());
        }

        $updateData = [
            'first_name' => $validated['first_name'],
            'last_name' => $validated['last_name'],
            'email' => $validated['email'],
            'gender' => $validated['gender'],
            'date_of_birth' => $validated['date_of_birth'] ?? $user->date_of_birth,
            'address' => $validated['address'] ?? $user->address,
            'phone_number' => $validated['phone_number'] ?? $user->phone_number,
            'parent_id' => $validated['role'] === 'Child' ? $parentId : null,
            'unique_number' => $validated['role'] === 'Child' ? $uniqueNumber : null,
            'relationship' => $validated['relationship'] ?? $user->relationship,
        ];

        if (!empty($validated['password'])) {
            $updateData['password'] = bcrypt($validated['password']);
        }

        if ($request->hasFile('profile_picture')) {
            // Delete old picture if exists
            if ($user->profile_picture) {
                Storage::disk('public')->delete($user->profile_picture);
            }
            $updateData['profile_picture'] = $request->file('profile_picture')->store('profile-pictures', 'public');
        }

        $user->update($updateData);

        // Sync role
        $user->syncRoles([$validated['role']]);

        if ($validated['role'] === 'Child') {
            app(AutoAssignChildService::class)->syncRollingPrograms($user);
        }

        return redirect()->route('admin.users.index')
            ->with('success', 'User updated successfully.');
    }

    /**
     * Remove the specified user from storage.
     */
    public function destroy(User $user)
    {
        $user->delete();

        return redirect()->route('admin.users.index')
            ->with('success', 'User soft-deleted successfully.');
    }

    /**
     * Restore the specified user from storage.
     */
    public function restore($id)
    {
        $user = User::withTrashed()->findOrFail($id);
        $user->restore();

        return redirect()->route('admin.users.index')
            ->with('success', 'User restored successfully.');
    }

    /**
     * Permanently remove the specified user from storage.
     */
    public function forceDelete($id)
    {
        $user = User::withTrashed()->findOrFail($id);
        $user->forceDelete();

        return redirect()->route('admin.users.index')
            ->with('success', 'User permanently deleted.');
    }

    /**
     * Export users to CSV.
     */
    public function export()
    {
        $users = User::with('roles')->get();

        $filename = 'users_' . date('Y-m-d_H-i-s') . '.csv';
        $handle = fopen('php://memory', 'w');

        // Add headers
        fputcsv($handle, ['First Name', 'Last Name', 'Email', 'Role', 'Status']);

        // Add data
        foreach ($users as $user) {
            fputcsv($handle, [
                $user->first_name,
                $user->last_name,
                $user->email,
                $user->roles?->first()?->name ?? 'No Role',
                $user->email_verified_at ? 'Verified' : 'Pending',
            ]);
        }

        rewind($handle);
        $csv = stream_get_contents($handle);
        fclose($handle);

        return response($csv)
            ->header('Content-Type', 'text/csv')
            ->header('Content-Disposition', "attachment; filename=\"{$filename}\"");
    }

    /**
     * Import users from CSV.
     */
    public function import(Request $request)
    {
        $request->validate([
            'csv_file' => 'required|file|mimes:csv,txt|max:5120',
        ]);

        $file = $request->file('csv_file');
        $handle = fopen($file->getRealPath(), 'r');
        $header = fgetcsv($handle); // Skip header row

        $imported = 0;
        $errors = [];
        $row = 1;

        while (($row_data = fgetcsv($handle)) !== false) {
            $row++;
            try {
                // Validate row data
                if (count($row_data) < 5) {
                    $errors[] = "Row {$row}: Invalid number of columns";
                    continue;
                }

                // Extract data (first 5 are required, rest are optional)
                [$first_name, $last_name, $email, $role, $status] = array_slice($row_data, 0, 5);
                $date_of_birth = isset($row_data[5]) && !empty(trim($row_data[5])) ? trim($row_data[5]) : null;
                $address = isset($row_data[6]) && !empty(trim($row_data[6])) ? trim($row_data[6]) : null;

                // Check if user already exists
                if (User::where('email', $email)->exists()) {
                    $errors[] = "Row {$row}: Email '{$email}' already exists";
                    continue;
                }

                // Create user with default password
                $user = User::create([
                    'first_name' => trim($first_name),
                    'last_name' => trim($last_name),
                    'email' => trim($email),
                    'password' => bcrypt('password'), // Default password
                    'date_of_birth' => $date_of_birth,
                    'address' => $address,
                    'email_verified_at' => strtolower(trim($status)) === 'verified' ? now() : null,
                ]);

                // Assign role if valid
                if (!empty(trim($role))) {
                    $user->assignRole(trim($role));
                }

                if ($user->hasRole('Child')) {
                    // Generate a temporary signed URL for verification and password setup
                    $verificationUrl = \Illuminate\Support\Facades\URL::temporarySignedRoute(
                        'child.setup',
                        now()->addDays(7),
                        ['id' => $user->id, 'hash' => sha1($user->email)]
                    );

                    // Send Custom Welcome Email
                    \Illuminate\Support\Facades\Mail::to($user->email)->send(new \App\Mail\ChildWelcomeMail($user, $user->parent ?? $user, $verificationUrl, 'password'));

                    // Notify Parent if exists
                    if ($user->parent) {
                        $user->parent->notify(new \App\Notifications\MenteeCreatedNotification($user));
                    }
                }

                $imported++;
            } catch (\Exception $e) {
                $errors[] = "Row {$row}: " . $e->getMessage();
            }
        }

        fclose($handle);

        return redirect()->route('admin.users.index')
            ->with('success', "Imported {$imported} users successfully.")
            ->with('errors', $errors);
    }

    /**
     * Download CSV template for importing users.
     */
    public function downloadTemplate()
    {
        $filename = 'users_import_template_' . date('Y-m-d_His') . '.csv';

        $callback = function () {
            $file = fopen('php://output', 'w');

            // Add header row
            fputcsv($file, [
                'First Name',
                'Last Name',
                'Email',
                'Role',
                'Status',
                'Date of Birth',
                'Address',
            ]);

            // Add example rows
            fputcsv($file, [
                'John',
                'Doe',
                'john.doe@example.com',
                'Parent',
                'Verified',
                '1985-05-15',
                '123 Main Street, City, State 12345',
            ]);

            fputcsv($file, [
                'Jane',
                'Smith',
                'jane.smith@example.com',
                'Mentor',
                'Pending',
                '1990-08-22',
                '456 Oak Avenue, Town, State 67890',
            ]);

            fputcsv($file, [
                'Michael',
                'Johnson',
                'michael.johnson@example.com',
                'Admin',
                'Verified',
                '1988-03-10',
                '789 Pine Road, Village, State 54321',
            ]);

            fclose($file);
        };

        return response()->streamDownload($callback, $filename, [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => "attachment; filename=\"{$filename}\"",
        ]);
    }

    /**
     * Display a listing of mentors.
     */
    public function mentors(Request $request)
    {
        $query = User::role('Mentor')->with('roles');

        if ($request->has('search') && $request->search) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('first_name', 'like', "%{$search}%")
                    ->orWhere('last_name', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%");
            });
        }

        if ($request->has('status') && $request->status) {
            if ($request->status === 'verified') {
                $query->whereNotNull('email_verified_at');
            } elseif ($request->status === 'pending') {
                $query->whereNull('email_verified_at');
            }
        }

        $mentors = $query->latest()->paginate(15);

        return view('Admin.Mentors.Index', [
            'mentors' => $mentors,
            'filters' => [
                'search' => $request->search,
                'status' => $request->status,
            ],
        ]);
    }

    /**
     * Display a listing of parents.
     */
    public function parents(Request $request)
    {
        $query = User::role('Parent')->with('roles');

        if ($request->has('search') && $request->search) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('first_name', 'like', "%{$search}%")
                    ->orWhere('last_name', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%");
            });
        }

        if ($request->has('status') && $request->status) {
            if ($request->status === 'verified') {
                $query->whereNotNull('email_verified_at');
            } elseif ($request->status === 'pending') {
                $query->whereNull('email_verified_at');
            }
        }

        $parents = $query->latest()->paginate(15);

        return view('Admin.Parents.Index', [
            'parents' => $parents,
            'filters' => [
                'search' => $request->search,
                'status' => $request->status,
            ],
        ]);
    }

    /**
     * Display a listing of children.
     */
    public function children(Request $request)
    {
        $query = User::role('Child')->with('roles', 'parent');

        // Search by name or email
        if ($request->has('search') && $request->search) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('first_name', 'like', "%{$search}%")
                    ->orWhere('last_name', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%")
                    ->orWhere('unique_number', 'like', "%{$search}%");
            });
        }

        // Filter by verification status
        if ($request->has('status') && $request->status) {
            if ($request->status === 'verified') {
                $query->whereNotNull('email_verified_at');
            } elseif ($request->status === 'pending') {
                $query->whereNull('email_verified_at');
            }
        }

        $children = $query->latest()->paginate(15);

        return view('Admin.Children.Index', [
            'children' => $children,
            'filters' => [
                'search' => $request->search,
                'status' => $request->status,
            ],
        ]);
    }
    /**
     * Display the specified mentor's profile and management dashboard.
     */
    public function mentorShow(User $user)
    {
        if (!$user->hasRole('Mentor')) {
            abort(403, 'User is not a mentor');
        }

        // Load programs assigned to this mentor
        $assignedPrograms = Program::where('mentor_id', $user->id)->with('children')->get();

        // Get all children associated with these programs
        $children = $assignedPrograms->flatMap(function ($program) {
            return $program->children;
        })->unique('id');

        // All programs (for assignment dropdown)
        $allPrograms = Program::all();

        return view('Admin.Mentors.show', [
            'mentor' => $user,
            'programs' => $assignedPrograms,
            'children' => $children,
            'allPrograms' => $allPrograms,
        ]);
    }

    /**
     * Assign a program to a mentor.
     */
    public function assignProgram(Request $request, User $user)
    {
        $request->validate([
            'program_id' => 'required|exists:programs,id',
        ]);

        $program = Program::findOrFail($request->program_id);
        $program->update(['mentor_id' => $user->id]);

        return back()->with('success', 'Program assigned successfully.');
    }

    /**
     * Unassign a program from a mentor.
     */
    public function unassignProgram(User $user, Program $program)
    {
        if ($program->mentor_id == $user->id) {
            $program->update(['mentor_id' => null]);
        }

        return back()->with('success', 'Program unassigned successfully.');
    }

    /**
     * Display the specified child's profile and management dashboard.
     */
    public function childShow(User $user)
    {
        if (!$user->hasRole('Child')) {
            abort(403, 'User is not a child/student');
        }

        // Auto-assign/unassign rolling programs on every profile view to ensure real-time accuracy
        app(AutoAssignChildService::class)->syncRollingPrograms($user);

        $user->load(['parent', 'enrollments.program']);

        // Get all scheduled programs the child could potentially join (not already enrolled)
        $availablePrograms = Program::where('type', 'scheduled')
            ->whereDoesntHave('children', function ($query) use ($user) {
                $query->where('users.id', $user->id);
            })
            ->get();

        return view('Admin.Children.show', [
            'child' => $user,
            'enrollments' => $user->enrollments,
            'availablePrograms' => $availablePrograms,
        ]);
    }

    /**
     * Enroll a child in a program.
     */
    public function enrollChild(Request $request, User $user)
    {
        $request->validate([
            'program_id' => [
                'required',
                'exists:programs,id',
                function ($attribute, $value, $fail) {
                    $program = Program::find($value);
                    if ($program && $program->type !== 'scheduled') {
                        $fail('Only scheduled programs can be manually enrolled.');
                    }
                },
            ],
        ]);

        // Check if already enrolled
        $exists = Enrollment::where('user_id', $user->id)
            ->where('program_id', $request->program_id)
            ->exists();

        if ($exists) {
            return back()->with('error', 'Child is already enrolled in this program.');
        }

        Enrollment::create([
            'user_id' => $user->id,
            'program_id' => $request->program_id,
            'status' => 'active',
        ]);

        return back()->with('success', 'Child enrolled successfully.');
    }
}
