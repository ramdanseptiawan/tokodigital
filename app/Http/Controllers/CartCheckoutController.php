<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Str;
use App\Models\Product;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\CartItem;
use App\Services\MidtransService;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use App\Mail\PaymentPending;

class CartCheckoutController extends Controller
{
    public function show()
    {
        $sessionId = Session::getId();
        $cartItems = CartItem::with('product')
            ->where('session_id', $sessionId)
            ->get();
            
        if ($cartItems->isEmpty()) {
            return redirect()->route('cart.index')->with('error', 'Keranjang kosong!');
        }
        
        $total = $cartItems->sum('total');
        
        return view('checkout.cart', compact('cartItems', 'total'));
    }

    public function store(Request $request, MidtransService $mid)
    {
        $data = $request->validate([
            'name'  => 'required|string',
            'email' => 'required|email',
            'phone' => 'nullable|string',
        ]);

        $sessionId = Session::getId();
        $cartItems = CartItem::with('product')
            ->where('session_id', $sessionId)
            ->get();
            
        if ($cartItems->isEmpty()) {
            return redirect()->route('cart.index')->with('error', 'Keranjang kosong!');
        }

        // Server-side validation: Calculate total from database to prevent manipulation
        $calculatedTotal = 0;
        foreach ($cartItems as $cartItem) {
            // Get fresh product price from database
            $product = Product::find($cartItem->product_id);
            if (!$product) {
                return redirect()->route('cart.index')->with('error', 'Produk tidak ditemukan!');
            }
            
            // Calculate total based on current database price, not cart stored price
            $calculatedTotal += $product->price * $cartItem->quantity;
        }
        
        $totalAmount = $calculatedTotal;

        DB::beginTransaction();
        try {
            // Buat order
            $order = Order::create([
                'total_amount'   => $totalAmount,
                'status'         => 'pending',
                'customer_name'  => $data['name'],
                'customer_email' => $data['email'],
                'customer_phone' => $data['phone'] ?? '',
            ]);

            // Buat order items using validated prices from database
            $itemDetails = [];
            foreach ($cartItems as $cartItem) {
                // Get fresh product data from database for security
                $product = Product::find($cartItem->product_id);
                $itemTotal = $product->price * $cartItem->quantity;
                
                OrderItem::create([
                    'order_id' => $order->id,
                    'product_id' => $cartItem->product_id,
                    'product_name' => $product->name,
                    'product_type' => $product->type,
                    'quantity' => $cartItem->quantity,
                    'price' => $product->price, // Use database price, not cart price
                    'total' => $itemTotal,
                ]);

                $itemDetails[] = [
                    'id'       => (string)$cartItem->product_id,
                    'price'    => $product->price, // Use database price, not cart price
                    'quantity' => $cartItem->quantity,
                    'name'     => $product->name,
                ];
            }

            // Charge ke Midtrans
            $resp = $mid->chargeQris([
                'order_id'     => $order->order_id,
                'gross_amount' => $order->total_amount,
                'item_details' => $itemDetails,
                'customer_details' => [
                    'first_name' => $order->customer_name,
                    'email'      => $order->customer_email,
                    'phone'      => $order->customer_phone,
                ],
            ]);

            // Log response untuk debugging
            Log::info('Midtrans QRIS Response', [
                'order_id' => $order->order_id,
                'response' => $resp,
                'actions' => $resp->actions ?? [],
            ]);

            $actions = collect($resp->actions ?? []);
            $qr = $actions->firstWhere('name', 'generate-qr-code-v2')
                ?? $actions->firstWhere('name', 'generate-qr-code');

            $order->update([
                'midtrans_transaction_id' => $resp->transaction_id ?? null,
                'qr_url'    => $qr->url ?? null,
                'qr_string' => $resp->qr_string ?? ($resp->qris ?? null),
            ]);

            // Log final order data
            Log::info('Order updated with QRIS data', [
                'order_id' => $order->order_id,
                'qr_url' => $order->qr_url,
                'qr_string' => $order->qr_string ? 'present' : 'null',
                'transaction_id' => $order->midtrans_transaction_id,
            ]);

            // Hapus cart items setelah berhasil checkout
            CartItem::where('session_id', $sessionId)->delete();

            // Kirim email notifikasi pending payment
            try {
                $orderWithItems = $order->load('orderItems.product');
                Mail::to($order->customer_email)->send(new PaymentPending($orderWithItems));
                Log::info('Email pending payment sent successfully', [
                    'order_id' => $order->order_id,
                    'email' => $order->customer_email
                ]);
            } catch (\Exception $e) {
                Log::error('Failed to send pending payment email', [
                    'order_id' => $order->order_id,
                    'email' => $order->customer_email,
                    'error' => $e->getMessage()
                ]);
            }

            DB::commit();

            return redirect()->route('orders.show', $order);
            
        } catch (\Exception $e) {
            DB::rollback();
            
            return redirect()->back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    // Checkout single product (quick buy)
    public function storeProduct(Request $request, Product $product, MidtransService $mid)
    {
        $data = $request->validate([
            'name'     => 'required|string',
            'email'    => 'required|email',
            'phone'    => 'nullable|string',
            'quantity' => 'integer|min:1|max:10'
        ]);

        $quantity = $data['quantity'] ?? 1;
        $totalAmount = $product->price * $quantity;

        DB::beginTransaction();
        try {
            // Buat order
            $order = Order::create([
                'total_amount'   => $totalAmount,
                'status'         => 'pending',
                'customer_name'  => $data['name'],
                'customer_email' => $data['email'],
                'customer_phone' => $data['phone'] ?? '',
            ]);

            // Buat order item
            OrderItem::create([
                'order_id' => $order->id,
                'product_id' => $product->id,
                'product_name' => $product->name,
                'product_type' => $product->type,
                'quantity' => $quantity,
                'price' => $product->price,
                'total' => $totalAmount,
            ]);

            // Charge ke Midtrans
            $resp = $mid->chargeQris([
                'order_id'     => $order->order_id,
                'gross_amount' => $order->total_amount,
                'item_details' => [[
                    'id'       => (string)$product->id,
                    'price'    => $product->price,
                    'quantity' => $quantity,
                    'name'     => $product->name,
                ]],
                'customer_details' => [
                    'first_name' => $order->customer_name,
                    'email'      => $order->customer_email,
                    'phone'      => $order->customer_phone,
                ],
            ]);

            $actions = collect($resp->actions ?? []);
            $qr = $actions->firstWhere('name', 'generate-qr-code-v2')
                ?? $actions->firstWhere('name', 'generate-qr-code');

            $order->update([
                'midtrans_transaction_id' => $resp->transaction_id ?? null,
                'qr_url'    => $qr->url ?? null,
                'qr_string' => $resp->qr_string ?? ($resp->qris ?? null),
            ]);

            // Kirim email notifikasi pending payment untuk single product
            try {
                $orderWithItems = $order->load('orderItems.product');
                Mail::to($order->customer_email)->send(new PaymentPending($orderWithItems));
                Log::info('Email pending payment sent successfully (single product)', [
                    'order_id' => $order->order_id,
                    'email' => $order->customer_email
                ]);
            } catch (\Exception $e) {
                Log::error('Failed to send pending payment email (single product)', [
                    'order_id' => $order->order_id,
                    'email' => $order->customer_email,
                    'error' => $e->getMessage()
                ]);
            }

            DB::commit();

            return redirect()->route('orders.show', $order);
            
        } catch (\Exception $e) {
            DB::rollback();
            return redirect()->back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }
}