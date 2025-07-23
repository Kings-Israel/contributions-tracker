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

class AdminLogController extends Controller
{
    public function store(Request $request)
    {
        $validated = $request->validate([
            'admin_id' => 'required|uuid|exists:users,id',
            'action_type' => 'required|string',
            'details' => 'required|string'
        ]);

        $log = AdminLog::create($validated);
        return Inertia::render('AdminLogs/Show', ['log' => $log]);
    }
}
