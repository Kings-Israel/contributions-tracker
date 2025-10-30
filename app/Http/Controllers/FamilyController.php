<?php

namespace App\Http\Controllers;

use App\Models\Family;
use App\Models\FamilyMember;
use Illuminate\Http\Request;

class FamilyController extends Controller
{
    public function index()
    {
        return redirect()->route('members.index');
    }

    /**
     * Add Family
     *
     **/
    public function store(Request $request)
    {
        $request->validate([
            'name' => ['required']
        ]);

        Family::create([
            'name' => $request->name,
        ]);

        return redirect()->route('groups.index');
    }

    public function addMemberToFamily(Request $request)
    {
        $validated = $request->validate([
            'member_id' => ['required'],
            'family_id' => ['required']
        ]);

        FamilyMember::firstOrCreate($validated);

        return redirect()->route('members.index');
    }
}
