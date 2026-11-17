<?php

namespace App\Modules\Catalog\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Modules\Catalog\Events\ProductDeleted;
use App\Modules\Catalog\Http\Requests\ProductRequest;
use App\Modules\Catalog\Models\Category;
use App\Modules\Catalog\Models\Product;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\View\View;

class ProductController extends Controller
{
    public function index(): View
    {
        return view('catalog::admin.products.index', [
            'products' => Product::with('categories')->latest()->get(),
        ]);
    }

    public function create(): View
    {
        return view('catalog::admin.products.create', [
            'product' => new Product,
            'categories' => Category::orderBy('name')->get(),
        ]);
    }

    public function store(ProductRequest $request): RedirectResponse
    {
        $data = $request->validated();

        DB::transaction(function () use ($request, $data) {
            $product = Product::create([
                'name' => $data['name'],
                'slug' => $this->uniqueSlug($data['name']),
                'description' => $data['description'] ?? null,
                'price' => $data['price'],
                'stock' => $data['stock'],
                'is_active' => $request->boolean('is_active'),
            ]);

            $product->categories()->sync($data['categories'] ?? []);
        });

        return redirect()->route('admin.products.index')->with('success', 'Produk berhasil ditambahkan.');
    }

    public function edit(Product $product): View
    {
        return view('catalog::admin.products.edit', [
            'product' => $product->load('categories'),
            'categories' => Category::orderBy('name')->get(),
        ]);
    }

    public function update(ProductRequest $request, Product $product): RedirectResponse
    {
        $data = $request->validated();

        DB::transaction(function () use ($request, $data, $product) {
            $product->update([
                'name' => $data['name'],
                'slug' => $product->name === $data['name']
                    ? $product->slug
                    : $this->uniqueSlug($data['name'], $product->id),
                'description' => $data['description'] ?? null,
                'price' => $data['price'],
                'stock' => $data['stock'],
                'is_active' => $request->boolean('is_active'),
            ]);

            $product->categories()->sync($data['categories'] ?? []);
        });

        return redirect()->route('admin.products.index')->with('success', 'Produk berhasil diperbarui.');
    }

    public function destroy(Product $product): RedirectResponse
    {
        $productId = $product->id;

        $product->delete();

        ProductDeleted::dispatch($productId);

        return redirect()->route('admin.products.index')->with('success', 'Produk berhasil dihapus.');
    }

    private function uniqueSlug(string $name, ?int $ignoreId = null): string
    {
        $base = Str::slug($name);
        $slug = $base;
        $i = 2;

        while (Product::where('slug', $slug)
            ->when($ignoreId, fn ($q) => $q->where('id', '!=', $ignoreId))
            ->exists()
        ) {
            $slug = $base.'-'.$i++;
        }

        return $slug;
    }
}
