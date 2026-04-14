<?php

namespace App\Http\Controllers\vendor;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Subcategory;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class CategoryController extends Controller
{
    public function index()
    {
        $categories = Category::withCount(['subcategories', 'products'])
            ->with('subcategories')
            ->latest()
            ->get();

        return view('vendor.categories.index', compact('categories'));
    }

    public function storeCategory(Request $request)
    {
        $data = $request->validate([
            'name'   => 'required|string|max:100|unique:categories,name',
            'status' => 'nullable|boolean',
        ]);

        Category::create([
            'name'   => $data['name'],
            'slug'   => Str::slug($data['name']),
            'status' => $request->boolean('status', true),
        ]);

        return redirect()->route('vendor.categories')->with('success', 'Category created successfully.');
    }

    public function updateCategory(Request $request, Category $category)
    {
        $data = $request->validate([
            'name'   => 'required|string|max:100|unique:categories,name,' . $category->id,
            'status' => 'nullable|boolean',
        ]);

        $category->update([
            'name'   => $data['name'],
            'slug'   => Str::slug($data['name']),
            'status' => $request->boolean('status', $category->status),
        ]);

        return redirect()->route('vendor.categories')->with('success', 'Category updated successfully.');
    }

    public function destroyCategory(Category $category)
    {
        $category->delete();
        return redirect()->route('vendor.categories')->with('success', 'Category deleted successfully.');
    }

    public function toggleCategoryStatus(Category $category)
    {
        $category->update(['status' => !$category->status]);
        return response()->json(['success' => true, 'status' => (bool) $category->status]);
    }

    public function storeSubcategory(Request $request)
    {
        $data = $request->validate([
            'category_id' => 'required|exists:categories,id',
            'name'        => 'required|string|max:100',
            'status'      => 'nullable|boolean',
        ]);

        Subcategory::create([
            'category_id' => $data['category_id'],
            'name'        => $data['name'],
            'slug'        => Str::slug($data['name']),
            'status'      => $request->boolean('status', true),
        ]);

        return redirect()->route('vendor.categories')->with('success', 'Subcategory created successfully.');
    }

    public function updateSubcategory(Request $request, Subcategory $subcategory)
    {
        $data = $request->validate([
            'category_id' => 'required|exists:categories,id',
            'name'        => 'required|string|max:100',
            'status'      => 'nullable|boolean',
        ]);

        $subcategory->update([
            'category_id' => $data['category_id'],
            'name'        => $data['name'],
            'slug'        => Str::slug($data['name']),
            'status'      => $request->boolean('status', $subcategory->status),
        ]);

        return redirect()->route('vendor.categories')->with('success', 'Subcategory updated successfully.');
    }

    public function destroySubcategory(Subcategory $subcategory)
    {
        $subcategory->delete();
        return redirect()->route('vendor.categories')->with('success', 'Subcategory deleted successfully.');
    }
}
