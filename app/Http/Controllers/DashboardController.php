<?php

namespace App\Http\Controllers;

use App\Models\Contribution;
use App\Models\Expense;
use App\Models\Investment;
use App\Models\Payment;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Inertia\Inertia;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        $users = User::count();
        $total_payments = round(Payment::sum('amount'), 2);
        $total_expenses = round(Expense::where('status', 'approved')->sum('amount'), 2);

        $months = [];
        $payments_data = [];
        $expenses_data = [];

        for ($i = 11; $i >= 0; $i--) {
            $month = Carbon::today()
            ->startOfMonth()
            ->subMonth($i);
            array_push($months, $month);
        }

        $payments_data = [];

        foreach ($months as $key => $month) {
            $payments_amount_per_month = 0;
            $expenses_amount_per_month = 0;

            $payments_amount_per_month = Payment::whereBetween('created_at', [
                Carbon::parse($month)->startOfMonth(),
                Carbon::parse($month)->endOfMonth(),
              ])
              ->sum('amount');

            array_push($payments_data, ceil($payments_amount_per_month));

            $expenses_amount_per_month = Expense::where('status', 'approved')
            ->whereBetween('month', [
                Carbon::parse($month)->startOfMonth(),
                Carbon::parse($month)->endOfMonth(),
              ])
              ->sum('amount');

            array_push($expenses_data, ceil($expenses_amount_per_month));
        }

        // Format months
        $months_formatted = [];
        foreach ($months as $key => $month) {
            array_push($months_formatted, Carbon::parse($month)->format('M'));
        }

        return Inertia::render('Dashboard', [
            'users' => $users,
            'total_contributions' => $total_payments,
            'total_investments' => $total_expenses,
            'months' => $months_formatted,
            'payments_graph_data' => $payments_data,
            'expenses_graph_data' => $expenses_data
        ]);
    }
}
