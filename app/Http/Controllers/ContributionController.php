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

class ContributionController extends Controller
{
    public function store(Request $request)
    {
        $validated = $request->validate([
            'user_id' => 'required|uuid|exists:users,id',
            'amount' => 'required|numeric|min:0',
            'month' => 'required|date',
            'status' => 'in:paid,pending'
        ]);

        $contribution = Contribution::create($validated);
        return Inertia::render('Contributions/Show', ['contribution' => $contribution]);
    }

    public function userContributions($id)
    {
        $contributions = Contribution::where('user_id', $id)->get();
        return Inertia::render('Contributions/UserContributions', ['contributions' => $contributions]);
    }
}
