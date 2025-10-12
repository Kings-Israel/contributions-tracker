<?php

namespace App\Http\Controllers;

use App\Models\Expense;
use Illuminate\Http\Request;

class ExpenseController extends Controller
{
    public function index()
    {
        $expenses = Expense::with('user', 'approvedBy')->latest()->paginate(20);

        return inertia('Expenses/Index', [
            'expenses' => $expenses,
        ]);
    }

    public function create()
    {
        return inertia('Expenses/Create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'expense_type' => 'required|string|max:500',
            'amount' => 'required|numeric|min:0',
            'payment_reference_id' => 'nullable|string|max:255',
            'month' => 'required|date',
            'attachment' => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:5000',
        ]);

        if ($request->hasFile('attachment')) {
            $path = pathinfo($request->file('attachment')->store('attachments', 'public'), PATHINFO_BASENAME);
            $validated['attachment'] = $path;
        }

        Expense::create(collect($validated)->merge(['user_id' => auth()->id()])->toArray());

        return redirect()->route('expenses')->with('success', 'Expense created successfully.');
    }

    public function update(Request $request)
    {
        $validated = $request->validate([
            'expense_id' => 'required|integer|exists:expenses,id',
            'expense_type' => 'required|string|max:500',
            'amount' => 'required|numeric|min:0',
            'payment_reference_id' => 'nullable|string|max:255',
            'month' => 'required|date',
            'attachment' => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:5000',
        ]);

        if ($request->hasFile('attachment')) {
            $path = pathinfo($request->file('attachment')->store('attachments', 'public'), PATHINFO_BASENAME);
            $validated['attachment'] = $path;
        }

        $expense = Expense::findOrFail($validated['expense_id']);
        $expense->update(collect($validated)->except('expense_id')->toArray());

        return redirect()->route('expenses')->with('success', 'Expense created successfully.');
    }

    public function updateStatus(Request $request)
    {
        $validated = $request->validate([
            'expense_id' => 'required|integer|exists:expenses,id',
            'status' => 'required|in:approve,reject',
        ]);

        $expense = Expense::findOrFail($validated['expense_id']);

        if ($validated['status'] === 'approve') {
            $expense->status = 'approved';
            $expense->approved_by = auth()->id();
        } elseif ($validated['status'] === 'reject') {
            $expense->status = 'rejected';
            $expense->approved_by = null;
        }

        $expense->save();

        return redirect()->route('expenses')->with('success', 'Expense status updated successfully.');
    }

    public function downloadTemplate()
    {
        $file_path = public_path('expenses_template.xlsx');

        if (file_exists($file_path)) {
            return response()->download($file_path, 'expense_template.xlsx', [
                'Content-Type' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
            ]);
        }

        return redirect()->route('expenses')->with('error', 'Template file not found.');
    }

    public function upload(Request $request)
    {
        $request->validate([
            'expenses_file' => 'required|file|mimes:xlsx|max:10000',
        ]);

        // Process the uploaded Excel file
        \Maatwebsite\Excel\Facades\Excel::import(new \App\Imports\Expenses, $request->file('expenses_file'));

        return redirect()->route('expenses')->with('success', 'Expenses uploaded successfully.');
    }
}
