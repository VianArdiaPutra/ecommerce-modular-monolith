<?php

namespace App\Modules\Catalog\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Modules\Catalog\Models\Category;
use App\Modules\Catalog\Models\Product;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ProductController extends Controller
{
    public function index(Request $request): View
    {
        $products = Product::with('categories')
            ->where('is_active', true)
            ->when($request->query('q'), fn($query, $term) => $query->where('name', 'like', "%{$term}%"))
            ->when($request->query('category'), fn($query, $slug) => $query->whereHas(
                'categories',
                fn($c) => $c->where('slug', $slug)
            ))
            ->orderBy('name')
            ->get();

        return view('catalog::products.index', [
            'products' => $products,
            'categories' => Category::orderBy('name')->get(),
        ]);
    }

    public function show(Product $product): View
    {
        abort_unless($product->is_active, 404);

        return view('catalog::products.show', [
            'product' => $product->load('categories'),
        ]);
    }
}
