<?php

namespace App\Console\Commands;

use App\Services\ProductImageAuditService;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;

class ExportMissingImagesCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'products:missing-images 
                            {--format=all : Formato de exportación: txt, json o all}
                            {--drafts : Incluir productos con estatus draft/borrador}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Analiza el catálogo y genera un reporte en TXT y JSON con todos los productos y variantes que no tienen imágenes cargadas.';

    /**
     * Execute the console command.
     */
    public function handle(ProductImageAuditService $auditService): int
    {
        $this->info('🔍 Analizando catálogo de productos y variantes...');

        $includeDrafts = (bool) $this->option('drafts');
        $format = strtolower((string) $this->option('format'));

        $report = $auditService->getAuditReport($includeDrafts);
        $summary = $report['summary'];

        $this->newLine();
        $this->info('📊 RESUMEN DEL CATÁLOGO:');
        $this->table(
            ['Métrica', 'Cantidad'],
            [
                ['Total productos analizados', $summary['total_products_analyzed']],
                ['Productos SIN NINGUNA imagen', $summary['products_completely_without_images']],
                ['Productos con al menos una imagen', $summary['products_with_at_least_one_image']],
                ['Total variantes sin imagen propia', $summary['total_variants_without_own_image']],
            ]
        );

        $savedFiles = [];

        // Generar TXT
        if (in_array($format, ['txt', 'all'])) {
            $txtContent = $auditService->formatAsTxt($report);
            $txtRootPath = base_path('productos_sin_imagen.txt');
            File::put($txtRootPath, $txtContent);
            $savedFiles[] = $txtRootPath;
        }

        // Generar JSON
        if (in_array($format, ['json', 'all'])) {
            $jsonContent = $auditService->formatAsJson($report);
            $jsonRootPath = base_path('productos_sin_imagen.json');
            File::put($jsonRootPath, $jsonContent);
            $savedFiles[] = $jsonRootPath;
        }

        $this->newLine();
        $this->info('✅ Archivos generados con éxito:');
        foreach ($savedFiles as $filePath) {
            $this->line("   📁 <comment>{$filePath}</comment>");
        }

        // Mostrar vista previa en consola de los primeros productos sin imagen
        $without = $report['products_without_images'];
        if (! empty($without)) {
            $this->newLine();
            $this->warn('⚠️ Primeros productos sin imagen encontrados:');
            $previewRows = array_map(function ($item) {
                return [
                    $item['id'],
                    $item['name'],
                    $item['category'],
                    $item['sku'],
                    $item['variants_count'],
                ];
            }, array_slice($without, 0, 15));

            $this->table(['ID', 'Producto', 'Categoría', 'SKU', 'Variantes'], $previewRows);

            if (count($without) > 15) {
                $remaining = count($without) - 15;
                $this->comment("... y {$remaining} productos más detallados en el archivo TXT y JSON.");
            }
        }

        $this->newLine();
        return Command::SUCCESS;
    }
}
