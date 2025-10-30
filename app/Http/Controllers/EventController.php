<?php

namespace App\Http\Controllers;

use App\Models\Event;
use Illuminate\Http\Request;
use Inertia\Inertia;

class EventController extends Controller
{
    /**
     * Events
     **/
    public function index(Request $request)
    {
        $per_page = $request->query('per_page', 15);

        $events = Event::with('user')->paginate($per_page);

        return Inertia::render('Events/Index', [
            'events' => $events
        ]);
    }

    /**
     * Store Event
     *
     **/
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => ['required'],
            'location' => ['required'],
            'date' => ['required', 'date'],
            'theme' => ['nullable'],
            'description' => ['nullable'],
            'budget' => ['nullable']
        ]);

        $validated['created_by'] = auth()->id();

        Event::create($validated);

        return redirect()->route('events.index');
    }

    /**
     * Update Event
     **/
    public function update(Request $request)
    {
        $validated = $request->validate([
            'event_id' => ['required'],
            'name' => ['required'],
            'location' => ['required'],
            'date' => ['required', 'date'],
            'theme' => ['nullable'],
            'description' => ['nullable'],
            'budget' => ['nullable']
        ]);

        $event = Event::find($request->event_id);

        $event->update(collect($validated)->except('event_id')->toArray());

        return redirect()->route('events.index');
    }
}
