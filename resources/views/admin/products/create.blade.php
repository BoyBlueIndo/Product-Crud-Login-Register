@extends('layout.app')

@section('title', 'Add New Product')

@section('content')
<div class="container mt-4">

    <h1 class="mb-4">Add New Product</h1>

    <div class="card p-4 shadow-sm">
        <form action="{{ route('admin.products.store') }}" method="POST" enctype="multipart/form-data">
            @csrf

            <div class="mb-3">
                <label class="form-label">Product Name</label>
                <input type="text" name="name" class="form-control" required>
            </div>

            <div class="mb-3">
                <label class="form-label">Category</label>
                <select name="genres_id" class="form-control" required>
                    <option value="">-- Choose Category --</option>
                    @foreach ($genres as $genre)
                        <option value="{{ $genre->id }}">{{ $genre->name }}</option>
                    @endforeach
                </select>
            </div>

            <div class="mb-3">
                <label class="form-label">Description</label>
                <textarea name="description" class="form-control" rows="4" required></textarea>
            </div>

            <div class="mb-3">
                <label class="form-label">Price</label>
                <input type="number" name="price" class="form-control" required>
            </div>

            <div class="mb-3">
                <label class="form-label">Stocks</label>
                <input type="number" name="stock" class="form-control" min="1" required>
            </div>

            <div class="mb-3">
                <label class="form-label">Image</label>
                <input type="file" name="image" class="form-control" accept="image/*">
            </div>

            <button class="btn btn-primary w-100">Create Product</button>
        </form>
    </div>

</div>
@endsection