<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreProductVariantRequest;
use App\Http\Requests\Admin\UpdateProductVariantRequest;
use App\Models\Product;
use App\Models\ProductVariant;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
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

    public function store(
        StoreProductVariantRequest $request,
        Product $product
    ): RedirectResponse {
        $validated = $request->validated();
        $storedPaths = [];

        try {
            DB::transaction(function () use (
                $request,
                $product,
                $validated,
                &$storedPaths
            ): void {
                $variant = $product->variants()->create([
                    ...$this->variantData($validated),
                    'created_by' => $request->user()->id,
                    'updated_by' => $request->user()->id,
                ]);

                $this->storeVariantImages(
                    $product,
                    $variant,
                    $request->file('images', []),
                    $request->user()->id,
                    $storedPaths,
                );
            });
        } catch (\Throwable $exception) {
            /*
            * DB::transaction revierte la variante y los registros media,
            * pero no elimina archivos físicos ya guardados.
            */
            foreach ($storedPaths as $path) {
                Storage::disk('public')->delete($path);
            }

            report($exception);

            return back()
                ->withInput()
                ->with('error', 'No fue posible crear la variante. Intenta nuevamente.');
        }

        return redirect()
            ->route('admin.products.variants.index', $product)
            ->with('success', 'Variante creada correctamente con sus imágenes.');
    }

    public function edit(Product $product, ProductVariant $variant): View
    {
        $this->ensureVariantBelongsToProduct($product, $variant);

        $variant->load([
            'media' => fn ($query) => $query
                ->where('collection', 'gallery')
                ->orderByDesc('is_primary')
                ->orderBy('sort_order'),
        ]);

        return view('admin.products.variants.edit', compact('product', 'variant'));
    }

    public function update(
        UpdateProductVariantRequest $request,
        Product $product,
        ProductVariant $variant
    ): RedirectResponse {
        $this->ensureVariantBelongsToProduct($product, $variant);

        $validated = $request->validated();
        $storedPaths = [];

        try {
            DB::transaction(function () use (
                $request,
                $product,
                $variant,
                $validated,
                &$storedPaths
            ): void {
                $variant->update([
                    ...$this->variantData($validated),
                    'updated_by' => $request->user()->id,
                ]);

                $this->storeVariantImages(
                    $product,
                    $variant,
                    $request->file('images', []),
                    $request->user()->id,
                    $storedPaths,
                );
            });
        } catch (\Throwable $exception) {
            foreach ($storedPaths as $path) {
                Storage::disk('public')->delete($path);
            }

            report($exception);

            return back()
                ->withInput()
                ->with('error', 'No fue posible actualizar la variante. Intenta nuevamente.');
        }

        return redirect()
            ->route('admin.products.variants.edit', [$product, $variant])
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

    /**
     * Guarda imágenes de una variante.
     *
     * Si la variante no tiene galería todavía, la primera imagen nueva será principal.
     * Si ya tiene imágenes, las nuevas se agregan al final sin cambiar la principal.
     */
    private function storeVariantImages(
        Product $product,
        ProductVariant $variant,
        array $images,
        int $userId,
        array &$storedPaths,
    ): void {
        if (empty($images)) {
            return;
        }

        $hasPrimaryImage = $variant->media()
            ->where('collection', 'gallery')
            ->where('is_primary', true)
            ->exists();

        $nextSortOrder = ((int) $variant->media()
            ->where('collection', 'gallery')
            ->max('sort_order')) + 1;

        foreach ($images as $index => $image) {
            $path = $image->store(
                'products/' . $product->id . '/variants/' . $variant->id,
                'public',
            );

            $storedPaths[] = $path;

            $variant->media()->create([
                'collection' => 'gallery',
                'disk' => 'public',
                'path' => $path,
                'file_name' => $image->getClientOriginalName(),
                'mime_type' => $image->getMimeType(),
                'size' => $image->getSize(),
                'alt_text' => $product->name . ' - ' . $variant->name,
                'is_primary' => ! $hasPrimaryImage && $index === 0,
                'sort_order' => $nextSortOrder,
                'created_by' => $userId,
            ]);

            $hasPrimaryImage = true;
            $nextSortOrder++;
        }
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