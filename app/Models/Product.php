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

    protected static function booted(): void
    {
        static::creating(function (Product $product): void {
            if (blank($product->slug)) {
                $product->slug = Str::slug($product->name);
            }
        });
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