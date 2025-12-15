<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\Product;
use Illuminate\Support\Facades\Auth;

class CartController extends Controller
{
    public function index()
    {
        $cart = Cart::where('user_id', Auth::id())->with('product')->get();
        return view('user.cart.index', compact('cart'));
    }

    public function add($productId)
    {
        $product = Product::findOrFail($productId);

        // jumlah di cart user saat ini
        $cartItem = Cart::where('user_id', Auth::id())
            ->where('product_id', $productId)
            ->first();

        $currentQty = $cartItem ? $cartItem->quantity : 0;

        if ($currentQty + 1 > $product->stock) {
            return back()->with('error', 'Stock not enough.');
        }

        Cart::updateOrCreate(
            [
                'user_id' => Auth::id(),
                'product_id' => $productId,
            ],
            [
                'quantity' => $currentQty + 1
            ]
        );

        return back()->with('success', 'The product added to cart.');
    }

    public function remove($id)
    {
        Cart::where('id', $id)->where('user_id', Auth::id())->delete();
        return back()->with('success', 'Removed from cart.');
    }
}