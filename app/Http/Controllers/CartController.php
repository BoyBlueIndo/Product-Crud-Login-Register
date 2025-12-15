<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\Product;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

class CartController extends Controller
{
    public function index()
    {
        $cart = Cart::where('user_id', Auth::id())->with('product')->get();
        return view('user.cart.index', compact('cart'));
    }

    public function add(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'quantity'   => 'required|integer|min:1',
        ]);

        $product = Product::findOrFail($request->product_id);
        $qty     = $request->quantity;

        if ($qty > $product->stock) {
            return back()->with('error', 'Quantity exceeds available stock.');
        }

        $cart = Cart::firstOrCreate(
            [
                'user_id'    => auth()->id(),
                'product_id' => $product->id,
            ],
            [
                'quantity' => 0,
            ]
        );

        $cart->quantity += $qty;
        $cart->save();

        return back()->with('success', 'Product added to cart.');
    }

    public function remove($id)
    {
        Cart::where('id', $id)->where('user_id', Auth::id())->delete();
        return back()->with('success', 'Removed from cart.');
    }
}