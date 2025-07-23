<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Contribution;
use App\Models\Penalty;
use App\Models\Investment;
use App\Models\Vote;
use App\Models\Payment;
use App\Models\AdminLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Inertia\Inertia;
use Illuminate\Validation\ValidationException;

class VoteController extends Controller
{
    public function store(Request $request)
    {
        $validated = $request->validate([
            'user_id' => 'required|uuid|exists:users,id',
            'investment_id' => 'required|uuid|exists:investments,id',
            'vote_value' => 'required|in:yes,no'
        ]);

        $vote = Vote::create($validated);
        return Inertia::render('Votes/Show', ['vote' => $vote]);
    }
}
