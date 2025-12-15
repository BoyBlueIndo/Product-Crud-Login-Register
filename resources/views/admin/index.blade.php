@extends('layout.app')

@section('title', 'Admin Dashboard')

@section('content')
<div class="container mt-4">

    {{-- Header --}}
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="fw-bold">Admin Dashboard</h1>
        <div class="d-flex gap-2">
            <a href="{{ route('admin.products.create') }}" class="btn btn-primary btn-sm">
                + Add Product
            </a>
            <a href="{{ route('admin.genres.index') }}" class="btn btn-outline-secondary btn-sm">
                Manage Genres
            </a>
            <a href="{{ route('admin.transactions.index') }}" class="btn btn-outline-dark btn-sm">
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

    {{-- Product Table --}}
    <div class="card shadow-sm">
        <div class="table-responsive">
            <table class="table table-striped table-bordered align-middle mb-0">
                <thead class="table-dark">
                    <tr>
                        <th width="90">Image</th>
                        <th>Name</th>
                        <th>Genre</th>
                        <th>Description</th>
                        <th width="120">Price</th>
                        <th width="90">Stock</th>
                        <th width="130">Actions</th>
                    </tr>
                </thead>
                <tbody>
                @forelse ($products as $product)
                    <tr>
                        <td class="text-center">
                            @if ($product->image)
                                <img src="{{ asset('storage/'.$product->image) }}"
                                     width="70"
                                     class="rounded shadow-sm">
                            @else
                                <span class="text-muted">No image</span>
                            @endif
                        </td>
                        <td class="fw-semibold">{{ $product->name }}</td>
                        <td>{{ $product->genre->name }}</td>
                        <td style="max-width:260px">
                            {{ Str::limit($product->description, 80) }}
                        </td>
                        <td>Rp {{ number_format($product->price, 0, ',', '.') }}</td>
                        <td class="text-center">{{ $product->stock }}</td>
                        <td>
                            <div class="d-grid gap-1">
                                <a href="{{ route('admin.products.edit', $product->id) }}"
                                   class="btn btn-warning btn-sm">
                                    Edit
                                </a>
                                <form action="{{ route('admin.products.destroy', $product->id) }}"
                                      method="POST"
                                      onsubmit="return confirm('Delete this product?')">
                                    @csrf
                                    @method('DELETE')
                                    <button class="btn btn-danger btn-sm w-100">
                                        Delete
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="text-center text-muted">
                            No products found.
                        </td>
                    </tr>
                @endforelse
                </tbody>
            </table>
        </div>
    </div>

</div>
@endsection