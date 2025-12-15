<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Genre;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $search   = $request->search;
        $genre    = $request->genre;
        $sort     = $request->sort;
        $minPrice = $request->min_price;
        $maxPrice = $request->max_price;

        $products = Product::with('genre')
            ->when($search, function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%");
            })
            ->when($genre, function ($q) use ($genre) {
                $q->where('genres_id', $genre);
            })
            ->when($minPrice, function ($q) use ($minPrice) {
                $q->where('price', '>=', $minPrice);
            })
            ->when($maxPrice, function ($q) use ($maxPrice) {
                $q->where('price', '<=', $maxPrice);
            })
            ->when($sort, function ($q) use ($sort) {
                return match ($sort) {
                    'price_asc'  => $q->orderBy('price', 'asc'),
                    'price_desc' => $q->orderBy('price', 'desc'),
                    'newest'     => $q->orderBy('created_at', 'desc'),
                    'oldest'     => $q->orderBy('created_at', 'asc'),
                    default      => $q
                };
            })
            ->paginate(12)
            ->withQueryString(); // 🔥 penting biar filter tidak hilang

        $genres = Genre::all();

        return view('user.index', compact(
            'products', 'genres', 'search', 'genre', 'sort', 'minPrice', 'maxPrice'
        ));
    }

    public function indexAdmin(Request $request)
    {
        $search   = $request->search;
        $genre    = $request->genre;
        $sort     = $request->sort;
        $minPrice = $request->min_price;
        $maxPrice = $request->max_price;

        $products = Product::with('genre')
            ->when($search, function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%");
            })
            ->when($genre, function ($q) use ($genre) {
                $q->where('genres_id', $genre);
            })
            ->when($minPrice, function ($q) use ($minPrice) {
                $q->where('price', '>=', $minPrice);
            })
            ->when($maxPrice, function ($q) use ($maxPrice) {
                $q->where('price', '<=', $maxPrice);
            })
            ->when($sort, function ($q) use ($sort) {
                return match ($sort) {
                    'price_asc'  => $q->orderBy('price', 'asc'),
                    'price_desc' => $q->orderBy('price', 'desc'),
                    'newest'     => $q->orderBy('created_at', 'desc'),
                    'oldest'     => $q->orderBy('created_at', 'asc'),
                    default      => $q
                };
            })
            ->paginate(12)
            ->withQueryString(); // 🔥 penting biar filter tidak hilang

        $genres = Genre::all();

        return view('admin.index', compact(
            'products', 'genres', 'search', 'genre', 'sort', 'minPrice', 'maxPrice'
        ));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $genres = Genre::all();
        return view('admin.products.create', compact('genres'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
         $request->validate([
            'name'        => 'required|string|unique:products,name',
            'genres_id'   => 'required|exists:genres,id',
            'description' => 'required|string',
            'price'       => 'required|integer|min:0',
            'stock'       => 'required|integer|min:1',
            'image'       => 'nullable|image|mimes:jpg,png,jpeg|max:2048',
        ]);

        $product = new Product($request->only([
            'name', 
            'genres_id', 
            'description', 
            'price', 
            'stock',
        ]));

        if ($request->hasFile('image')) {
            $product->image = $request->file('image')->store('images', 'public');
        }

        $product->save();

        return redirect()->route('admin.index')->with('success', 'Product added.');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $product = Product::findOrFail($id);
        $genres = Genre::all();
        return view('admin.products.edit', compact('product', 'genres'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $product = Product::findOrFail($id);

        $request->validate([
            'name'        => 'required|string|unique:products,name,' . $id,
            'genres_id'   => 'required|exists:genres,id',
            'description' => 'required|string',
            'price'       => 'required|integer|min:0',
            'stock'       => 'required|integer|min:0',
            'image'       => 'nullable|image|mimes:jpg,png,jpeg|max:2048',
        ]);

        $product->fill($request->only([
            'name', 
            'genres_id', 
            'description', 
            'price',
            'stock',
        ]));

        if ($request->hasFile('image')) {
            Storage::disk('public')->delete($product->image);
            $product->image = $request->file('image')->store('images', 'public');
        }

        $product->save();

        return redirect()->route('admin.index')->with('success', 'Product updated.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $product = Product::findOrFail($id);

        if ($product->stock > 0) {
            return back()->with('error', 'Product cannot be deleted while stock is available.');
        }

        if ($product->image) Storage::disk('public')->delete($product->image);

        $product->delete();

        return redirect()->route('admin.index')->with('success', 'Product deleted.');
    }
}
