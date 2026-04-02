<?php

namespace App\Http\Controllers\Parent;

use App\Http\Controllers\Controller;
use App\Models\Event;
use Illuminate\Support\Facades\Auth;

class ParentEventController extends Controller
{
    public function index()
    {
        $events = Event::where('start_time', '>=', now()->startOfDay())
            ->orderBy('start_time', 'asc')
            ->get();

        return view('parent.events.index', compact('events'));
    }

    public function show(Event $event)
    {
        return view('parent.events.show', compact('event'));
    }
}
