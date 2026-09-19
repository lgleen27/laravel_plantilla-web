<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

class Category extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'parent_id',
        'name',
        'slug',
        'description',
        'is_active',
        'sort_order',
    ];

    protected function casts(): array
    {
        return [
            'is_active' => 'boolean',
            'sort_order' => 'integer',
        ];
    }

    /**
     * Completa datos administrativos al crear una categoría.
     */
    protected static function booted(): void
    {
        static::creating(function (Category $category): void {
            if (blank($category->slug)) {
                $category->slug = static::generateUniqueSlug($category->name);
            }

            if ($category->sort_order === null) {
                $category->sort_order = static::nextSortOrder(
                    $category->parent_id
                );
            }
        });
    }

    /**
     * Genera un slug único para evitar conflictos de URL.
     */
    protected static function generateUniqueSlug(string $name): string
    {
        $baseSlug = Str::slug($name);

        if ($baseSlug === '') {
            $baseSlug = 'categoria';
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
     * Obtiene el siguiente orden disponible dentro del mismo padre.
     */
    protected static function nextSortOrder(?int $parentId): int
    {
        $maxSortOrder = static::query()
            ->where('parent_id', $parentId)
            ->max('sort_order');

        return ((int) $maxSortOrder) + 1;
    }

    /**
     * Categoría padre.
     */
    public function parent(): BelongsTo
    {
        return $this->belongsTo(self::class, 'parent_id');
    }

    /**
     * Subcategorías directas.
     */
    public function children(): HasMany
    {
        return $this->hasMany(self::class, 'parent_id')
            ->orderBy('sort_order')
            ->orderBy('name');
    }

    /**
     * Obtiene todos los descendientes directos e indirectos.
     */
    public function childrenRecursive(): HasMany
    {
        return $this->children()->with('childrenRecursive');
    }

    /**
     * Determina si la categoría indicada pertenece a los descendientes
     * de la categoría actual.
     */
    public function hasDescendant(Category $category): bool
    {
        foreach ($this->children as $child) {
            if ($child->is($category) || $child->hasDescendant($category)) {
                return true;
            }
        }

        return false;
    }

    /**
     * Devuelve los IDs de todos los descendientes directos e indirectos.
     *
     * @return array<int, int>
     */
    public function descendantIds(): array
    {
        $ids = [];

        foreach ($this->children as $child) {
            $ids[] = $child->id;
            $ids = array_merge($ids, $child->descendantIds());
        }

        return $ids;
    }

    public function products(): BelongsToMany
    {
        return $this->belongsToMany(Product::class)
            ->withPivot(['is_primary', 'sort_order']);
    }
}