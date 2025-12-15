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
    <form class="card shadow-sm mb-4 p-3" method="GET">
        <div class="row g-2">
            <div class="col-md-10">
                <input type="text"
                       name="search"
                       class="form-control"
                       placeholder="Search product..."
                       value="{{ $search ?? '' }}">
            </div>
            <div class="col-md-2 d-grid">
                <button class="btn btn-dark">Search</button>
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