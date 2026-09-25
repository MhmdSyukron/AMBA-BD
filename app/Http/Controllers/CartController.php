<?php

namespace App\Http\Controllers;

use App\Models\CartItem;
use Illuminate\Http\Request;

class CartController extends Controller
{
    private function getSessionId(Request $request): string
    {
        return $request->input('session_id') ?? session()->getId();
    }

    public function index(Request $request)
    {
        $sessionId = $this->getSessionId($request);
        $cartItems = CartItem::with('product')->where('session_id', $sessionId)->get();

        $subtotal = $cartItems->sum(fn ($item) => $item->subtotal);
        $cartCount = $cartItems->sum('quantity');

        if ($request->wantsJson()) {
            return response()->json([
                'items' => $cartItems->map(function ($item) {
                    return [
                        'id' => $item->id,
                        'product_id' => $item->product_id,
                        'name' => $item->product->name,
                        'image_url' => $item->product->image_url,
                        'price' => (float) $item->product->price,
                        'formatted_price' => $item->product->formatted_price,
                        'quantity' => $item->quantity,
                        'subtotal' => $item->subtotal,
                        'formatted_subtotal' => $item->formatted_subtotal,
                        'store_name' => $item->product->store_name,
                    ];
                }),
                'cart_count' => $cartCount,
                'subtotal' => $subtotal,
                'formatted_subtotal' => 'Rp '.number_format($subtotal, 0, ',', '.'),
            ]);
        }

        return view('cart.index', compact('cartItems', 'subtotal', 'cartCount'));
    }

    public function add(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'quantity' => 'integer|min:1',
        ]);

        $sessionId = $this->getSessionId($request);
        $productId = $request->product_id;
        $qty = $request->quantity ?? 1;

        $cartItem = CartItem::where('session_id', $sessionId)
            ->where('product_id', $productId)
            ->first();

        if ($cartItem) {
            $cartItem->quantity += $qty;
            $cartItem->save();
        } else {
            $cartItem = CartItem::create([
                'session_id' => $sessionId,
                'product_id' => $productId,
                'quantity' => $qty,
            ]);
        }

        $totalCount = CartItem::where('session_id', $sessionId)->sum('quantity');

        if ($request->wantsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Produk berhasil ditambahkan ke keranjang!',
                'cart_count' => $totalCount,
                'session_id' => $sessionId,
            ]);
        }

        return redirect()->back()->with('success', 'Produk berhasil ditambahkan ke keranjang!');
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'quantity' => 'required|integer|min:1',
        ]);

        $sessionId = $this->getSessionId($request);
        $cartItem = CartItem::where('session_id', $sessionId)->where('id', $id)->firstOrFail();
        $cartItem->quantity = $request->quantity;
        $cartItem->save();

        if ($request->wantsJson()) {
            return $this->index($request);
        }

        return redirect()->back()->with('success', 'Keranjang diperbarui!');
    }

    public function remove(Request $request, $id)
    {
        $sessionId = $this->getSessionId($request);
        $cartItem = CartItem::where('session_id', $sessionId)->where('id', $id)->firstOrFail();
        $cartItem->delete();

        if ($request->wantsJson()) {
            return $this->index($request);
        }

        return redirect()->back()->with('success', 'Produk dihapus dari keranjang!');
    }

    public function clear(Request $request)
    {
        $sessionId = $this->getSessionId($request);
        CartItem::where('session_id', $sessionId)->delete();

        if ($request->wantsJson()) {
            return response()->json([
                'success' => true,
                'cart_count' => 0,
                'formatted_subtotal' => 'Rp 0',
            ]);
        }

        return redirect()->back()->with('success', 'Keranjang dibersihkan!');
    }
}
