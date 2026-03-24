<?php

namespace App\Http\Controllers\vendor;

use App\Http\Controllers\Controller;
use App\Http\Requests\UpdateReq;
use App\Models\Category;
use App\Models\Product;
use App\Models\Subcategory;
use Illuminate\Http\Request;
use App\Http\Requests\ProductReq;
use Illuminate\Support\Facades\Log;
class ProductController extends Controller
{
    public function index()
    {
        $products = Product::with(['category', 'subcategory'])->latest()->get();
        $categories = Category::where('status', true)->with('subcategories')->get();
        return view('vendor.product.create', compact('products', 'categories'));
    }

    public function create()
    {

    }

    public function store(ProductReq $req)
    {
        $data = $req->validated();

        $product = new Product();
        $product->name = $data['name'];
        $product->description = $data['description'];
        $product->full_description = $data['full_description'] ?? null;
        $product->sku = $data['sku'] ?? null;
        $product->price = $data['price'];
        $product->sale_price = $data['sale_price'] ?? null;
        $product->stock = $data['stock'];
        $product->category_id = $data['category_id'];
        $product->subcategory_id = $data['subcategory_id'];
        $product->brand = $data['brand'] ?? null;
        $saved = $product->save();

        if ($saved && $req->hasFile('image')) {
            $product->addMediaFromRequest('image')->toMediaCollection('product_image');
        }

        if ($saved) {
            return redirect()->route('vendor.dashboard')->with('success', 'Product Added Successfully');
        } else {
            return redirect()->route('vendor.product')->with('error', 'Failed to Add Product');
        }

    }

    public function show()
    {
        $products = Product::with(['category', 'subcategory'])->latest()->get();
        $categories = Category::where('status', true)->with('subcategories')->get();
        return view('vendor.product.index', compact('products', 'categories'));
    }

    public function edit($id)
    {
        // Code to show form for editing a product
    }

    public function update(UpdateReq $req, $id)
    {
        $product = Product::findOrFail($id);
        Log::info('Incoming Update Request Data:', $req->all());

        $data = $req->validated();

        $product->name = $data['name'];
        $product->description = $data['description'];
        $product->full_description = $data['full_description'] ?? null;
        $product->sku = $data['sku'] ?? null;
        $product->price = $data['price'];
        $product->sale_price = $data['sale_price'] ?? null;
        $product->stock = $data['stock'];
        $product->category = $data['category_id'];
        $product->subcategory = $data['subcategory_id'];
        $product->brand = $data['brand'] ?? null;



        if ($product->save()) {

            if ($req->hasFile('image')) {
                $product->clearMediaCollection('product_image');
                $product->addMediaFromRequest('image')->toMediaCollection('product_image');
            }

            return redirect()->route('vendor.product.show')->with('success', 'Product Updated Successfully');
        } else {
            return back()->with('error', 'Failed to Update Product');
        }
    }


    public function destroy($id)
    {
        $product = Product::find($id);

        if ($product) {
            $product->delete();
            return redirect()->route('vendor.product.show')->with('success', 'Product Deleted Successfully');
        } else {
            return redirect()->route('vendor.product.show')->with('error', 'Product Not Found');
        }
    }
    public function getSubcategories($categoryId)
    {
        $subcategories = Subcategory::where('category_id', $categoryId)
            ->where('status', true)
            ->get();

        return response()->json([
            'status' => true,
            'subcategories' => $subcategories
        ]);
    }

}
