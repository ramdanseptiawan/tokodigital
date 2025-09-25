<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\CartItem;
use Illuminate\Support\Facades\Session;

class CartController extends Controller
{
    public function index()
    {
        $sessionId = Session::getId();
        $cartItems = CartItem::with('product')
            ->where('session_id', $sessionId)
            ->get();
            
        $total = $cartItems->sum('total');
        
        return view('cart.index', compact('cartItems', 'total'));
    }

    public function add(Request $request, Product $product)
    {
        $request->validate([
            'quantity' => 'integer|min:1|max:10'
        ]);

        $sessionId = Session::getId();
        $quantity = $request->input('quantity', 1);

        $cartItem = CartItem::where('session_id', $sessionId)
            ->where('product_id', $product->id)
            ->first();

        if ($cartItem) {
            $cartItem->quantity += $quantity;
            $cartItem->save();
        } else {
            CartItem::create([
                'session_id' => $sessionId,
                'product_id' => $product->id,
                'quantity' => $quantity,
                'price' => $product->price,
            ]);
        }

        // Return JSON response for AJAX requests
        if ($request->wantsJson() || $request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'Produk berhasil ditambahkan ke keranjang!',
                'count' => $this->getCartCount()
            ]);
        }

        return redirect()->back()->with('success', 'Produk berhasil ditambahkan ke keranjang!');
    }

    public function update(Request $request, CartItem $cartItem)
    {
        $request->validate([
            'quantity' => 'required|integer|min:1|max:10'
        ]);

        // Pastikan cart item milik session ini
        if ($cartItem->session_id !== Session::getId()) {
            abort(403);
        }

        $cartItem->update([
            'quantity' => $request->quantity
        ]);

        return redirect()->route('cart.index')->with('success', 'Keranjang berhasil diupdate!');
    }

    public function remove(CartItem $cartItem)
    {
        // Pastikan cart item milik session ini
        if ($cartItem->session_id !== Session::getId()) {
            abort(403);
        }

        $cartItem->delete();

        return redirect()->route('cart.index')->with('success', 'Item berhasil dihapus dari keranjang!');
    }

    public function clear()
    {
        $sessionId = Session::getId();
        CartItem::where('session_id', $sessionId)->delete();

        return redirect()->route('cart.index')->with('success', 'Keranjang berhasil dikosongkan!');
    }

    public function count()
    {
        return response()->json([
            'count' => $this->getCartCount()
        ]);
    }

    private function getCartCount()
    {
        $sessionId = Session::getId();
        return CartItem::where('session_id', $sessionId)->sum('quantity');
    }
}
