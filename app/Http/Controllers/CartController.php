<?php

namespace App\Http\Controllers;

use App\Models\CartItem;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class CartController extends Controller
{
    public function index()
    {
        $cartQuery = CartItem::with('product');

        if (Auth::check()) {
            $cartQuery->where('user_id', Auth::id());
        } else {
            $cartQuery->where('session_id', session()->getId());
        }

        $cartItems = $cartQuery->get();

        Log::info("Cart Items: ", $cartItems->toArray()); // Log data to storage/logs/laravel.log

        $total = $cartItems->sum(fn($item) => $item->product->price * $item->quantity);

        return view('cart.index', compact('cartItems', 'total'));
    }


    public function addToCart(Request $request, Product $product)
    {
        $cartItem = CartItem::firstOrNew([
            'user_id' => auth()->id(),
            'session_id' => auth()->check() ? null : session()->getId(),
            'product_id' => $product->id,
        ]);

        // If the item exists, increment the quantity; otherwise, set it to 1
        $cartItem->quantity = ($cartItem->exists ? $cartItem->quantity + 1 : 1);
        $cartItem->save();

        // Get updated cart count
        $cartCount = CartItem::where(function ($query) {
            if (auth()->check()) {
                $query->where('user_id', auth()->id());
            } else {
                $query->where('session_id', session()->getId());
            }
        })->sum('quantity');

        return response()->json([
            'success' => true,
            'message' => "{$product->name} added to cart",
            'cartCount' => $cartCount // Return correct cart count
        ]);
    }


    public function removeFromCart(CartItem $cartItem)
    {
        // Ensure user owns the cart item before deleting
        if (Auth::check()) {
            if ($cartItem->user_id !== Auth::id()) {
                return redirect()->route('cart.index')->with('error', 'Unauthorized action!');
            }
        } else {
            if ($cartItem->session_id !== session()->getId()) {
                return redirect()->route('cart.index')->with('error', 'Unauthorized action!');
            }
        }

        $cartItem->delete();

        return redirect()->route('cart.index')->with('success', 'Product removed from cart!');
    }

    public function getCartCount()
    {
        $cartQuery = CartItem::query();

        if (auth()->check()) {
            $cartQuery->where('user_id', auth()->id());
        } else {
            $cartQuery->where('session_id', session()->getId());
        }

        return response()->json(['cartCount' => $cartQuery->sum('quantity')]);
    }
}
