<?php

namespace App\Http\Controllers\Child;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ChildNotificationController extends Controller
{
    public function index()
    {
        $notifications = Auth::user()->notifications()->paginate(15);
        Auth::user()->unreadNotifications->markAsRead();

        return view('child.notifications.index', compact('notifications'));
    }

    public function destroy($id)
    {
        Auth::user()->notifications()->where('id', $id)->delete();
        return back()->with('success', 'Notification removed.');
    }
}
