<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class ProductController extends Controller
{
    public function show($slug)
    {
        $product = Product::with('category')->where('slug', $slug)->firstOrFail();
        $relatedProducts = Product::where('category_id', $product->category_id)
            ->where('id', '!=', $product->id)
            ->take(4)
            ->get();

        if (request()->wantsJson()) {
            return response()->json([
                'product' => $product,
                'formatted_price' => $product->formatted_price,
                'formatted_original_price' => $product->formatted_original_price,
                'related' => $relatedProducts,
            ]);
        }

        return view('products.show', compact('product', 'relatedProducts'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'category_id' => 'required|exists:categories,id',
            'name' => 'required|string|max:255',
            'price' => 'required|numeric|min:0',
            'original_price' => 'nullable|numeric|min:0',
            'stock' => 'required|integer|min:0',
            'store_name' => 'required|string|max:255',
            'store_location' => 'required|string|max:255',
            'image_url' => 'required|url',
            'description' => 'required|string',
        ]);

        $validated['slug'] = Str::slug($validated['name']).'-'.rand(100, 999);
        $validated['discount_percent'] = $validated['original_price'] && $validated['original_price'] > $validated['price']
            ? round((($validated['original_price'] - $validated['price']) / $validated['original_price']) * 100)
            : 0;
        $validated['is_official_store'] = $request->has('is_official_store');
        $validated['is_flash_sale'] = $request->has('is_flash_sale');

        $product = Product::create($validated);

        return redirect()->back()->with('success', 'Produk "'.$product->name.'" berhasil ditambahkan!');
    }

    public function destroy($id)
    {
        $product = Product::findOrFail($id);
        $product->delete();

        return redirect()->back()->with('success', 'Produk berhasil dihapus!');
    }
}
