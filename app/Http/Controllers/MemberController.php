<?php

namespace App\Http\Controllers;

use App\Models\Group;
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
        $per_page = $request->query('per_page') ?? 10;

        $members = Member::with('groups')->latest()->paginate($per_page);

        $groups = Group::withCount('members')->paginate($per_page);

        $all_groups = Group::all();

        return Inertia::render('Members/Index', [
            'members' => $members,
            'groups' => $groups,
            'all_groups' => $all_groups,
        ]);
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
