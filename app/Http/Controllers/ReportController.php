<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ReportController extends Controller
{
    public function store(Request $request)
    {
        $validated = $request->validate([
            'reportable_id' => 'required|integer',
            'reportable_type' => 'required|string',
            'reason' => 'required|string|min:5',
        ]);

        $report = \App\Models\Report::create([
            'reporter_id' => auth()->id(),
            'reportable_id' => $validated['reportable_id'],
            'reportable_type' => $validated['reportable_type'], // Ensure full class name is sent or mapped
            'reason' => $validated['reason'],
        ]);

        // Notify Admins
        $admins = \App\Models\User::role('Admin')->get();
        \Illuminate\Support\Facades\Notification::send($admins, new \App\Notifications\NewReportNotification($report));

        return back()->with('success', 'Report submitted successfully. Admins have been notified.');
    }
}
