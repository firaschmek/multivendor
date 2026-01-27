<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class AdminCategoryController extends Controller
{
    /**
     * Display all categories
     */
    public function index()
    {
        $categories = Category::with('parent', 'children')
            ->withCount('products')
            ->orderBy('parent_id')
            ->orderBy('name')
            ->get();

        return view('admin.categories.index', compact('categories'));
    }

    /**
     * Show create form
     */
    public function create()
    {
        $parentCategories = Category::whereNull('parent_id')->get();
        return view('admin.categories.create', compact('parentCategories'));
    }

    /**
     * Store new category
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:categories,name',
            'name_ar' => 'nullable|string|max:255',
            'parent_id' => 'nullable|exists:categories,id',
            'description' => 'nullable|string',
            'description_ar' => 'nullable|string',
            'is_active' => 'boolean',
        ]);

        Category::create([
            'name' => $request->name,
            'name_ar' => $request->name_ar,
            'slug' => Str::slug($request->name),
            'parent_id' => $request->parent_id,
            'description' => $request->description,
            'description_ar' => $request->description_ar,
            'is_active' => $request->has('is_active'),
        ]);

        return redirect()
            ->route('admin.categories.index')
            ->with('success', 'Category created successfully!');
    }

    /**
     * Show edit form
     */
    public function edit(Category $category)
    {
        $parentCategories = Category::whereNull('parent_id')
            ->where('id', '!=', $category->id)
            ->get();

        return view('admin.categories.edit', compact('category', 'parentCategories'));
    }

    /**
     * Update category
     */
    public function update(Request $request, Category $category)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:categories,name,' . $category->id,
            'name_ar' => 'nullable|string|max:255',
            'parent_id' => 'nullable|exists:categories,id',
            'description' => 'nullable|string',
            'description_ar' => 'nullable|string',
            'is_active' => 'boolean',
        ]);

        // Prevent circular reference
        if ($request->parent_id == $category->id) {
            return redirect()
                ->back()
                ->withInput()
                ->with('error', 'Category cannot be its own parent.');
        }

        $category->update([
            'name' => $request->name,
            'name_ar' => $request->name_ar,
            'slug' => Str::slug($request->name),
            'parent_id' => $request->parent_id,
            'description' => $request->description,
            'description_ar' => $request->description_ar,
            'is_active' => $request->has('is_active'),
        ]);

        return redirect()
            ->route('admin.categories.index')
            ->with('success', 'Category updated successfully!');
    }

    /**
     * Toggle active status
     */
    public function toggleStatus(Category $category)
    {
        $category->update(['is_active' => !$category->is_active]);

        return redirect()
            ->back()
            ->with('success', 'Category status updated!');
    }

    /**
     * Delete category
     */
    public function destroy(Category $category)
    {
        // Check if has products
        if ($category->products()->count() > 0) {
            return redirect()
                ->back()
                ->with('error', 'Cannot delete category with products. Reassign products first.');
        }

        // Check if has children
        if ($category->children()->count() > 0) {
            return redirect()
                ->back()
                ->with('error', 'Cannot delete category with subcategories. Delete subcategories first.');
        }

        $category->delete();

        return redirect()
            ->route('admin.categories.index')
            ->with('success', 'Category deleted successfully!');
    }
}
