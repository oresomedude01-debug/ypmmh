<?php

namespace App\Http\Controllers\Child;

use App\Http\Controllers\Controller;
use App\Models\Event;
use Illuminate\Http\Request;
use Carbon\Carbon;

class ChildEventController extends Controller
{
    public function index()
    {
        $events = Event::where('start_time', '>=', now()->startOfDay())
            ->orderBy('start_time', 'asc')
            ->get();

        return view('child.events.index', compact('events'));
    }
}
