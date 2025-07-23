<?php

namespace App\Http\Controllers;

use App\Models\Contribution;
use App\Models\Investment;
use App\Models\User;
use Illuminate\Http\Request;
use Inertia\Inertia;

class DashboardController extends Controller
{
    public function index()
    {
        $users = User::count();
        $total_contributions = Contribution::sum('amount');
        $total_investments = Investment::count();

        return Inertia::render('Dashboard', [
            'users' => $users,
            'total_contributions' => $total_contributions,
            'total_investments' => $total_investments
        ]);
    }
}
