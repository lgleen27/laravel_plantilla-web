<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreProductRequest;
use App\Http\Requests\Admin\UpdateProductRequest;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;
use Illuminate\Support\Facades\Storage;

class ProductController extends Controller
{
    public function index(): View
    {
        $products = Product::query()
            ->with('categories')
            ->orderByDesc('created_at')
            ->paginate(15);

        return view('admin.products.index', compact('products'));
    }

    public function create(): View
    {
        $categories = Category::where('is_active', true)->orderBy('name')->get();
        return view('admin.products.create', compact('categories'));
    }

    public function store(StoreProductRequest $request): RedirectResponse
    {
        $validated = $request->validated();
        $storedPaths = [];

        // VALIDACIÓN ESTRICTA DE VARIANTES NUEVAS
        $nombresUsados = [];
        if (!empty($validated['variants'])) {
            foreach ($validated['variants'] as $v) {
                $name = trim($v['name'] ?? '');
                if (empty($name)) $name = 'Única';

                if (in_array(strtolower($name), $nombresUsados)) {
                    return back()->withInput()->with('error', "No puedes duplicar el nombre de variante: '$name'");
                }
                $nombresUsados[] = strtolower($name);

                if (empty($v['images'])) {
                    return back()->withInput()->with('error', "La variante '$name' debe tener al menos una fotografía.");
                }
            }
        }

        try {
            DB::transaction(function () use ($validated, $request, &$storedPaths) {
                $product = Product::create([
                    'name' => $validated['name'],
                    'sku' => $validated['sku'] ?? null,
                    'description' => $validated['description'] ?? null,
                    'status' => $validated['status'],
                    'is_featured' => (bool) ($validated['is_featured'] ?? false),
                    'is_quotable' => true,
                    'track_stock' => false,
                    'allow_backorder' => true,
                    'created_by' => $request->user()->id,
                    'updated_by' => $request->user()->id,
                ]);

                $product->categories()->sync([
                    $validated['category_id'] => ['is_primary' => true, 'sort_order' => 1]
                ]);

                if (!empty($validated['variants'])) {
                    foreach ($validated['variants'] as $index => $variantData) {
                        $variantName = trim($variantData['name'] ?? '');
                        if (empty($variantName)) $variantName = 'Única';

                        $variant = $product->variants()->create([
                            'name' => $variantName,
                            'sku' => $variantData['sku'] ?? null,
                            'status' => 'active',
                            'sort_order' => $index + 1,
                            'created_by' => $request->user()->id,
                            'updated_by' => $request->user()->id,
                        ]);

                        foreach ($variantData['images'] as $imageIndex => $image) {
                            $path = $image->store('products/' . $product->id . '/variants/' . $variant->id, 'public');
                            $storedPaths[] = $path;

                            $variant->media()->create([
                                'collection' => 'gallery', 'disk' => 'public', 'path' => $path,
                                'file_name' => $image->getClientOriginalName(), 'mime_type' => $image->getMimeType(),
                                'size' => $image->getSize(), 'alt_text' => $product->name . ' - ' . $variantName,
                                'is_primary' => $imageIndex === 0, 'sort_order' => $imageIndex + 1,
                                'created_by' => $request->user()->id,
                            ]);
                        }
                    }
                }
            });
        } catch (\Throwable $exception) {
            foreach ($storedPaths as $path) Storage::disk('public')->delete($path);
            report($exception);
            return back()->withInput()->with('error', 'Ocurrió un error al guardar el producto.');
        }

        return redirect()->route('admin.products.index')->with('success', 'Producto guardado correctamente.');
    }

    public function edit(Product $product): View
    {
        $product->load('categories', 'variants.media');
        $categories = Category::where('is_active', true)->orderBy('name')->get();

        return view('admin.products.edit', compact('product', 'categories'));
    }

    public function update(UpdateProductRequest $request, Product $product): RedirectResponse
    {
        $validated = $request->validated();
        $storedPaths = [];

        // VALIDACIÓN ESTRICTA EN EDICIÓN
        $nombresUsados = [];
        $deleteMedia = $validated['delete_media'] ?? [];
        $deleteVariants = $validated['delete_variants'] ?? [];

        // 1. Revisar variantes existentes
        if (!empty($validated['existing_variants'])) {
            foreach ($validated['existing_variants'] as $id => $v) {
                if (in_array($id, $deleteVariants)) continue;

                $name = trim($v['name'] ?? '');
                if (empty($name)) $name = 'Única';

                if (in_array(strtolower($name), $nombresUsados)) {
                    return back()->withInput()->with('error', "No puedes duplicar el nombre de variante: '$name'");
                }
                $nombresUsados[] = strtolower($name);

                // Validar que no se quede sin fotos
                $variantModel = $product->variants()->find($id);
                if ($variantModel) {
                    $currentMediaIds = $variantModel->media->pluck('id')->toArray();
                    $remainingMedia = array_diff($currentMediaIds, $deleteMedia);
                    
                    if (empty($remainingMedia) && empty($v['new_images'])) {
                        return back()->withInput()->with('error', "La variante '$name' no puede quedarse sin fotografías. Sube al menos una o elimina la variante.");
                    }
                }
            }
        }

        // 2. Revisar variantes nuevas
        if (!empty($validated['variants'])) {
            foreach ($validated['variants'] as $v) {
                $name = trim($v['name'] ?? '');
                if (empty($name)) $name = 'Única';

                if (in_array(strtolower($name), $nombresUsados)) {
                    return back()->withInput()->with('error', "No puedes duplicar el nombre de variante: '$name'");
                }
                $nombresUsados[] = strtolower($name);

                if (empty($v['images'])) {
                    return back()->withInput()->with('error', "La nueva variante '$name' debe tener al menos una fotografía.");
                }
            }
        }

        try {
            DB::transaction(function () use ($product, $validated, $request, &$storedPaths, $deleteMedia, $deleteVariants) {
                $product->update([
                    'name' => $validated['name'],
                    'sku' => $validated['sku'] ?? null,
                    'description' => $validated['description'] ?? null,
                    'status' => $validated['status'],
                    'is_featured' => (bool) ($validated['is_featured'] ?? false),
                    'updated_by' => $request->user()->id,
                ]);

                $product->categories()->sync([$validated['category_id'] => ['is_primary' => true, 'sort_order' => 1]]);

                if (!empty($deleteMedia)) {
                    $mediaToDelete = \App\Models\Media::whereIn('id', $deleteMedia)->get();
                    foreach ($mediaToDelete as $media) {
                        Storage::disk($media->disk)->delete($media->path);
                        $media->delete();
                    }
                }

                if (!empty($deleteVariants)) {
                    $variantsToDelete = $product->variants()->whereIn('id', $deleteVariants)->get();
                    foreach ($variantsToDelete as $variant) {
                        foreach ($variant->media as $media) {
                            Storage::disk($media->disk)->delete($media->path);
                            $media->delete();
                        }
                        $variant->delete();
                    }
                }

                if (!empty($validated['existing_variants'])) {
                    foreach ($validated['existing_variants'] as $variantId => $data) {
                        $variant = $product->variants()->find($variantId);
                        if ($variant && !in_array($variantId, $deleteVariants)) {
                            $variantName = trim($data['name'] ?? '');
                            if (empty($variantName)) $variantName = 'Única';
                            
                            $variant->update(['name' => $variantName, 'sku' => $data['sku'] ?? null]);

                            if (!empty($data['new_images'])) {
                                $nextOrder = $variant->media()->max('sort_order') + 1;
                                foreach ($data['new_images'] as $image) {
                                    $path = $image->store('products/' . $product->id . '/variants/' . $variant->id, 'public');
                                    $storedPaths[] = $path;

                                    $variant->media()->create([
                                        'collection' => 'gallery', 'disk' => 'public', 'path' => $path,
                                        'file_name' => $image->getClientOriginalName(), 'mime_type' => $image->getMimeType(),
                                        'size' => $image->getSize(), 'alt_text' => $product->name . ' - ' . $variantName,
                                        'is_primary' => false, 'sort_order' => $nextOrder++,
                                        'created_by' => $request->user()->id,
                                    ]);
                                }
                            }
                        }
                    }
                }

                if (!empty($validated['variants'])) {
                    $nextOrder = $product->variants()->max('sort_order') + 1;
                    foreach ($validated['variants'] as $variantData) {
                        $variantName = trim($variantData['name'] ?? '');
                        if (empty($variantName)) $variantName = 'Única';

                        $variant = $product->variants()->create([
                            'name' => $variantName,
                            'sku' => $variantData['sku'] ?? null,
                            'status' => 'active',
                            'sort_order' => $nextOrder++,
                            'created_by' => $request->user()->id,
                            'updated_by' => $request->user()->id,
                        ]);

                        foreach ($variantData['images'] as $imageIndex => $image) {
                            $path = $image->store('products/' . $product->id . '/variants/' . $variant->id, 'public');
                            $storedPaths[] = $path;

                            $variant->media()->create([
                                'collection' => 'gallery', 'disk' => 'public', 'path' => $path,
                                'file_name' => $image->getClientOriginalName(), 'mime_type' => $image->getMimeType(),
                                'size' => $image->getSize(), 'alt_text' => $product->name . ' - ' . $variantName,
                                'is_primary' => $imageIndex === 0, 'sort_order' => $imageIndex + 1,
                                'created_by' => $request->user()->id,
                            ]);
                        }
                    }
                }
            });
        } catch (\Throwable $exception) {
            foreach ($storedPaths as $path) Storage::disk('public')->delete($path);
            report($exception);
            return back()->withInput()->with('error', 'Ocurrió un error al actualizar el producto.');
        }

        return redirect()->route('admin.products.index')->with('success', 'Producto actualizado correctamente.');
    }

    public function destroy(Product $product): RedirectResponse
    {
        $product->delete();
        return redirect()->route('admin.products.index')->with('success', 'Producto eliminado correctamente.');
    }
}