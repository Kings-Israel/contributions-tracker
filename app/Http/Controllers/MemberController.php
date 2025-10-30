<?php

namespace App\Http\Controllers;

use App\Models\Member;
use Illuminate\Http\Request;
use Inertia\Inertia;

class MemberController extends Controller
{
    /**
     * Get members
     *
     **/
    public function index(Request $request)
    {
        $per_page = $request->query('per_page') ?? 50;

        $members = Member::latest()->paginate($per_page);

        return Inertia::render('Members/Index', ['members' => $members]);
    }

    /**
     * Create a Member
     *
     * Add a new church member
     *
     **/
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => ['required'],
            'email' => ['nullable', 'string', 'max:255'],
            'phone_number' => ['nullable', 'string'],
            'age' => ['required', 'integer'],
            'gender' => ['required', 'in:male,female']
        ]);

        Member::create($validated);

        return redirect()->route('members.index');
    }
}
