<?php

namespace App\Http\Controllers;

use App\Models\Group;
use App\Models\GroupMember;
use Illuminate\Http\Request;
use Inertia\Inertia;

class GroupController extends Controller
{
    /**
     * Groups
     *
     **/
    public function index(Request $request)
    {
        return redirect()->route('members.index');
    }

    /**
     * Add Group
     *
     **/
    public function store(Request $request)
    {
        $request->validate([
            'name' => ['required']
        ]);

        $group = Group::create([
            'name' => $request->name,
            'tags' => $request->tags
        ]);

        return redirect()->route('groups.index');
    }

    public function addMemberToGroup(Request $request)
    {
        $validated = $request->validate([
            'member_id' => ['required'],
            'group_id' => ['required']
        ]);

        GroupMember::create($validated);

        return redirect()->route('members.index');
    }
}
