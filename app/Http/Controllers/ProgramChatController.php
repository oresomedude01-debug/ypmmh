<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Program;
use App\Models\ProgramMessage;
use App\Models\Enrollment;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;

class ProgramChatController extends Controller
{
    public function fetchMessages(Request $request, Program $program)
    {
        $this->authorizeAccess($program);

        $lastId = $request->query('last_id');

        $query = $program->messages()
            ->with(['user:id,first_name,last_name,profile_picture', 'reactions']);

        if ($lastId) {
            // Polling for new messages
            $messages = $query->where('id', '>', $lastId)
                ->orderBy('id', 'asc')
                ->get();
        } else {
            // Initial load - get latest 50 and sort them back to chronological
            $messages = $query->orderBy('id', 'desc')
                ->take(50)
                ->get()
                ->reverse()
                ->values();
        }

        // Update last read status
        $enrollment = Enrollment::where('program_id', $program->id)
            ->where('user_id', Auth::id())
            ->first();
        if ($enrollment) {
            $enrollment->update(['last_read_at' => now()]);
        }

        $formattedMessages = $messages->map(function ($message) use ($program) {
            $reactions = $message->reactions;
            return [
                'id' => $message->id,
                'content' => $message->content,
                'user_name' => $message->user->full_name,
                'user_id' => $message->user_id,
                'is_me' => Auth::id() === $message->user_id,
                'is_moderator' => $this->isModerator($program, $message->user),
                'created_at' => $message->created_at->format('M d, H:i'),
                'can_delete' => Gate::allows('delete', $message),
                'reactions' => [
                    'like' => $reactions->where('type', 'like')->count(),
                    'love' => $reactions->where('type', 'love')->count(),
                    'dislike' => $reactions->where('type', 'dislike')->count(),
                ],
                'my_reaction' => $reactions->where('user_id', Auth::id())->first()->type ?? null,
            ];
        });

        return response()->json($formattedMessages);
    }

    public function toggleReaction(Request $request, Program $program, ProgramMessage $message)
    {
        $this->authorizeAccess($program);

        $validated = $request->validate([
            'type' => 'required|in:like,love,dislike',
        ]);

        $reaction = $message->reactions()
            ->where('user_id', Auth::id())
            ->where('type', $validated['type'])
            ->first();

        if ($reaction) {
            $reaction->delete();
            $status = 'removed';
        } else {
            // Remove any other reaction type first (single reaction per user per message)
            $message->reactions()->where('user_id', Auth::id())->delete();

            $message->reactions()->create([
                'user_id' => Auth::id(),
                'type' => $validated['type'],
            ]);
            $status = 'added';

            // Check achievements for the receiver and the giver
            $message->user->checkAchievements();
            Auth::user()->checkAchievements();
        }

        return response()->json([
            'success' => true,
            'status' => $status,
            'reactions' => [
                'like' => $message->reactions()->where('type', 'like')->count(),
                'love' => $message->reactions()->where('type', 'love')->count(),
                'dislike' => $message->reactions()->where('type', 'dislike')->count(),
            ]
        ]);
    }

    public function sendMessage(Request $request, Program $program)
    {
        $this->authorizeAccess($program);

        // Check if suspended
        $enrollment = Enrollment::where('program_id', $program->id)
            ->where('user_id', Auth::id())
            ->first();

        if ($enrollment && $enrollment->chat_status === 'suspended') {
            return response()->json(['error' => 'You are suspended from this chat.'], 403);
        }

        $validated = $request->validate([
            'content' => 'required|string|max:1000',
        ]);

        $message = $program->messages()->create([
            'user_id' => Auth::id(),
            'content' => $validated['content'],
        ]);

        // Notify all members except the sender
        // This includes Mentor, Admin (if they want to be notified, though usually they don't), and all Children
        $sender = Auth::user();
        $notificationData = [
            'type' => 'chat',
            'message' => "{$sender->first_name} sent a message in {$program->name}: " . substr($validated['content'], 0, 50) . '...',
            'program_id' => $program->id
        ];

        // 1. Notify Children
        $program->children->each(function ($child) use ($sender, $notificationData) {
            if ($child->id !== $sender->id) {
                $child->notify(new \App\Notifications\ProgramUpdateNotification($notificationData));
            }
        });

        // 2. Notify Mentor (if not the sender)
        if ($program->mentor && $program->mentor->id !== $sender->id) {
            $program->mentor->notify(new \App\Notifications\ProgramUpdateNotification($notificationData));
        }

        Auth::user()->checkAchievements();

        return response()->json(['success' => true]);
    }

    public function deleteMessage(ProgramMessage $message)
    {
        Gate::authorize('delete', $message);
        $message->delete();
        return response()->json(['success' => true]);
    }

    public function toggleSuspension(Request $request, Program $program, $userId)
    {
        // Only Admin or the program mentor can suspend/reinstate
        if (!Auth::user()->hasRole('Admin') && Auth::id() !== $program->mentor_id) {
            abort(403);
        }

        $enrollment = Enrollment::where('program_id', $program->id)
            ->where('user_id', $userId)
            ->firstOrFail();

        $newStatus = $enrollment->chat_status === 'active' ? 'suspended' : 'active';
        $enrollment->update(['chat_status' => $newStatus]);

        $child = \App\Models\User::find($userId);

        if ($newStatus === 'suspended') {
            $message = "Your chat access for '{$program->name}' has been suspended for review.";
            $parentMessage = "{$child->first_name}'s chat access for '{$program->name}' has been suspended following a review by the guide.";
        } else {
            $message = "Your chat access for '{$program->name}' has been reinstated.";
            $parentMessage = "{$child->first_name}'s chat access for '{$program->name}' has been reinstated.";
        }

        $child->notify(new \App\Notifications\ProgramUpdateNotification([
            'type' => 'chat_suspension',
            'message' => $message,
            'program_id' => $program->id
        ]));

        if ($child->parent) {
            $child->parent->notify(new \App\Notifications\ProgramUpdateNotification([
                'type' => 'chat_suspension',
                'message' => $parentMessage,
                'program_id' => $program->id
            ]));
        }

        return response()->json([
            'success' => true,
            'status' => $newStatus
        ]);
    }

    public function communityHub(Request $request)
    {
        $user = Auth::user();
        $query = Program::query();

        if ($user->hasRole('Mentor')) {
            $query->where('mentor_id', $user->id);
        }

        // Search/Filter for Admin
        if ($user->hasRole('Admin') && $request->filled('search')) {
            $query->where('name', 'like', '%' . $request->search . '%');
        }

        $programs = $query->withCount('children')->get();

        return view('communities.index', compact('programs'));
    }

    public function fullScreenChat(Program $program)
    {
        $this->authorizeAccess($program);
        return view('communities.show', compact('program'));
    }

    private function authorizeAccess(Program $program)
    {
        $user = Auth::user();
        if ($user->hasRole('Admin'))
            return;
        if ($user->id === $program->mentor_id)
            return;

        $isEnrolled = Enrollment::where('program_id', $program->id)
            ->where('user_id', $user->id)
            ->exists();

        if (!$isEnrolled) {
            abort(403, 'You are not enrolled in this program community.');
        }
    }

    private function isModerator($program, $user)
    {
        if ($user->hasRole('Admin'))
            return true;
        if ($user->id === $program->mentor_id)
            return true;
        return false;
    }
}
