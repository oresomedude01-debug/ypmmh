<?php

namespace App\Http\Controllers\Mentor;

use App\Http\Controllers\Controller;
use App\Models\Event;
use Illuminate\Http\Request;

class EventController extends Controller
{
    public function index()
    {
        $events = Event::orderBy('start_time', 'asc')->paginate(10);
        return view('mentor.events.index', compact('events'));
    }

    public function show(Event $event)
    {
        return view('mentor.events.show', compact('event'));
    }
}
