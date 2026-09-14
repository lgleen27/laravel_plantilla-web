<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreProductVariantRequest;
use App\Http\Requests\Admin\UpdateProductVariantRequest;
use App\Models\Product;
use App\Models\ProductVariant;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\View\View;

class ProductVariantController extends Controller
{
    public function index(Product $product): View
    {
        $variants = $product->variants()
            ->withCount('media')
            ->paginate(15);

        return view('admin.products.variants.index', compact('product', 'variants'));
    }

    public function create(Product $product): View
    {
        return view('admin.products.variants.create', compact('product'));
    }

    public function store(StoreProductVariantRequest $request, Product $product): RedirectResponse
    {
        $validated = $request->validated();

        $product->variants()->create([
            ...$this->variantData($validated),
            'created_by' => $request->user()->id,
            'updated_by' => $request->user()->id,
        ]);

        return redirect()
            ->route('admin.products.variants.index', $product)
            ->with('success', 'Variante creada correctamente.');
    }

    public function edit(Product $product, ProductVariant $variant): View
    {
        $this->ensureVariantBelongsToProduct($product, $variant);

        return view('admin.products.variants.edit', compact('product', 'variant'));
    }

    public function update(
        UpdateProductVariantRequest $request,
        Product $product,
        ProductVariant $variant
    ): RedirectResponse {
        $this->ensureVariantBelongsToProduct($product, $variant);

        $variant->update([
            ...$this->variantData($request->validated()),
            'updated_by' => $request->user()->id,
        ]);

        return redirect()
            ->route('admin.products.variants.index', $product)
            ->with('success', 'Variante actualizada correctamente.');
    }

    public function destroy(Product $product, ProductVariant $variant): RedirectResponse
    {
        $this->ensureVariantBelongsToProduct($product, $variant);

        $variant->delete();

        return redirect()
            ->route('admin.products.variants.index', $product)
            ->with('success', 'Variante eliminada correctamente.');
    }

    private function variantData(array $validated): array
    {
        return [
            'name' => $validated['name'],
            'slug' => blank($validated['slug'] ?? null)
                ? Str::slug($validated['name'])
                : Str::slug($validated['slug']),
            'sku' => blank($validated['sku'] ?? null) ? null : $validated['sku'],
            'barcode' => blank($validated['barcode'] ?? null) ? null : $validated['barcode'],
            'price' => $validated['price'] ?? null,
            'compare_at_price' => $validated['compare_at_price'] ?? null,
            'track_stock' => $validated['track_stock'],
            'stock' => $validated['track_stock'] ? $validated['stock'] : null,
            'allow_backorder' => $validated['allow_backorder'],
            'status' => $validated['status'],
            'sort_order' => $validated['sort_order'],
        ];
    }

    private function ensureVariantBelongsToProduct(
        Product $product,
        ProductVariant $variant
    ): void {
        abort_unless($variant->product_id === $product->id, 404);
    }
}