<?php

namespace App\Http\Controllers\customer;

use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Http\Request;

class CartController extends Controller
{
    public function index()
    {
        $cart = session()->get('cart', []);
        $subtotal = 0;
        $shipping = 0;

        foreach ($cart as $item) {
            $price = $item['sale_price'] ?? $item['price'];
            $subtotal += $price * $item['quantity'];
            $shipping += $item['shipping_charge'] ?? 0;
        }
        $grand_total = $subtotal + $shipping;

        return view('customer.cart', compact('cart', 'subtotal', 'shipping', 'grand_total'));
    }

    public function addToCart(Request $req, $id)
    {
        $product = Product::findOrFail($id);
        $cart = session()->get('cart',[]);
        
        if (isset($cart[$id])) {
            $cart[$id]['$quantity']++;
        } else {
            $cart[$id] = [
                'id' => $product->id,
                'name' => $product->name,
                'description' => $product->description,
                'price' => $product->price,
                'sale_price' => $product->sale_price,
                'quantity' => 1,
                'image'=> $product->getFirstMediaUrl('product_image'),
                'shipping_charge' => $product->shipping_charge ?? 0,
            ];
        } 

        session()->put('cart', $cart);

        return response()->json(['status'=>true, 'message'=>'Product added to the cart successfully.',
        'cart_count' => count($cart)]);
    }

     public function updateQuantity(Request $request, $id)
    {
        $request->validate([
            'quantity' => ['required', 'integer', 'min:1']
        ]);

        $cart = session()->get('cart', []);

        if (!isset($cart[$id])) {
            return response()->json([
                'status' => false,
                'message' => 'Product not found in cart.'
            ], 404);
        }

        $cart[$id]['quantity'] = $request->quantity;

        session()->put('cart', $cart);

        $price = $cart[$id]['sale_price'] ?? $cart[$id]['price'];
        $itemTotal = $price * $cart[$id]['quantity'];

        $subtotal = 0;
        $shipping = 0;

        foreach ($cart as $item) {
            $itemPrice = $item['sale_price'] ?? $item['price'];
            $subtotal += $itemPrice * $item['quantity'];
            $shipping += $item['shipping_charge'] ?? 0;
        }

        $grandTotal = $subtotal + $shipping;

        return response()->json([
            'status' => true,
            'message' => 'Quantity updated successfully.',
            'item_total' => number_format($itemTotal, 2),
            'subtotal' => number_format($subtotal, 2),
            'shipping' => number_format($shipping, 2),
            'grand_total' => number_format($grandTotal, 2),
            'quantity' => $cart[$id]['quantity'],
        ]);
    }

    public function removeItem($id)
    {
        $cart = session()->get('cart', []);

        if (!isset($cart[$id])) {
            return response()->json([
                'status' => false,
                'message' => 'Product not found in cart.'
            ], 404);
        }

        unset($cart[$id]);

        session()->put('cart', $cart);

        $subtotal = 0;
        $shipping = 0;

        foreach ($cart as $item) {
            $price = $item['sale_price'] ?? $item['price'];
            $subtotal += $price * $item['quantity'];
            $shipping += $item['shipping_charge'] ?? 0;
        }

        $grandTotal = $subtotal + $shipping;

        return response()->json([
            'status' => true,
            'message' => 'Item removed successfully.',
            'subtotal' => number_format($subtotal, 2),
            'shipping' => number_format($shipping, 2),
            'grand_total' => number_format($grandTotal, 2),
            'cart_count' => count($cart),
        ]);
    }
}
