<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\Transaction;
use App\Models\Transaction_item;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Barryvdh\DomPDF\Facade\Pdf;

class TransactionController extends Controller
{
    public function index()
    {
        $transactions = Transaction::where('user_id', Auth::id())
            ->with('items.product')
            ->get();

        return view('user.transactions.index', compact('transactions'));
    }

    public function adminIndex()
    {
        $transactions = Transaction::with(['items.product', 'user'])->get();

        return view('admin.transactions.index', compact('transactions'));
    }

    public function checkout(Request $request)
    {
        $request->validate([
            'payment_method' => 'required|string',
            'cash_paid' => 'nullable|integer|min:0'
        ]);

        $cartItems = Cart::where('user_id', Auth::id())
            ->with('product')
            ->get();

        if ($cartItems->isEmpty()) {
            return back()->with('error', 'Your cart is empty.');
        }

        DB::beginTransaction();

        try {

            // 1️⃣ Cek stok
            foreach ($cartItems as $item) {
                if ($item->product->stock < $item->quantity) {
                    throw new \Exception(
                        'Stock for "' . $item->product->name . '" is not enough.'
                    );
                }
            }

            // 2️⃣ Hitung total harga
            $totalPrice = 0;
            foreach ($cartItems as $item) {
                $totalPrice += $item->product->price * $item->quantity;
            }

            // 3️⃣ Validasi CASH
            $cashPaid = null;
            $change   = null;

            if ($request->payment_method === 'cash') {
                if (!$request->cash_paid || $request->cash_paid < $totalPrice) {
                    throw new \Exception('Cash is not enough.');
                }

                $cashPaid = $request->cash_paid;
                $change   = $cashPaid - $totalPrice;
            }

            // 4️⃣ Simpan TRANSACTION (SATU KALI)
            $transaction = Transaction::create([
                'user_id' => Auth::id(),
                'payment_method' => $request->payment_method,
                'cash_paid' => $cashPaid,
                'change_amount' => $change,
            ]);

            // 5️⃣ Simpan ITEMS + kurangi stok
            foreach ($cartItems as $item) {
                Transaction_item::create([
                    'transaction_id' => $transaction->id,
                    'product_id' => $item->product_id,
                    'quantity' => $item->quantity,
                ]);

                $item->product->decrement('stock', $item->quantity);
            }

            // 6️⃣ Hapus cart
            Cart::where('user_id', Auth::id())->delete();

            DB::commit();

            return redirect()->route('user.transactions.index')
                ->with('success', 'Checkout succeeded!');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', $e->getMessage());
        }
    }

    public function printAllTransactionsPdf()
    {
        $user = Auth::user();

        $transactions = Transaction::with('items.product')
            ->where('user_id', $user->id)
            ->orderBy('created_at', 'asc')
            ->get();

        $grandTotal = 0;
        $totalItems = 0;

        foreach ($transactions as $trx) {
            foreach ($trx->items as $item) {
                $grandTotal += $item->product->price * $item->quantity;
                $totalItems += $item->quantity;
            }
        }

        $pdf = Pdf::loadView(
            'user.transactions.summary-pdf',
            compact('user', 'transactions', 'grandTotal', 'totalItems')
        )->setPaper('A4');

        return $pdf->download('transaction-summary-' . $user->id . '.pdf');
    }
}