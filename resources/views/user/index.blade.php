@extends('layout.app')

@section('title', 'Store')

@section('content')
<div class="container mt-4">

    {{-- Header --}}
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="fw-bold mb-0">Store</h2>

        <a href="{{ route('user.transactions.index') }}" class="btn btn-outline-secondary btn-sm">
            Transactions
        </a>
    </div>

    {{-- Search --}}
    <form method="GET" class="card shadow-sm mb-4">
        <div class="card-body">
            <div class="row g-3">
                <div class="col-md-3">
                    <input type="text" name="search" class="form-control"
                           placeholder="Search product..." value="{{ $search }}">
                </div>

                <div class="col-md-2">
                    <select name="genre" class="form-select">
                        <option value="">All Categories</option>
                        @foreach ($genres as $g)
                            <option value="{{ $g->id }}" {{ $genre == $g->id ? 'selected' : '' }}>
                                {{ $g->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="col-md-2">
                    <input type="number" name="min_price" class="form-control"
                           placeholder="Min Price" value="{{ $minPrice }}">
                </div>

                <div class="col-md-2">
                    <input type="number" name="max_price" class="form-control"
                           placeholder="Max Price" value="{{ $maxPrice }}">
                </div>

                <div class="col-md-2">
                    <select name="sort" class="form-select">
                        <option value="">Sort By</option>
                        <option value="price_asc" {{ $sort == 'price_asc' ? 'selected' : '' }}>Price ↑</option>
                        <option value="price_desc" {{ $sort == 'price_desc' ? 'selected' : '' }}>Price ↓</option>
                        <option value="newest" {{ $sort == 'newest' ? 'selected' : '' }}>Newest</option>
                        <option value="oldest" {{ $sort == 'oldest' ? 'selected' : '' }}>Oldest</option>
                    </select>
                </div>

                <div class="col-md-1 d-grid">
                    <button class="btn btn-dark">Filter</button>
                </div>
            </div>
        </div>
    </form>

    <div class="row">

        {{-- PRODUCT LIST --}}
        <div class="col-md-8">
            @if ($products->isEmpty())
                <div class="alert alert-info text-center">No products found.</div>
            @else
                <div class="row g-4">
                    @foreach ($products as $product)
                        <div class="col-md-6">
                            <div class="card h-100 shadow-sm">

                                @if ($product->image)
                                    <img src="{{ asset('storage/' . $product->image) }}"
                                         class="card-img-top" style="height:180px; object-fit:cover;">
                                @endif

                                <div class="card-body d-flex flex-column">
                                    <h5 class="fw-bold">{{ $product->name }}</h5>
                                    <small class="text-muted">Category: {{ $product->genre->name }}</small>

                                    <p class="mt-2">{{ Str::limit($product->description, 80) }}</p>

                                    <strong class="text-success mb-2">
                                        Rp {{ number_format($product->price, 0, ',', '.') }}
                                    </strong>

                                    <small class="mb-2 
                                        {{ $product->stock > 5 ? 'text-muted' : 'text-danger fw-semibold' }}">
                                        Stock: {{ $product->stock }}
                                    </small>

                                    <form action="{{ route('user.cart.add') }}" method="POST" class="mt-auto">
                                        @csrf
                                        <input type="hidden" name="product_id" value="{{ $product->id }}">

                                        <div class="input-group mb-2">
                                            <span class="input-group-text">Qty</span>
                                            <input type="number" name="quantity" class="form-control"
                                                   value="1" min="1" max="{{ $product->stock }}"
                                                   {{ $product->stock <= 0 ? 'disabled' : '' }}>
                                        </div>

                                        <button class="btn btn-primary w-100"
                                                {{ $product->stock <= 0 ? 'disabled' : '' }}>
                                            Add to Cart
                                        </button>
                                    </form>
                                </div>

                            </div>
                        </div>
                    @endforeach
                </div>
            @endif
        </div>

        {{-- CART SIDEBAR --}}
        <div class="col-md-4">
            <div class="card shadow-sm position-sticky" style="top:80px">
                <div class="card-header bg-dark text-white fw-bold">My Cart</div>

                <div class="card-body">
                    @if ($cart->isEmpty())
                        <p class="text-muted text-center mb-0">Cart is empty</p>
                    @else
                        @php $total = 0; @endphp

                        <ul class="list-group list-group-flush mb-3">
                            @foreach ($cart as $item)
                                @php
                                    $subtotal = $item->product->price * $item->quantity;
                                    $total += $subtotal;
                                @endphp
                                <li class="list-group-item px-0">
                                    <div class="d-flex justify-content-between">
                                        <div>
                                            <strong>{{ $item->product->name }}</strong><br>
                                            <small>{{ $item->quantity }} × Rp {{ number_format($item->product->price,0,',','.') }}</small>
                                        </div>
                                        <form action="{{ route('user.cart.remove', $item->id) }}" method="POST">
                                            @csrf
                                            @method('DELETE')
                                            <button class="btn btn-sm btn-outline-danger">×</button>
                                        </form>
                                    </div>
                                </li>
                            @endforeach
                        </ul>

                        <div class="border-top pt-2 mb-3 fw-bold">
                            Total:
                            <span class="float-end text-success">
                                Rp {{ number_format($total,0,',','.') }}
                            </span>
                        </div>

                        <form method="POST" action="{{ route('user.checkout') }}">
                            @csrf

                            <select name="payment_method" id="payment_method"
                                    class="form-select mb-2" required>
                                <option value="">Payment Method</option>
                                <option value="cash">Cash</option>
                            </select>

                            <div id="cash-input" style="display:none">
                                <input type="number"
                                    name="cash_paid"
                                    id="cash_paid"
                                    class="form-control mb-2"
                                    placeholder="Cash paid (Rp)"
                                    min="{{ $total }}">
                                <small class="text-muted">
                                    Minimum: Rp {{ number_format($total,0,',','.') }}
                                </small>
                            </div>

                            <button class="btn btn-success w-100 mt-2">
                                Checkout
                            </button>
                        </form>
                    @endif
                </div>
            </div>
        </div>

    </div>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
    const paymentMethod = document.getElementById('payment_method');
    const cashInput = document.getElementById('cash-input');
    const cashPaid = document.getElementById('cash_paid');

    paymentMethod.addEventListener('change', function () {
        const isCash = this.value === 'cash';

        cashInput.style.display = isCash ? 'block' : 'none';
        cashPaid.required = isCash;

        if (!isCash) {
            cashPaid.value = '';
        }
    });
});
</script>
@endpush