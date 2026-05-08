<?php

namespace App\Http\Controllers;

use App\Models\Sale;
use App\Models\SaleItem;
use App\Models\Product;
use App\Models\Customer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Barryvdh\DomPDF\Facade\Pdf;

class PosController extends Controller
{
    public function index()
    {
        $products = Product::where('user_id', auth()->id())
            ->where('status', true)
            ->with('category')
            ->get();

        $customers = Customer::where('user_id', auth()->id())->pluck('name', 'id');

        return view('pos.index', compact('products', 'customers'));
    }

    public function addToCart(Request $request)
    {
        $product = Product::where('id', $request->product_id)
            ->where('user_id', auth()->id())
            ->firstOrFail();

        if ($product->stock_quantity < $request->quantity) {
            return response()->json([
                'success' => false,
                'message' => 'Insufficient stock! Available: ' . $product->stock_quantity,
            ]);
        }

        $cart = session()->get('cart', []);
        $cartKey = $request->product_id;

        if (isset($cart[$cartKey])) {
            $newQty = $cart[$cartKey]['quantity'] + $request->quantity;
            if ($newQty > $product->stock_quantity) {
                return response()->json([
                    'success' => false,
                    'message' => 'Insufficient stock! Available: ' . $product->stock_quantity,
                ]);
            }
            $cart[$cartKey]['quantity'] = $newQty;
            $cart[$cartKey]['total'] = $cart[$cartKey]['quantity'] * $cart[$cartKey]['price'];
        } else {
            $cart[$cartKey] = [
                'product_id' => $product->id,
                'name' => $product->name,
                'price' => $product->selling_price,
                'quantity' => $request->quantity,
                'total' => $product->selling_price * $request->quantity,
            ];
        }

        session()->put('cart', $cart);

        return response()->json([
            'success' => true,
            'message' => 'Product added to cart.',
            'cart' => $cart,
            'cart_count' => count($cart),
            'cart_total' => array_sum(array_column($cart, 'total')),
        ]);
    }

    public function updateCart(Request $request)
    {
        if ($request->product_id && $request->quantity) {
            $cart = session()->get('cart', []);

            if (isset($cart[$request->product_id])) {
                $product = Product::where('id', $request->product_id)->where('user_id', auth()->id())->first();
                if ($request->quantity > $product->stock_quantity) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Insufficient stock! Available: ' . $product->stock_quantity,
                    ]);
                }
                $cart[$request->product_id]['quantity'] = $request->quantity;
                $cart[$request->product_id]['total'] = $request->quantity * $cart[$request->product_id]['price'];
                session()->put('cart', $cart);
            }
        }

        $cart = session()->get('cart', []);
        return response()->json([
            'success' => true,
            'cart' => $cart,
            'cart_count' => count($cart),
            'cart_total' => array_sum(array_column($cart, 'total')),
        ]);
    }

    public function removeFromCart(Request $request)
    {
        $cart = session()->get('cart', []);

        if (isset($cart[$request->product_id])) {
            unset($cart[$request->product_id]);
            session()->put('cart', $cart);
        }

        $cart = session()->get('cart', []);
        return response()->json([
            'success' => true,
            'cart' => $cart,
            'cart_count' => count($cart),
            'cart_total' => count($cart) > 0 ? array_sum(array_column($cart, 'total')) : 0,
        ]);
    }

    public function clearCart()
    {
        session()->forget('cart');
        return response()->json(['success' => true, 'message' => 'Cart cleared.']);
    }

    public function checkout(Request $request)
    {
        $cart = session()->get('cart', []);

        if (empty($cart)) {
            return response()->json(['success' => false, 'message' => 'Cart is empty.']);
        }

        $validated = $request->validate([
            'customer_id' => 'nullable|exists:customers,id',
            'discount_amount' => 'nullable|numeric|min:0',
            'discount_type' => 'nullable|in:fixed,percentage',
            'vat_percentage' => 'nullable|numeric|min:0|max:100',
            'payment_method' => 'required|in:cash,bkash,card',
            'paid_amount' => 'required|numeric|min:0',
            'notes' => 'nullable|string|max:1000',
        ]);

        try {
            DB::beginTransaction();

            $userId = auth()->id();
            $subtotal = array_sum(array_column($cart, 'total'));

            // Calculate discount
            $discountAmount = $validated['discount_amount'] ?? 0;
            $discountType = $validated['discount_type'] ?? 'fixed';

            if ($discountType === 'percentage') {
                $discountAmount = ($subtotal * $discountAmount) / 100;
            }

            // Calculate VAT
            $vatPercentage = $validated['vat_percentage'] ?? 0;
            $vatAmount = (($subtotal - $discountAmount) * $vatPercentage) / 100;

            $totalAmount = $subtotal - $discountAmount + $vatAmount;
            $paidAmount = $validated['paid_amount'];
            $dueAmount = $totalAmount - $paidAmount;

            $paymentStatus = 'paid';
            if ($paidAmount < $totalAmount) {
                $paymentStatus = 'unpaid';
            }

            $invoiceNo = Sale::generateInvoiceNo($userId);

            $sale = Sale::create([
                'user_id' => $userId,
                'customer_id' => $validated['customer_id'],
                'invoice_no' => $invoiceNo,
                'sale_date' => now()->format('Y-m-d'),
                'subtotal' => $subtotal,
                'discount_amount' => $discountAmount,
                'discount_type' => $discountType,
                'vat_amount' => $vatAmount,
                'vat_percentage' => $vatPercentage,
                'total_amount' => $totalAmount,
                'paid_amount' => $paidAmount,
                'due_amount' => max(0, $dueAmount),
                'payment_method' => $validated['payment_method'],
                'payment_status' => $paymentStatus,
                'notes' => $validated['notes'],
            ]);

            // Create sale items and decrease stock
            foreach ($cart as $item) {
                SaleItem::create([
                    'user_id' => $userId,
                    'sale_id' => $sale->id,
                    'product_id' => $item['product_id'],
                    'quantity' => $item['quantity'],
                    'unit_price' => $item['price'],
                    'total_price' => $item['total'],
                ]);

                // Decrease stock
                Product::where('id', $item['product_id'])
                    ->where('user_id', $userId)
                    ->decrement('stock_quantity', $item['quantity']);
            }

            // Update customer due
            if ($validated['customer_id'] && $dueAmount > 0) {
                Customer::where('id', $validated['customer_id'])
                    ->where('user_id', $userId)
                    ->increment('previous_due', max(0, $dueAmount));
            }

            session()->forget('cart');
            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Sale completed successfully!',
                'sale_id' => $sale->id,
                'invoice_no' => $invoiceNo,
                'invoice_url' => route('pos.invoice', $sale->id),
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['success' => false, 'message' => 'Checkout failed: ' . $e->getMessage()]);
        }
    }

    public function invoice(Sale $sale)
    {
        if ($sale->user_id !== auth()->id()) {
            abort(403);
        }

        $sale->load(['customer', 'saleItems.product', 'user']);
        $shop = auth()->user();

        if ($sale->payment_status === 'paid' || $sale->payment_status === 'partial') {
            $paymentStatusText = 'Paid';
        } else {
            $paymentStatusText = 'Unpaid';
        }

        return view('invoice.print', compact('sale', 'shop', 'paymentStatusText'));
    }

    public function downloadInvoice(Sale $sale)
    {
        if ($sale->user_id !== auth()->id()) {
            abort(403);
        }

        $sale->load(['customer', 'saleItems.product', 'user']);
        $shop = auth()->user();

        $pdf = Pdf::loadView('invoice.print', compact('sale', 'shop'));
        return $pdf->download('invoice-' . $sale->invoice_no . '.pdf');
    }
}
