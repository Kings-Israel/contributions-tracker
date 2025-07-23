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

class UserController extends Controller
{
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|min:6',
            'role' => 'in:user,admin'
        ]);

        $user = User::create([
            'id' => Str::uuid(),
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
            'role' => $validated['role'] ?? 'user',
        ]);

        return Inertia::render('Users/Show', ['user' => $user]);
    }

    public function show($id)
    {
        $user = User::findOrFail($id);
        return Inertia::render('Users/Show', ['user' => $user]);
    }
}
