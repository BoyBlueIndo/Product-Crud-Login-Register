@extends('layout.app')

@section('title', 'Edit Product')

@section('content')
<div class="container mt-4">

    <h1 class="mb-4">Edit Product</h1>

    <div class="card p-4 shadow-sm">
        <form action="{{ route('admin.products.update', $product->id) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')

            <div class="mb-3">
                <label class="form-label">Product Name</label>
                <input type="text" name="name" class="form-control" value="{{ $product->name }}">
            </div>

            <div class="mb-3">
                <label class="form-label">Genre</label>
                <select name="genres_id" class="form-control">
                    @foreach ($genres as $genre)
                        <option value="{{ $genre->id }}" 
                            {{ $product->genres_id == $genre->id ? 'selected' : '' }}>
                            {{ $genre->name }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="mb-3">
                <label class="form-label">Description</label>
                <textarea name="description" class="form-control" rows="4">{{ $product->description }}</textarea>
            </div>

            <div class="mb-3">
                <label class="form-label">Price</label>
                <input type="number" name="price" class="form-control" value="{{ $product->price }}">
            </div>

            <div class="mb-3">
                <label class="form-label">Stocks</label>
                <input type="number" name="stock" class="form-control" min="0" value="{{ $product->stock }}">
            </div>

            <div class="mb-3">
                <label class="form-label">Image</label>
                <input type="file" name="image" class="form-control" accept="image/*">

                @if ($product->image)
                    <img src="{{ asset('storage/' . $product->image) }}" width="120" class="mt-2 rounded">
                @endif
            </div>

            <button class="btn btn-warning w-100">Save Changes</button>
        </form>
    </div>

</div>
@endsection