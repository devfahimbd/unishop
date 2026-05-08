<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use App\Models\Sale;
use Illuminate\Http\Request;

class CustomerController extends Controller
{
    public function index(Request $request)
    {
        $customers = Customer::where('user_id', auth()->id())
            ->when($request->search, function ($q) use ($request) {
                $q->search($request->search);
            })
            ->latest()
            ->paginate(15);

        return view('customers.index', compact('customers'));
    }

    public function create()
    {
        return view('customers.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'nullable|email|max:255',
            'phone' => 'nullable|string|max:20',
            'address' => 'nullable|string|max:500',
            'previous_due' => 'nullable|numeric|min:0',
        ]);

        $validated['user_id'] = auth()->id();
        $validated['previous_due'] = $validated['previous_due'] ?? 0;
        Customer::create($validated);

        return redirect()->route('customers.index')->with('success', 'Customer added successfully.');
    }

    public function edit(Customer $customer)
    {
        $this->authorizeCustomer($customer);
        return view('customers.edit', compact('customer'));
    }

    public function update(Request $request, Customer $customer)
    {
        $this->authorizeCustomer($customer);

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'nullable|email|max:255',
            'phone' => 'nullable|string|max:20',
            'address' => 'nullable|string|max:500',
            'previous_due' => 'nullable|numeric|min:0',
        ]);

        $validated['previous_due'] = $validated['previous_due'] ?? 0;
        $customer->update($validated);

        return redirect()->route('customers.index')->with('success', 'Customer updated successfully.');
    }

    public function destroy(Customer $customer)
    {
        $this->authorizeCustomer($customer);
        $customer->delete();
        return redirect()->route('customers.index')->with('success', 'Customer deleted successfully.');
    }

    public function purchaseHistory(Customer $customer)
    {
        $this->authorizeCustomer($customer);
        $sales = Sale::where('user_id', auth()->id())
            ->where('customer_id', $customer->id)
            ->with('saleItems.product')
            ->latest()
            ->paginate(15);

        return view('customers.purchase-history', compact('customer', 'sales'));
    }

    private function authorizeCustomer(Customer $customer)
    {
        if ($customer->user_id !== auth()->id()) {
            abort(403, 'Unauthorized action.');
        }
    }
}
