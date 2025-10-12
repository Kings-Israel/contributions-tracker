<?php

namespace App\Http\Controllers;

use App\Models\Contribution;
use App\Models\Expense;
use App\Models\Investment;
use App\Models\Payment;
use App\Models\User;
use Illuminate\Http\Request;
use Inertia\Inertia;

class DashboardController extends Controller
{
    public function index()
    {
        $users = User::count();
        $total_payments = round(Payment::sum('amount'), 2);
        $total_expenses = round(Expense::where('status', 'approved')->sum('amount'), 2);

        return Inertia::render('Dashboard', [
            'users' => $users,
            'total_contributions' => $total_payments,
            'total_investments' => $total_expenses
        ]);
    }
}
