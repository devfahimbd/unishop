<?php

namespace App\Http\Controllers;

use App\Models\Expense;
use Illuminate\Http\Request;

class ExpenseController extends Controller
{
    public function index(Request $request)
    {
        $expenses = Expense::where('user_id', auth()->id())
            ->when($request->category, function ($q) use ($request) {
                $q->where('category', $request->category);
            })
            ->when($request->date_from, function ($q) use ($request) {
                $q->whereDate('date', '>=', $request->date_from);
            })
            ->when($request->date_to, function ($q) use ($request) {
                $q->whereDate('date', '<=', $request->date_to);
            })
            ->latest()
            ->paginate(15);

        $categories = Expense::where('user_id', auth()->id())
            ->select('category')
            ->distinct()
            ->pluck('category');

        return view('expenses.index', compact('expenses', 'categories'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'category' => 'required|string|max:255',
            'amount' => 'required|numeric|min:0',
            'date' => 'required|date',
            'description' => 'nullable|string|max:1000',
        ]);

        $validated['user_id'] = auth()->id();
        Expense::create($validated);

        return redirect()->back()->with('success', 'Expense added successfully.');
    }

    public function update(Request $request, Expense $expense)
    {
        if ($expense->user_id !== auth()->id()) {
            abort(403);
        }

        $validated = $request->validate([
            'category' => 'required|string|max:255',
            'amount' => 'required|numeric|min:0',
            'date' => 'required|date',
            'description' => 'nullable|string|max:1000',
        ]);

        $expense->update($validated);

        return redirect()->back()->with('success', 'Expense updated successfully.');
    }

    public function destroy(Expense $expense)
    {
        if ($expense->user_id !== auth()->id()) {
            abort(403);
        }

        $expense->delete();
        return redirect()->back()->with('success', 'Expense deleted successfully.');
    }

    public function report(Request $request)
    {
        $userId = auth()->id();
        $dateFrom = $request->date_from ?? now()->startOfMonth()->format('Y-m-d');
        $dateTo = $request->date_to ?? now()->format('Y-m-d');

        $expenses = Expense::where('user_id', $userId)
            ->dateRange($dateFrom, $dateTo)
            ->latest()
            ->get();

        $totalExpenses = $expenses->sum('amount');

        $categoryBreakdown = $expenses->groupBy('category')->map(function ($items) {
            return [
                'total' => $items->sum('amount'),
                'count' => $items->count(),
            ];
        });

        return view('expenses.report', compact('expenses', 'totalExpenses', 'categoryBreakdown', 'dateFrom', 'dateTo'));
    }
}
