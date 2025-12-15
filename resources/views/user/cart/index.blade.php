@extends('layout.app')

@section('title', 'My Cart')

@section('content')
<div class="container mt-4">

    {{-- Header --}}
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="fw-bold mb-0">My Cart</h2>
        <a href="{{ route('user.index') }}" class="btn btn-outline-secondary btn-sm">
            Back to Store
        </a>
    </div>

    {{-- Alert --}}
    @foreach (['success', 'error'] as $msg)
        @if (session($msg))
            <div class="alert alert-{{ $msg == 'success' ? 'success' : 'danger' }}">
                {{ session($msg) }}
            </div>
        @endif
    @endforeach

    @if ($cart->isEmpty())
        <div class="alert alert-info text-center">
            Your cart is empty.
        </div>
    @else
        {{-- Cart Table --}}
        <div class="table-responsive mb-4">
            <table class="table table-hover align-middle">
                <thead class="table-dark">
                    <tr>
                        <th>Product</th>
                        <th width="150">Price</th>
                        <th width="100" class="text-center">Qty</th>
                        <th width="150">Subtotal</th>
                        <th width="100"></th>
                    </tr>
                </thead>
                <tbody>
                    @php $total = 0; @endphp

                    @foreach ($cart as $item)
                        @php
                            $subtotal = $item->product->price * $item->quantity;
                            $total += $subtotal;
                        @endphp
                        <tr>
                            <td>{{ $item->product->name }}</td>
                            <td>Rp {{ number_format($item->product->price, 0, ',', '.') }}</td>
                            <td class="text-center">{{ $item->quantity }}</td>
                            <td>Rp {{ number_format($subtotal, 0, ',', '.') }}</td>
                            <td>
                                <form action="{{ route('user.cart.remove', $item->id) }}" method="POST">
                                    @csrf
                                    @method('DELETE')
                                    <button class="btn btn-sm btn-danger w-100">
                                        Remove
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @endforeach

                    <tr class="fw-bold table-secondary">
                        <td colspan="3" class="text-end">Total</td>
                        <td colspan="2">
                            Rp {{ number_format($total, 0, ',', '.') }}
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>

        {{-- Checkout --}}
        <div class="card shadow-sm">
            <div class="card-body">
                <form method="POST" action="{{ route('user.checkout') }}">
                    @csrf

                    <div class="mb-3">
                        <label class="form-label fw-semibold">
                            Payment Method
                        </label>
                        <select name="payment_method" class="form-select" required>
                            <option value="">-- Choose Payment Method --</option>
                            <option value="manual_transfer">Manual Transfer</option>
                            <option value="bank_transfer">Bank Transfer</option>
                            <option value="qris">QRIS</option>
                            <option value="cod">Cash on Delivery</option>
                        </select>
                    </div>

                    <button class="btn btn-success w-100">
                        Checkout
                    </button>
                </form>
            </div>
        </div>
    @endif

</div>
@endsection