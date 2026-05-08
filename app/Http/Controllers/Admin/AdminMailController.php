<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Mail\AdminDirectMail;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class AdminMailController extends Controller
{
    /**
     * Display the mail form view.
     */
    public function index()
    {
        // Get students and parents for dropdown
        $students = User::role('Child')
            ->select('id', 'first_name', 'last_name', 'email')
            ->orderBy('first_name')
            ->get()
            ->map(function ($user) {
                return [
                    'id' => $user->id,
                    'name' => "{$user->first_name} {$user->last_name}",
                    'email' => $user->email,
                    'type' => 'Student',
                ];
            });

        $parents = User::role('Parent')
            ->select('id', 'first_name', 'last_name', 'email')
            ->orderBy('first_name')
            ->get()
            ->map(function ($user) {
                return [
                    'id' => $user->id,
                    'name' => "{$user->first_name} {$user->last_name}",
                    'email' => $user->email,
                    'type' => 'Parent',
                ];
            });

        $recipients = $students->concat($parents)->sortBy('name')->values();

        return view('Admin.Mail.index', compact('recipients'));
    }

    /**
     * Send direct mail to student or parent.
     */
    public function send(Request $request)
    {
        $validated = $request->validate([
            'recipient_id' => 'required|integer|exists:users,id',
            'recipient_type' => 'required|in:Student,Parent',
            'subject' => 'required|string|max:255',
            'message' => 'required|string|max:5000',
        ]);

        try {
            $recipient = User::findOrFail($validated['recipient_id']);

            // Ensure the user has the correct role
            if ($validated['recipient_type'] === 'Student' && !$recipient->hasRole('Child')) {
                return back()->with('error', 'Selected recipient is not a student.');
            }

            if ($validated['recipient_type'] === 'Parent' && !$recipient->hasRole('Parent')) {
                return back()->with('error', 'Selected recipient is not a parent.');
            }

            // Verify email is set
            if (!$recipient->email) {
                return back()->with('error', 'Selected recipient does not have a valid email address.');
            }

            // Send email
            Mail::to($recipient->email)->send(
                new AdminDirectMail(
                    recipientName: $recipient->first_name,
                    recipientType: $validated['recipient_type'],
                    subject: $validated['subject'],
                    body: $validated['message'],
                    senderName: auth()->user()->first_name . ' ' . auth()->user()->last_name,
                )
            );

            return back()->with('success', 'Email sent successfully to ' . $recipient->first_name . ' (' . $recipient->email . ')');
        } catch (\Throwable $e) {
            $errorMsg = $e->getMessage();
            \Log::error('Admin Mail Error: ' . $errorMsg, [
                'recipient_type' => $validated['recipient_type'] ?? null,
                'recipient_id' => $validated['recipient_id'] ?? null,
                'exception' => class_basename($e),
                'line' => $e->getLine(),
                'file' => $e->getFile(),
            ]);
            
            // Return more specific error for debugging
            $displayError = config('app.debug') 
                ? 'Failed to send email: ' . $errorMsg
                : 'Failed to send email. Please try again.';
            
            return back()->with('error', $displayError);
        }
    }

    /**
     * Get recipients via AJAX for dynamic dropdown.
     */
    public function getRecipients(Request $request)
    {
        $type = $request->get('type'); // 'Student' or 'Parent'
        $search = $request->get('search', '');

        if ($type === 'Student') {
            $recipients = User::role('Child')
                ->where(function ($query) use ($search) {
                    $query->where('first_name', 'like', "%{$search}%")
                        ->orWhere('last_name', 'like', "%{$search}%")
                        ->orWhere('email', 'like', "%{$search}%");
                })
                ->select('id', 'first_name', 'last_name', 'email')
                ->orderBy('first_name')
                ->limit(50)
                ->get()
                ->map(function ($user) {
                    return [
                        'id' => $user->id,
                        'name' => "{$user->first_name} {$user->last_name} ({$user->email})",
                        'email' => $user->email,
                    ];
                });
        } else {
            $recipients = User::role('Parent')
                ->where(function ($query) use ($search) {
                    $query->where('first_name', 'like', "%{$search}%")
                        ->orWhere('last_name', 'like', "%{$search}%")
                        ->orWhere('email', 'like', "%{$search}%");
                })
                ->select('id', 'first_name', 'last_name', 'email')
                ->orderBy('first_name')
                ->limit(50)
                ->get()
                ->map(function ($user) {
                    return [
                        'id' => $user->id,
                        'name' => "{$user->first_name} {$user->last_name} ({$user->email})",
                        'email' => $user->email,
                    ];
                });
        }

        return response()->json($recipients);
    }
}
