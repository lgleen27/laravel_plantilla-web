<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

class ProductVariant extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'product_id',
        'name',
        'slug',
        'sku',
        'barcode',
        'price',
        'compare_at_price',
        'track_stock',
        'stock',
        'allow_backorder',
        'status',
        'sort_order',
        'metadata',
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
            'sort_order' => 'integer',
            'metadata' => 'array',
        ];
    }

    protected static function booted(): void
    {
        static::creating(function (ProductVariant $variant): void {
            if (blank($variant->slug)) {
                $variant->slug = Str::slug($variant->name);
            }
        });
    }

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
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