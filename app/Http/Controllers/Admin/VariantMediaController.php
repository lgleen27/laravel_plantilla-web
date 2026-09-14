<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Media;
use App\Models\Product;
use App\Models\ProductVariant;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class VariantMediaController extends Controller
{
    public function index(Product $product, ProductVariant $variant)
    {
        $this->ensureVariantBelongsToProduct($product, $variant);

        $media = $variant->media()
            ->where('collection', 'gallery')
            ->get();

        return view('admin.products.variants.media.index', compact(
            'product',
            'variant',
            'media',
        ));
    }

    public function store(Request $request, Product $product, ProductVariant $variant): RedirectResponse
    {
        $this->ensureVariantBelongsToProduct($product, $variant);

        $request->validate([
            'images' => ['required', 'array', 'min:1', 'max:10'],
            'images.*' => [
                'required',
                'image',
                'mimes:jpg,jpeg,png,webp',
                'max:5120',
            ],
        ], [
            'images.required' => 'Selecciona al menos una imagen.',
            'images.max' => 'Puedes cargar un máximo de 10 imágenes a la vez.',
            'images.*.image' => 'Cada archivo debe ser una imagen válida.',
            'images.*.mimes' => 'Las imágenes deben ser JPG, JPEG, PNG o WEBP.',
            'images.*.max' => 'Cada imagen puede pesar como máximo 5 MB.',
        ]);

        DB::transaction(function () use ($request, $variant): void {
            $hasPrimaryImage = $variant->media()
                ->where('collection', 'gallery')
                ->where('is_primary', true)
                ->exists();

            $nextSortOrder = (int) $variant->media()
                ->where('collection', 'gallery')
                ->max('sort_order') + 1;

            foreach ($request->file('images') as $image) {
                $path = $image->store(
                    'products/'.$variant->product_id.'/variants/'.$variant->id,
                    'public',
                );

                $variant->media()->create([
                    'collection' => 'gallery',
                    'disk' => 'public',
                    'path' => $path,
                    'file_name' => $image->getClientOriginalName(),
                    'mime_type' => $image->getMimeType(),
                    'size' => $image->getSize(),
                    'alt_text' => $variant->product->name.' - '.$variant->name,
                    'is_primary' => ! $hasPrimaryImage,
                    'sort_order' => $nextSortOrder,
                    'created_by' => $request->user()->id,
                ]);

                $hasPrimaryImage = true;
                $nextSortOrder++;
            }
        });

        return redirect()
            ->route('admin.products.variants.media.index', [$product, $variant])
            ->with('success', 'Imágenes cargadas correctamente.');
    }

    public function makePrimary(
        Product $product,
        ProductVariant $variant,
        Media $media
    ): RedirectResponse {
        $this->ensureVariantBelongsToProduct($product, $variant);
        $this->ensureMediaBelongsToVariant($variant, $media);

        DB::transaction(function () use ($variant, $media): void {
            $variant->media()
                ->where('collection', 'gallery')
                ->update(['is_primary' => false]);

            $media->update(['is_primary' => true]);
        });

        return redirect()
            ->route('admin.products.variants.media.index', [$product, $variant])
            ->with('success', 'Imagen principal actualizada.');
    }

    public function destroy(
        Product $product,
        ProductVariant $variant,
        Media $media
    ): RedirectResponse {
        $this->ensureVariantBelongsToProduct($product, $variant);
        $this->ensureMediaBelongsToVariant($variant, $media);

        $wasPrimary = $media->is_primary;

        DB::transaction(function () use ($variant, $media, $wasPrimary): void {
            Storage::disk($media->disk)->delete($media->path);
            $media->delete();

            if ($wasPrimary) {
                $nextMedia = $variant->media()
                    ->where('collection', 'gallery')
                    ->orderBy('sort_order')
                    ->first();

                if ($nextMedia) {
                    $nextMedia->update(['is_primary' => true]);
                }
            }
        });

        return redirect()
            ->route('admin.products.variants.media.index', [$product, $variant])
            ->with('success', 'Imagen eliminada correctamente.');
    }

    private function ensureVariantBelongsToProduct(
        Product $product,
        ProductVariant $variant
    ): void {
        abort_unless($variant->product_id === $product->id, 404);
    }

    private function ensureMediaBelongsToVariant(
        ProductVariant $variant,
        Media $media
    ): void {
        abort_unless(
            $media->mediable_type === ProductVariant::class
            && $media->mediable_id === $variant->id
            && $media->collection === 'gallery',
            404,
        );
    }
}