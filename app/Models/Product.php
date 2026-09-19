<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Support\Str;

class Product extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'name',
        'slug',
        'sku',
        'short_description',
        'description',
        'price',
        'compare_at_price',
        'track_stock',
        'stock',
        'allow_backorder',
        'status',
        'is_featured',
        'is_quotable',
        'sort_order',
        'seo_title',
        'seo_description',
        'seo_keywords',
        'created_by',
        'updated_by',
    ];

    protected function casts(): array
    {
        return [
            'price' => 'decimal:2',
            'compare_at_price' => 'decimal:2',
            'track_stock' => 'boolean',
            'stock' => 'integer',
            'allow_backorder' => 'boolean',
            'is_featured' => 'boolean',
            'is_quotable' => 'boolean',
            'sort_order' => 'integer',
            'seo_keywords' => 'array',
        ];
    }

    /**
     * Completa automáticamente los datos administrativos al crear un producto.
     */
    protected static function booted(): void
    {
        static::creating(function (Product $product): void {
            /*
            * El slug se genera solo al crear. Si más adelante se edita
            * el nombre, la URL pública permanece estable.
            */
            if (blank($product->slug)) {
                $product->slug = static::generateUniqueSlug($product->name);
            }

            /*
            * El catálogo público trabaja únicamente por cotización.
            */
            $product->is_quotable = true;

            /*
            * Valores que ya no se capturan en el formulario principal.
            * No eliminamos las columnas todavía para no romper la demo,
            * variantes o consultas existentes.
            */
            $product->short_description = null;
            $product->price = null;
            $product->compare_at_price = null;
            $product->track_stock = false;
            $product->stock = null;
            $product->allow_backorder = true;
            $product->seo_keywords = null;

            if ($product->sort_order === null) {
                $product->sort_order = static::nextSortOrder();
            }

            if (blank($product->seo_title)) {
                $product->seo_title = static::generateSeoTitle($product);
            }

            if (blank($product->seo_description)) {
                $product->seo_description = static::generateSeoDescription($product);
            }
        });
    }
    
    /**
     * Genera un slug único para el producto.
     */
    protected static function generateUniqueSlug(string $name): string
    {
        $baseSlug = Str::slug($name);

        if ($baseSlug === '') {
            $baseSlug = 'producto';
        }

        $slug = $baseSlug;
        $counter = 1;

        while (
            static::withTrashed()
                ->where('slug', $slug)
                ->exists()
        ) {
            $counter++;

            $slug = $baseSlug . '-' . $counter;
        }

        return $slug;
    }

    /**
     * Obtiene el siguiente orden general del catálogo.
     */
    protected static function nextSortOrder(): int
    {
        $maxSortOrder = static::query()
            ->max('sort_order');

        return ((int) $maxSortOrder) + 1;
    }

    /**
     * Genera el título SEO a partir del nombre del producto.
     */
    protected static function generateSeoTitle(Product $product): string
    {
        return Str::limit(
            $product->name . ' | Mueblería Liz y Congela',
            60,
            ''
        );
    }

    /**
     * Genera una descripción SEO básica a partir del nombre
     * y la descripción completa.
     */
    protected static function generateSeoDescription(Product $product): string
    {
        $text = $product->description ?: $product->name;

        $text = strip_tags($text);
        $text = preg_replace('/\s+/', ' ', $text);
        $text = trim($text);

        return Str::limit($text, 155, '');
    }

    public function categories(): BelongsToMany
    {
        return $this->belongsToMany(Category::class)
            ->withPivot(['is_primary', 'sort_order']);
    }

    public function attributes(): HasMany
    {
        return $this->hasMany(ProductAttributeValue::class);
    }

    public function primaryCategory(): BelongsToMany
    {
        return $this->categories()->wherePivot('is_primary', true);
    }

    public function variants(): HasMany
    {
        return $this->hasMany(ProductVariant::class)
            ->orderBy('sort_order')
            ->orderBy('name');
    }

    public function brands(): BelongsToMany
    {
        return $this->belongsToMany(Brand::class)
            ->withPivot('sort_order')
            ->orderBy('sort_order')
            ->orderBy('name');
    }

    public function media(): MorphMany
    {
        return $this->morphMany(Media::class, 'mediable')
            ->orderBy('collection')
            ->orderByDesc('is_primary')
            ->orderBy('sort_order');
    }

    public function isAvailable(): bool
    {
        if ($this->status !== 'active') {
            return false;
        }

        if (! $this->track_stock) {
            return true;
        }

        return $this->stock > 0 || $this->allow_backorder;
    }
}