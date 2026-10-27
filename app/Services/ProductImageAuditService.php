<?php

namespace App\Services;

use App\Models\Product;
use Carbon\Carbon;

class ProductImageAuditService
{
    /**
     * Genera el reporte estructurado de productos y variantes sin imágenes.
     */
    public function getAuditReport(bool $includeDrafts = false): array
    {
        $query = Product::with([
            'categories',
            'variants' => function ($variantQuery) {
                $variantQuery->with('media');
            },
            'media',
        ]);

        if (! $includeDrafts) {
            $query->where('status', '!=', 'draft');
        }

        $products = $query->orderBy('name')->get();

        $productsWithoutImages = [];
        $partiallyMissing = [];
        $totalVariantsWithoutOwnImage = 0;
        $totalProductsWithImages = 0;

        foreach ($products as $product) {
            $primaryCategory = $product->categories->firstWhere('pivot.is_primary', true)
                ?? $product->categories->first();
            $categoryName = $primaryCategory?->name ?? 'Sin categoría';

            $productMediaCount = $product->media->count();
            $variantsData = [];
            $productTotalImages = $productMediaCount;
            $variantsMissingOwnImage = [];

            foreach ($product->variants as $variant) {
                $variantMediaCount = $variant->media->count();
                $productTotalImages += $variantMediaCount;

                $variantInfo = [
                    'id' => $variant->id,
                    'name' => $variant->name ?: $variant->sku ?: ('Variante #' . $variant->id),
                    'sku' => $variant->sku ?: 'No especificado',
                    'images_count' => $variantMediaCount,
                    'has_own_image' => $variantMediaCount > 0,
                    'status' => $variant->status,
                ];

                $variantsData[] = $variantInfo;

                if ($variantMediaCount === 0) {
                    $totalVariantsWithoutOwnImage++;
                    $variantsMissingOwnImage[] = $variantInfo;
                }
            }

            if ($productTotalImages === 0) {
                // Producto completamente sin imágenes
                $productsWithoutImages[] = [
                    'id' => $product->id,
                    'name' => $product->name,
                    'slug' => $product->slug,
                    'sku' => $product->sku ?: 'No especificado',
                    'category' => $categoryName,
                    'status' => $product->status,
                    'variants_count' => count($variantsData),
                    'variants' => $variantsData,
                ];
            } else {
                $totalProductsWithImages++;

                // Producto que sí tiene fotos en general, pero algunas variantes no tienen foto propia
                if (count($variantsMissingOwnImage) > 0) {
                    $partiallyMissing[] = [
                        'id' => $product->id,
                        'name' => $product->name,
                        'slug' => $product->slug,
                        'sku' => $product->sku ?: 'No especificado',
                        'category' => $categoryName,
                        'total_images' => $productTotalImages,
                        'variants_missing_images_count' => count($variantsMissingOwnImage),
                        'variants_missing_images' => $variantsMissingOwnImage,
                    ];
                }
            }
        }

        return [
            'generated_at' => Carbon::now()->toIso8601String(),
            'generated_at_human' => Carbon::now()->format('d/m/Y H:i:s'),
            'summary' => [
                'total_products_analyzed' => $products->count(),
                'products_completely_without_images' => count($productsWithoutImages),
                'products_with_at_least_one_image' => $totalProductsWithImages,
                'total_variants_without_own_image' => $totalVariantsWithoutOwnImage,
            ],
            'products_without_images' => $productsWithoutImages,
            'partially_missing_variants' => $partiallyMissing,
        ];
    }

    /**
     * Genera el reporte formateado en texto plano (.txt).
     */
    public function formatAsTxt(array $report): string
    {
        $summary = $report['summary'];
        $without = $report['products_without_images'];
        $partial = $report['partially_missing_variants'];

        $out = [];
        $out[] = str_repeat('=', 80);
        $out[] = ' REPORTE DE AUDITORÍA: PRODUCTOS Y VARIANTES SIN IMÁGENES';
        $out[] = ' Catálogo - Mueblería Liz y Congela';
        $out[] = ' Fecha de generación: ' . ($report['generated_at_human'] ?? date('d/m/Y H:i:s'));
        $out[] = str_repeat('=', 80);
        $out[] = '';
        $out[] = 'RESUMEN GENERAL:';
        $out[] = ' • Total de productos analizados: ' . $summary['total_products_analyzed'];
        $out[] = ' • Productos COMPLETAMENTE SIN IMAGEN: ' . $summary['products_completely_without_images'];
        $out[] = ' • Productos con al menos una imagen: ' . $summary['products_with_at_least_one_image'];
        $out[] = ' • Total de variantes sin fotografía propia: ' . $summary['total_variants_without_own_image'];
        $out[] = '';
        $out[] = str_repeat('-', 80);
        $out[] = ' 1. PRODUCTOS QUE NO TIENEN NINGUNA FOTO (' . count($without) . ' productos)';
        $out[] = ' (Estos productos quedan ocultos si se activa el filtro "Solo con foto")';
        $out[] = str_repeat('-', 80);
        $out[] = '';

        if (empty($without)) {
            $out[] = '¡Excelente! Todos los productos cuentan con al menos una fotografía.';
            $out[] = '';
        } else {
            foreach ($without as $index => $item) {
                $num = $index + 1;
                $out[] = "{$num}. [ID: {$item['id']}] {$item['name']}";
                $out[] = "   Categoría : {$item['category']}";
                $out[] = "   SKU Ppal  : {$item['sku']}";
                $out[] = "   Variantes : " . count($item['variants']);

                if (! empty($item['variants'])) {
                    foreach ($item['variants'] as $v) {
                        $out[] = "     - Variante: {$v['name']} (SKU: {$v['sku']}) [Sin foto]";
                    }
                }
                $out[] = '';
            }
        }

        $out[] = str_repeat('-', 80);
        $out[] = ' 2. PRODUCTOS CON FOTOS, PERO CON VARIANTES SIN FOTO PROPIA (' . count($partial) . ' productos)';
        $out[] = ' (Tienen foto compartida/general, pero estas variantes específicas no tienen imagen propia)';
        $out[] = str_repeat('-', 80);
        $out[] = '';

        if (empty($partial)) {
            $out[] = 'No hay variantes secundarias sin fotografía propia.';
            $out[] = '';
        } else {
            foreach ($partial as $index => $item) {
                $num = $index + 1;
                $out[] = "{$num}. [ID: {$item['id']}] {$item['name']} (Categoría: {$item['category']})";
                $out[] = "   Fotos totales del producto: {$item['total_images']}";
                $out[] = "   Variantes a las que les falta foto propia:";
                foreach ($item['variants_missing_images'] as $v) {
                    $out[] = "     - {$v['name']} (SKU: {$v['sku']})";
                }
                $out[] = '';
            }
        }

        $out[] = str_repeat('=', 80);
        $out[] = ' FIN DEL REPORTE';
        $out[] = str_repeat('=', 80);

        return implode("\n", $out);
    }

    /**
     * Genera el reporte formateado en JSON indentado.
     */
    public function formatAsJson(array $report): string
    {
        return json_encode($report, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
    }
}
