<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ProductAttributeValue extends Model
{
    use HasFactory;

    protected $fillable = [
        'attribute_id',
        'product_id',
        'attribute_option_id',
        'value_text',
        'value_number',
        'value_boolean',
        'value_date',
    ];

    protected function casts(): array
    {
        return [
            'attribute_id' => 'integer',
            'product_id' => 'integer',
            'attribute_option_id' => 'integer',
            'value_number' => 'decimal:6',
            'value_boolean' => 'boolean',
            'value_date' => 'date',
        ];
    }

    public function attribute(): BelongsTo
    {
        return $this->belongsTo(Attribute::class);
    }

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }

    public function option(): BelongsTo
    {
        return $this->belongsTo(AttributeOption::class, 'attribute_option_id');
    }
}