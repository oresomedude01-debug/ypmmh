<?php

namespace App\Http\Controllers\Mentor;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class NotificationController extends Controller
{
    public function index()
    {
        $notifications = Auth::user()->notifications()->paginate(15);
        return view('mentor.notifications.index', compact('notifications'));
    }

    public function markAsRead($id)
    {
        $notification = Auth::user()->notifications()->where('id', $id)->first();
        if ($notification) {
            $notification->markAsRead();
        }

        return back()->with('success', 'Notification marked as read.');
    }

    /**
     * Mark notification as read and redirect to its relevant page.
     */
    public function markAsReadAndRedirect($id)
    {
        $notification = Auth::user()->notifications()->where('id', $id)->first();

        if (!$notification) {
            return redirect()->route('mentor.notifications.index');
        }

        $notification->markAsRead();

        return redirect($this->resolveUrl($notification->data));
    }

    public function markAllAsRead()
    {
        Auth::user()->unreadNotifications->markAsRead();
        return back()->with('success', 'All notifications marked as read.');
    }

    public function destroy($id)
    {
        $notification = Auth::user()->notifications()->where('id', $id)->first();
        if ($notification) {
            $notification->delete();
        }

        return back()->with('success', 'Notification deleted.');
    }

    /**
     * Resolve a destination URL from notification data.
     */
    private function resolveUrl(array $data): string
    {
        $type = $data['type'] ?? null;

        return match ($type) {
            'new_program_available', 'program_update' => isset($data['program_id'])
                ? route('mentor.programs.show', $data['program_id'])
                : route('mentor.programs.index'),
            'blog_post', 'new_blog_post', 'blog_published' => route('mentor.blogs.index'),
            'birthday'  => route('mentor.notifications.index'),
            default     => route('mentor.notifications.index'),
        };
    }
}
