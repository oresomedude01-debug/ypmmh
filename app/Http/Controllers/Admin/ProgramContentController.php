<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Program;
use App\Models\ProgramContent;
use Illuminate\Http\Request;


class ProgramContentController extends Controller
{
    /**
     * List contents for a program.
     */
    public function index(Program $program)
    {
        $contents = $program->contents();

        if ($program->type === 'rolling') {
            $contents->orderBy('week_number')
                ->orderBy('day_number')
                ->orderBy('time_of_day');
        } elseif ($program->type === 'journey') {
            $contents->orderBy('week_offset')
                ->orderBy('day_offset')
                ->orderBy('time_of_day');
        } else {
            $contents->orderBy('publish_at');
        }

        $contents = $contents->get();

        return view('Admin/Programs/Contents/index', [
            'program' => $program,
            'contents' => $contents,
        ]);
    }

    /**
     * Show create content form.
     */
    public function create(Program $program)
    {
        return view('Admin/Programs/Contents/create', [
            'program' => $program,
            'contentTypes' => [
                ['label' => 'Video & PDF (Lesson)', 'value' => 'video_pdf'],
                ['label' => 'PDF Only (Activity/Assignment)', 'value' => 'pdf_only'],
            ],
        ]);
    }

    /**
     * Store new content.
     */
    public function store(Request $request, Program $program)
    {
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

        if ($program->type === 'journey') {
            $rules = array_merge($rules, [
                'week_offset' => 'required|integer|min:0',
                'day_offset' => 'required|integer|min:0|max:6',
                'time_of_day' => 'required|date_format:H:i',
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

        // Journey: use offsets
        if ($program->type === 'journey') {
            $data['week_offset'] = $validated['week_offset'];
            $data['day_offset'] = $validated['day_offset'];
            $data['time_of_day'] = $validated['time_of_day'];
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
            $message = "New content added to {$program->name}: {$content->title}";

            $child->notify(new \App\Notifications\ProgramUpdateNotification([
                'type' => 'new_content',
                'message' => $message,
                'program_id' => $program->id,
                'content_id' => $content->id
            ]));

            // Notify Parent
            if ($child->parent) {
                $child->parent->notify(new \App\Notifications\ProgramUpdateNotification([
                    'type' => 'new_lesson',
                    'message' => "A new lesson '{$content->title}' has been added to the '{$program->name}' program for {$child->first_name}.",
                    'program_id' => $program->id,
                    'content_id' => $content->id
                ]));
            }
        }

        return redirect()
            ->route('admin.programs.show', $program)
            ->with('success', 'Content added successfully.');
    }

    /**
     * Show edit form.
     */
    public function edit(Program $program, ProgramContent $content)
    {
        return view('Admin/Programs/Contents/edit', [
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
    public function update(Request $request, Program $program, ProgramContent $content)
    {
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

        if ($program->type === 'journey') {
            $rules = array_merge($rules, [
                'week_offset' => 'required|integer|min:0',
                'day_offset' => 'required|integer|min:0|max:6',
                'time_of_day' => 'required|date_format:H:i',
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

        if ($program->type === 'journey') {
            $data['week_offset'] = $validated['week_offset'];
            $data['day_offset'] = $validated['day_offset'];
            $data['time_of_day'] = $validated['time_of_day'];
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
            ->route('admin.programs.show', $program)
            ->with('success', 'Content updated successfully.');
    }

    /**
     * Delete content.
     */
    public function destroy(Program $program, ProgramContent $content)
    {
        $content->delete();

        return redirect()
            ->route('admin.programs.contents.index', $program)
            ->with('success', 'Content deleted successfully.');
    }
}
