<?php

namespace App\Http\Controllers\Mentor;

use App\Http\Controllers\Controller;
use App\Models\Program;
use App\Models\ProgramContent;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class MentorProgramController extends Controller
{
    /**
     * Display a listing of programs assigned to the mentor.
     */
    public function index()
    {
        $mentor = Auth::user();

        $programs = Program::where('mentor_id', $mentor->id)
            ->withCount(['contents', 'enrollments'])
            ->orderBy('created_at', 'desc')
            ->get();

        return view('mentor.programs.index', [
            'programs' => $programs,
        ]);
    }

    /**
     * Display a specific program with its contents.
     */
    public function show(Program $program)
    {
        $mentor = Auth::user();

        // Ensure mentor can only view their assigned programs
        if ($program->mentor_id !== $mentor->id) {
            abort(403, 'Unauthorized access to this program.');
        }

        $program->load([
            'contents' => function ($query) use ($program) {
                if ($program->type === 'rolling') {
                    $query->orderBy('week_number')
                        ->orderBy('day_number')
                        ->orderBy('time_of_day');
                } else {
                    $query->orderBy('publish_at');
                }
            },
            'children'
        ]);

        return view('mentor.programs.show', [
            'program' => $program,
        ]);
    }

    /**
     * Show the form for creating content for a program.
     */
    public function createContent(Program $program)
    {
        $mentor = Auth::user();

        if ($program->mentor_id !== $mentor->id) {
            abort(403, 'Unauthorized access to this program.');
        }

        return view('mentor.programs.create-content', [
            'program' => $program,
            'contentTypes' => [
                ['label' => 'Video & PDF (Lesson)', 'value' => 'video_pdf'],
                ['label' => 'PDF Only (Activity/Assignment)', 'value' => 'pdf_only'],
            ],
        ]);
    }

    /**
     * Store new content for a program.
     */
    public function storeContent(Request $request, Program $program)
    {
        $mentor = Auth::user();

        if ($program->mentor_id !== $mentor->id) {
            abort(403, 'Unauthorized access to this program.');
        }

        $rules = [
            'title' => 'required|string|max:255',
            'type' => 'required|in:video_pdf,pdf_only',
        ];

        if ($program->type === 'rolling') {
            $rules = array_merge($rules, [
                'week' => 'required|integer|min:1',
                'day' => 'required|integer|min:1|max:7',
                'time' => 'required|date_format:H:i',
            ]);
        }

        if ($program->type === 'scheduled') {
            $rules = array_merge($rules, [
                'publish_at' => 'required|date',
            ]);
        }

        if ($request->type === 'video_pdf') {
            $rules['video_url'] = 'required|url';
            $rules['pdf'] = 'nullable|file|mimes:pdf|max:20480';
        }

        if ($request->type === 'pdf_only') {
            $rules['pdf'] = 'required|file|mimes:pdf|max:20480';
        }

        $validated = $request->validate($rules);

        // Map form fields to database columns
        $data = [
            'program_id' => $program->id,
            'title' => $validated['title'],
            'content_type' => $validated['type'],
        ];

        // Handle file upload
        if ($request->hasFile('pdf')) {
            $data['file_path'] = $request->file('pdf')->store('programs/pdfs', 'public');
        }

        if ($request->type === 'video_pdf' && $request->has('video_url')) {
            $data['youtube_url'] = $validated['video_url'];
        }

        // Rolling: use week, day, and time
        if ($program->type === 'rolling') {
            $data['week_number'] = $validated['week'];
            $data['day_number'] = $validated['day'];
            $data['time_of_day'] = $validated['time'];
            $data['publish_at'] = null;
        }

        // Scheduled: use publish_at date
        if ($program->type === 'scheduled') {
            $data['publish_at'] = $validated['publish_at'];
            $data['week_number'] = null;
            $data['day_number'] = null;
            $data['time_of_day'] = null;
        }

        $content = ProgramContent::create($data);

        // Notify enrolled children and their parents
        $children = $program->children;
        foreach ($children as $child) {
            $child->notify(new \App\Notifications\ProgramUpdateNotification([
                'type' => 'new_content',
                'message' => "Your mentor added new content to {$program->name}: {$content->title}",
                'program_id' => $program->id,
                'content_id' => $content->id
            ]));

            // Notify Parent
            if ($child->parent) {
                $child->parent->notify(new \App\Notifications\ProgramUpdateNotification([
                    'type' => 'new_lesson',
                    'message' => "A new lesson '{$content->title}' has been added by the mentor for {$child->first_name} in '{$program->name}'.",
                    'program_id' => $program->id,
                    'content_id' => $content->id
                ]));
            }
        }

        return redirect()
            ->route('mentor.programs.show', $program)
            ->with('success', 'Content added successfully.');
    }

    /**
     * Show the form for editing content.
     */
    public function editContent(Program $program, ProgramContent $content)
    {
        $mentor = Auth::user();

        if ($program->mentor_id !== $mentor->id) {
            abort(403, 'Unauthorized access to this program.');
        }

        return view('mentor.programs.edit-content', [
            'program' => $program,
            'content' => $content,
            'contentTypes' => [
                ['label' => 'Video & PDF (Lesson)', 'value' => 'video_pdf'],
                ['label' => 'PDF Only (Activity/Assignment)', 'value' => 'pdf_only'],
            ],
        ]);
    }

    /**
     * Update content.
     */
    public function updateContent(Request $request, Program $program, ProgramContent $content)
    {
        $mentor = Auth::user();

        if ($program->mentor_id !== $mentor->id) {
            abort(403, 'Unauthorized access to this program.');
        }

        $rules = [
            'title' => 'required|string|max:255',
            'type' => 'required|in:video_pdf,pdf_only',
        ];

        if ($program->type === 'rolling') {
            $rules = array_merge($rules, [
                'week' => 'required|integer|min:1',
                'day' => 'required|integer|min:1|max:7',
                'time' => 'required|date_format:H:i',
            ]);
        }

        if ($program->type === 'scheduled') {
            $rules = array_merge($rules, [
                'publish_at' => 'required|date',
            ]);
        }

        if ($request->type === 'video_pdf') {
            $rules['video_url'] = 'required|url';
            $rules['pdf'] = 'nullable|file|mimes:pdf|max:20480';
        }

        if ($request->type === 'pdf_only') {
            $rules['pdf'] = 'nullable|file|mimes:pdf|max:20480';
        }

        $validated = $request->validate($rules);

        // Map form fields to database columns
        $data = [
            'title' => $validated['title'],
            'content_type' => $validated['type'],
        ];

        if ($request->hasFile('pdf')) {
            $data['file_path'] = $request->file('pdf')->store('programs/pdfs', 'public');
        }

        if ($request->type === 'video_pdf' && $request->has('video_url')) {
            $data['youtube_url'] = $validated['video_url'];
        } elseif ($request->type === 'pdf_only') {
            $data['youtube_url'] = null; // Clear video URL if switching to pdf_only
        }

        if ($program->type === 'rolling') {
            $data['week_number'] = $validated['week'];
            $data['day_number'] = $validated['day'];
            $data['time_of_day'] = $validated['time'];
            $data['publish_at'] = null;
        }

        if ($program->type === 'scheduled') {
            $data['publish_at'] = $validated['publish_at'];
            $data['week_number'] = null;
            $data['day_number'] = null;
            $data['time_of_day'] = null;
        }

        $content->update($data);

        return redirect()
            ->route('mentor.programs.show', $program)
            ->with('success', 'Content updated successfully.');
    }

    /**
     * Delete content.
     */
    public function destroyContent(Program $program, ProgramContent $content)
    {
        $mentor = Auth::user();

        if ($program->mentor_id !== $mentor->id) {
            abort(403, 'Unauthorized access to this program.');
        }

        $content->delete();

        return redirect()
            ->route('mentor.programs.show', $program)
            ->with('success', 'Content deleted successfully.');
    }
}
