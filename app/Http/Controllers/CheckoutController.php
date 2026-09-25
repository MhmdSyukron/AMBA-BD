<?php

namespace App\Http\Controllers;

use App\Models\CartItem;
use App\Models\Order;
use App\Models\OrderItem;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class CheckoutController extends Controller
{
    private function getSessionId(Request $request): string
    {
        return $request->input('session_id') ?? session()->getId();
    }

    public function index(Request $request)
    {
        $sessionId = $this->getSessionId($request);
        $cartItems = CartItem::with('product')->where('session_id', $sessionId)->get();

        if ($cartItems->isEmpty()) {
            return redirect()->route('home')->with('error', 'Keranjang Anda masih kosong!');
        }

        $subtotal = $cartItems->sum(fn ($item) => $item->subtotal);
        $shippingCost = 15000;
        $discountAmount = 15000;
        $grandTotal = max(0, $subtotal + $shippingCost - $discountAmount);

        return view('checkout.index', compact('cartItems', 'subtotal', 'shippingCost', 'discountAmount', 'grandTotal'));
    }

    public function process(Request $request)
    {
        $request->validate([
            'customer_name' => 'required|string|max:255',
            'customer_email' => 'required|email|max:255',
            'customer_phone' => 'required|string|max:20',
            'shipping_address' => 'required|string',
            'courier' => 'required|string',
            'payment_method' => 'required|string',
        ]);

        $sessionId = $this->getSessionId($request);
        $cartItems = CartItem::with('product')->where('session_id', $sessionId)->get();

        // If specific session cart is empty, fallback to most recent cart items if available
        if ($cartItems->isEmpty()) {
            $cartItems = CartItem::with('product')->latest()->get();
        }

        if ($cartItems->isEmpty()) {
            if ($request->wantsJson()) {
                return response()->json(['success' => false, 'message' => 'Keranjang kosong!'], 400);
            }

            return redirect()->route('home')->with('error', 'Keranjang Anda kosong!');
        }

        $subtotal = $cartItems->sum(fn ($item) => $item->subtotal);
        $shippingCost = $request->courier === 'GoSend Instant' ? 25000 : 15000;
        $discountAmount = 15000;
        $totalAmount = max(0, $subtotal + $shippingCost - $discountAmount);

        $invoiceNumber = 'INV/'.date('Ymd').'/TKP/'.strtoupper(Str::random(6));

        $order = Order::create([
            'invoice_number' => $invoiceNumber,
            'customer_name' => $request->customer_name,
            'customer_email' => $request->customer_email,
            'customer_phone' => $request->customer_phone,
            'shipping_address' => $request->shipping_address,
            'courier' => $request->courier,
            'payment_method' => $request->payment_method,
            'subtotal' => $subtotal,
            'shipping_cost' => $shippingCost,
            'discount_amount' => $discountAmount,
            'total_amount' => $totalAmount,
            'status' => 'PAID',
        ]);

        foreach ($cartItems as $item) {
            OrderItem::create([
                'order_id' => $order->id,
                'product_id' => $item->product_id,
                'quantity' => $item->quantity,
                'price' => $item->product ? $item->product->price : 0,
                'subtotal' => $item->subtotal,
            ]);

            if ($item->product) {
                $item->product->decrement('stock', $item->quantity);
                $item->product->increment('sold_count', $item->quantity);
            }
        }

        // Clear cart
        CartItem::where('session_id', $sessionId)->delete();

        if ($request->wantsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Transaksi berhasil dibuat!',
                'invoice_number' => $order->invoice_number,
                'order_id' => $order->id,
                'redirect_url' => route('checkout.success', $order->id),
            ]);
        }

        return redirect()->route('checkout.success', $order->id);
    }

    public function success($id)
    {
        $order = Order::with('items.product')->findOrFail($id);

        return view('checkout.success', compact('order'));
    }
}
