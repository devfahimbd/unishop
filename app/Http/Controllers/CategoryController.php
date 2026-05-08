<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    public function index(Request $request)
    {
        $categories = Category::where('user_id', auth()->id())
            ->when($request->search, function ($q) use ($request) {
                $q->where('name', 'like', "%{$request->search}%");
            })
            ->latest()
            ->paginate(15);

        return view('categories.index', compact('categories'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string|max:500',
            'status' => 'boolean',
        ]);

        $validated['user_id'] = auth()->id();
        $validated['status'] = $request->has('status');
        Category::create($validated);

        return redirect()->back()->with('success', 'Category created successfully.');
    }

    public function update(Request $request, Category $category)
    {
        $this->authorizeCategory($category);

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string|max:500',
            'status' => 'boolean',
        ]);

        $validated['status'] = $request->has('status');
        $category->update($validated);

        return redirect()->back()->with('success', 'Category updated successfully.');
    }

    public function destroy(Category $category)
    {
        $this->authorizeCategory($category);
        $category->delete();
        return redirect()->back()->with('success', 'Category deleted successfully.');
    }

    private function authorizeCategory(Category $category)
    {
        if ($category->user_id !== auth()->id()) {
            abort(403, 'Unauthorized action.');
        }
    }
}
