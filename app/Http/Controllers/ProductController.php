<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Category;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function index(Request $request)
    {
        $products = Product::where('user_id', auth()->id())
            ->with('category')
            ->when($request->search, function ($q) use ($request) {
                $q->search($request->search);
            })
            ->when($request->category_id, function ($q) use ($request) {
                $q->where('category_id', $request->category_id);
            })
            ->latest()
            ->paginate(15);

        $categories = Category::where('user_id', auth()->id())->where('status', true)->pluck('name', 'id');

        return view('products.index', compact('products', 'categories'));
    }

    public function create()
    {
        $categories = Category::where('user_id', auth()->id())->where('status', true)->pluck('name', 'id');
        return view('products.create', compact('categories'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'category_id' => 'nullable|exists:categories,id',
            'name' => 'required|string|max:255',
            'barcode' => 'nullable|string|max:100|unique:products,barcode,NULL,id,user_id,' . auth()->id(),
            'purchase_price' => 'required|numeric|min:0',
            'selling_price' => 'required|numeric|min:0',
            'stock_quantity' => 'required|integer|min:0',
            'alert_quantity' => 'required|integer|min:0',
            'unit' => 'required|string|max:50',
            'description' => 'nullable|string|max:1000',
            'status' => 'boolean',
        ]);

        $validated['user_id'] = auth()->id();
        $validated['status'] = $request->has('status');
        Product::create($validated);

        return redirect()->route('products.index')->with('success', 'Product created successfully.');
    }

    public function edit(Product $product)
    {
        $this->authorizeProduct($product);
        $categories = Category::where('user_id', auth()->id())->where('status', true)->pluck('name', 'id');
        return view('products.edit', compact('product', 'categories'));
    }

    public function update(Request $request, Product $product)
    {
        $this->authorizeProduct($product);

        $validated = $request->validate([
            'category_id' => 'nullable|exists:categories,id',
            'name' => 'required|string|max:255',
            'barcode' => 'nullable|string|max:100|unique:products,barcode,' . $product->id . ',id,user_id,' . auth()->id(),
            'purchase_price' => 'required|numeric|min:0',
            'selling_price' => 'required|numeric|min:0',
            'stock_quantity' => 'required|integer|min:0',
            'alert_quantity' => 'required|integer|min:0',
            'unit' => 'required|string|max:50',
            'description' => 'nullable|string|max:1000',
            'status' => 'boolean',
        ]);

        $validated['status'] = $request->has('status');
        $product->update($validated);

        return redirect()->route('products.index')->with('success', 'Product updated successfully.');
    }

    public function destroy(Product $product)
    {
        $this->authorizeProduct($product);
        $product->delete();
        return redirect()->route('products.index')->with('success', 'Product deleted successfully.');
    }

    public function barcodeSearch(Request $request)
    {
        $product = Product::where('user_id', auth()->id())
            ->where('barcode', $request->barcode)
            ->where('status', true)
            ->first();

        if ($product) {
            return response()->json([
                'success' => true,
                'product' => [
                    'id' => $product->id,
                    'name' => $product->name,
                    'barcode' => $product->barcode,
                    'selling_price' => $product->selling_price,
                    'stock_quantity' => $product->stock_quantity,
                ]
            ]);
        }

        return response()->json(['success' => false, 'message' => 'Product not found.']);
    }

    private function authorizeProduct(Product $product)
    {
        if ($product->user_id !== auth()->id()) {
            abort(403, 'Unauthorized action.');
        }
    }
}
