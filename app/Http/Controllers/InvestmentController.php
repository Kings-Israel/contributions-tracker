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

class InvestmentController extends Controller
{
    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'type' => 'required|in:active,potential,idea',
            'created_by' => 'required|uuid|exists:users,id'
        ]);

        $investment = Investment::create($validated);
        return Inertia::render('Investments/Show', ['investment' => $investment]);
    }
}
