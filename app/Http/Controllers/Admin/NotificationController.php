<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class NotificationController extends Controller
{
    public function index()
    {
        $notifications = Auth::user()->notifications()->paginate(15);
        return view('Admin.Notifications.Index', compact('notifications'));
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
            return redirect()->route('admin.notifications.index');
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
            'new_user_registration'  => route('admin.users.index'),
            'new_program_available'  => isset($data['program_id'])
                ? route('admin.programs.show', $data['program_id'])
                : route('admin.programs.index'),
            'blog_post', 'new_blog_post' => route('admin.blogs.index'),
            'blog_published'         => isset($data['slug'])
                ? route('blog.show', $data['slug'])
                : route('admin.blogs.index'),
            'report'                 => isset($data['report_id'])
                ? route('admin.reports.show', $data['report_id'])
                : route('admin.reports.index'),
            'birthday'               => route('admin.children.index'),
            default                  => route('admin.notifications.index'),
        };
    }
}
