<?php

namespace App\Http\Controllers;

use App\Models\Purchase;
use App\Models\PurchaseItem;
use App\Models\Supplier;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PurchaseController extends Controller
{
    public function index(Request $request)
    {
        $purchases = Purchase::where('user_id', auth()->id())
            ->with(['supplier', 'purchaseItems'])
            ->when($request->date_from, function ($q) use ($request) {
                $q->whereDate('purchase_date', '>=', $request->date_from);
            })
            ->when($request->date_to, function ($q) use ($request) {
                $q->whereDate('purchase_date', '<=', $request->date_to);
            })
            ->latest()
            ->paginate(15);

        return view('purchases.index', compact('purchases'));
    }

    public function create()
    {
        $suppliers = Supplier::where('user_id', auth()->id())->pluck('name', 'id');
        $products = Product::where('user_id', auth()->id())->where('status', true)->get();
        return view('purchases.create', compact('suppliers', 'products'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'supplier_id' => 'nullable|exists:suppliers,id',
            'purchase_date' => 'required|date',
            'paid_amount' => 'nullable|numeric|min:0',
            'notes' => 'nullable|string|max:1000',
            'items' => 'required|array|min:1',
            'items.*.product_id' => 'required|exists:products,id',
            'items.*.quantity' => 'required|integer|min:1',
            'items.*.unit_cost' => 'required|numeric|min:0',
        ]);

        try {
            DB::beginTransaction();

            $userId = auth()->id();
            $totalAmount = 0;

            // Calculate total
            foreach ($validated['items'] as $item) {
                $totalAmount += $item['quantity'] * $item['unit_cost'];
            }

            $paidAmount = $validated['paid_amount'] ?? 0;
            $dueAmount = $totalAmount - $paidAmount;

            $paymentStatus = 'unpaid';
            if ($paidAmount >= $totalAmount) {
                $paymentStatus = 'paid';
            } elseif ($paidAmount > 0) {
                $paymentStatus = 'partial';
            }

            $purchase = Purchase::create([
                'user_id' => $userId,
                'supplier_id' => $validated['supplier_id'],
                'purchase_date' => $validated['purchase_date'],
                'total_amount' => $totalAmount,
                'paid_amount' => $paidAmount,
                'due_amount' => $dueAmount,
                'payment_status' => $paymentStatus,
                'notes' => $validated['notes'],
            ]);

            // Create purchase items and update stock
            foreach ($validated['items'] as $item) {
                $totalCost = $item['quantity'] * $item['unit_cost'];

                PurchaseItem::create([
                    'user_id' => $userId,
                    'purchase_id' => $purchase->id,
                    'product_id' => $item['product_id'],
                    'quantity' => $item['quantity'],
                    'unit_cost' => $item['unit_cost'],
                    'total_cost' => $totalCost,
                ]);

                // Increase stock
                Product::where('id', $item['product_id'])
                    ->where('user_id', $userId)
                    ->increment('stock_quantity', $item['quantity']);
            }

            // Update supplier due
            if ($validated['supplier_id'] && $dueAmount > 0) {
                Supplier::where('id', $validated['supplier_id'])
                    ->where('user_id', $userId)
                    ->increment('previous_due', $dueAmount);
            }

            DB::commit();

            return redirect()->route('purchases.index')->with('success', 'Purchase recorded successfully. Stock updated.');

        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Failed to record purchase: ' . $e->getMessage());
        }
    }

    public function show(Purchase $purchase)
    {
        if ($purchase->user_id !== auth()->id()) {
            abort(403);
        }

        $purchase->load(['supplier', 'purchaseItems.product']);
        return view('purchases.show', compact('purchase'));
    }

    public function destroy(Purchase $purchase)
    {
        if ($purchase->user_id !== auth()->id()) {
            abort(403);
        }

        try {
            DB::beginTransaction();

            // Reverse stock
            foreach ($purchase->purchaseItems as $item) {
                Product::where('id', $item->product_id)
                    ->where('user_id', auth()->id())
                    ->decrement('stock_quantity', $item->quantity);
            }

            $purchase->delete();
            DB::commit();

            return redirect()->route('purchases.index')->with('success', 'Purchase deleted successfully.');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Failed to delete purchase.');
        }
    }
}
