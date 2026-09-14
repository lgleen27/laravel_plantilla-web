<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

class Attribute extends Model
{
    use HasFactory, SoftDeletes;

    public const TYPES = [
        'text' => 'Texto corto',
        'textarea' => 'Texto largo',
        'number' => 'Número entero',
        'decimal' => 'Número decimal',
        'boolean' => 'Sí / No',
        'select' => 'Lista de una opción',
        'multiselect' => 'Lista de varias opciones',
        'date' => 'Fecha',
        'url' => 'Enlace URL',
    ];

    protected $fillable = [
        'name',
        'code',
        'type',
        'is_filterable',
        'is_required',
        'is_variant_attribute',
        'is_active',
        'sort_order',
        'metadata',
    ];

    protected function casts(): array
    {
        return [
            'is_filterable' => 'boolean',
            'is_required' => 'boolean',
            'is_variant_attribute' => 'boolean',
            'is_active' => 'boolean',
            'sort_order' => 'integer',
            'metadata' => 'array',
        ];
    }

    protected static function booted(): void
    {
        static::creating(function (Attribute $attribute): void {
            if (blank($attribute->code)) {
                $attribute->code = Str::snake(Str::ascii($attribute->name));
            }
        });
    }

    public function options(): HasMany
    {
        return $this->hasMany(AttributeOption::class)
            ->orderBy('sort_order')
            ->orderBy('label');
    }

    public function requiresOptions(): bool
    {
        return in_array($this->type, ['select', 'multiselect'], true);
    }
}