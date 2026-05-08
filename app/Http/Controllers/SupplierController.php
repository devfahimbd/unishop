<?php

namespace App\Http\Controllers;

use App\Models\Supplier;
use Illuminate\Http\Request;

class SupplierController extends Controller
{
    public function index(Request $request)
    {
        $suppliers = Supplier::where('user_id', auth()->id())
            ->when($request->search, function ($q) use ($request) {
                $q->search($request->search);
            })
            ->latest()
            ->paginate(15);

        return view('suppliers.index', compact('suppliers'));
    }

    public function create()
    {
        return view('suppliers.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'nullable|email|max:255',
            'phone' => 'nullable|string|max:20',
            'address' => 'nullable|string|max:500',
            'company_name' => 'nullable|string|max:255',
            'previous_due' => 'nullable|numeric|min:0',
        ]);

        $validated['user_id'] = auth()->id();
        $validated['previous_due'] = $validated['previous_due'] ?? 0;
        Supplier::create($validated);

        return redirect()->route('suppliers.index')->with('success', 'Supplier added successfully.');
    }

    public function edit(Supplier $supplier)
    {
        $this->authorizeSupplier($supplier);
        return view('suppliers.edit', compact('supplier'));
    }

    public function update(Request $request, Supplier $supplier)
    {
        $this->authorizeSupplier($supplier);

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'nullable|email|max:255',
            'phone' => 'nullable|string|max:20',
            'address' => 'nullable|string|max:500',
            'company_name' => 'nullable|string|max:255',
            'previous_due' => 'nullable|numeric|min:0',
        ]);

        $validated['previous_due'] = $validated['previous_due'] ?? 0;
        $supplier->update($validated);

        return redirect()->route('suppliers.index')->with('success', 'Supplier updated successfully.');
    }

    public function destroy(Supplier $supplier)
    {
        $this->authorizeSupplier($supplier);
        $supplier->delete();
        return redirect()->route('suppliers.index')->with('success', 'Supplier deleted successfully.');
    }

    private function authorizeSupplier(Supplier $supplier)
    {
        if ($supplier->user_id !== auth()->id()) {
            abort(403, 'Unauthorized action.');
        }
    }
}
