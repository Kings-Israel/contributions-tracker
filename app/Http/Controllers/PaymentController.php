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

class PaymentController extends Controller
{
    public function index(Request $request)
    {
        $per_page = $request->query('per_page') ?? 50;

        $payments = Payment::paginate($per_page);

        return Inertia::render('Payments/Index', ['payments' => $payments]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'payment_type' => 'required|string|max:255',
            'reference_id' => 'required|uuid',
            'amount' => 'required|numeric|min:0',
            'month' => 'required|date'
        ]);

        $payment = Payment::create($validated);
        return Inertia::render('Payments/Show', ['payment' => $payment]);
    }
}
