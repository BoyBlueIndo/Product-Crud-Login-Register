@extends('layout.app')

@section('title', 'Store')

@section('content')
<div class="container mt-4">

    {{-- Header --}}
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="fw-bold mb-0">Store</h2>

        <div class="btn-group">
            <a href="{{ route('user.cart') }}" class="btn btn-outline-primary btn-sm">
                Cart
            </a>
            <a href="{{ route('user.transactions.index') }}" class="btn btn-outline-secondary btn-sm">
                Transactions
            </a>
        </div>
    </div>

    {{-- Search --}}
    <form method="GET" class="card shadow-sm mb-4">
        <div class="card-body">
            <div class="row g-3">

                {{-- Search --}}
                <div class="col-md-3">
                    <input type="text" 
                        name="search"
                        class="form-control"
                        placeholder="Search product..."
                        value="{{ $search }}">
                </div>

                {{-- Genre --}}
                <div class="col-md-2">
                    <select name="genre" class="form-select">
                        <option value="">All Genres</option>
                        @foreach ($genres as $g)
                            <option value="{{ $g->id }}"
                                {{ $genre == $g->id ? 'selected' : '' }}>
                                {{ $g->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                {{-- Min Price --}}
                <div class="col-md-2">
                    <input type="number"
                        name="min_price"
                        class="form-control"
                        placeholder="Min Price"
                        value="{{ $minPrice }}">
                </div>

                {{-- Max Price --}}
                <div class="col-md-2">
                    <input type="number"
                        name="max_price"
                        class="form-control"
                        placeholder="Max Price"
                        value="{{ $maxPrice }}">
                </div>

                {{-- Sort --}}
                <div class="col-md-2">
                    <select name="sort" class="form-select">
                        <option value="">Sort By</option>
                        <option value="price_asc" {{ $sort == 'price_asc' ? 'selected' : '' }}>
                            Price ↑
                        </option>
                        <option value="price_desc" {{ $sort == 'price_desc' ? 'selected' : '' }}>
                            Price ↓
                        </option>
                        <option value="newest" {{ $sort == 'newest' ? 'selected' : '' }}>
                            Newest
                        </option>
                        <option value="oldest" {{ $sort == 'oldest' ? 'selected' : '' }}>
                            Oldest
                        </option>
                    </select>
                </div>

                {{-- Button --}}
                <div class="col-md-1 d-grid">
                    <button class="btn btn-dark">
                        Filter
                    </button>
                </div>

            </div>
        </div>
    </form>

    {{-- Product List --}}
    @if ($products->isEmpty())
        <div class="alert alert-info text-center">
            No products found.
        </div>
    @else
        <div class="row g-4">
            @foreach ($products as $product)
                <div class="col-md-4">
                    <div class="card h-100 shadow-sm">

                        {{-- Image --}}
                        @if ($product->image)
                            <img 
                                src="{{ asset('storage/' . $product->image) }}" 
                                class="card-img-top"
                                style="height:180px; object-fit:cover;"
                            >
                        @else
                            <img 
                                src="https://via.placeholder.com/300x180?text=No+Image"
                                class="card-img-top"
                            >
                        @endif

                        <div class="card-body d-flex flex-column">
                            <h5 class="fw-bold">
                                {{ $product->name }}
                            </h5>

                            <small class="text-muted mb-2">
                                Genre: {{ $product->genre->name }}
                            </small>

                            <p class="grow">
                                {{ Str::limit($product->description, 90) }}
                            </p>

                            <div class="mb-2">
                                <strong class="text-success">
                                    Rp {{ number_format($product->price, 0, ',', '.') }}
                                </strong>
                            </div>

                            <small class="text-muted mb-3">
                                Stock: {{ $product->stock }}
                            </small>

                            <form action="{{ route('user.cart.add') }}" method="POST" class="mt-auto">
                                @csrf

                                <input type="hidden" name="product_id" value="{{ $product->id }}">

                                <div class="input-group mb-2">
                                    <span class="input-group-text">Quantity</span>
                                    <input 
                                        type="number"
                                        name="quantity"
                                        class="form-control"
                                        value="1"
                                        min="1"
                                        max="{{ $product->stock }}"
                                        {{ $product->stock <= 0 ? 'disabled' : '' }}
                                    >
                                </div>

                                <button lass="btn btn-primary w-100" {{ $product->stock <= 0 ? 'disabled' : '' }}>
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
@endsection