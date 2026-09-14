<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class Media extends Model
{
    use HasFactory;

    protected $fillable = [
        'collection',
        'disk',
        'path',
        'file_name',
        'mime_type',
        'size',
        'alt_text',
        'is_primary',
        'sort_order',
        'metadata',
        'created_by',
    ];

    protected function casts(): array
    {
        return [
            'size' => 'integer',
            'is_primary' => 'boolean',
            'sort_order' => 'integer',
            'metadata' => 'array',
        ];
    }

    public function mediable(): MorphTo
    {
        return $this->morphTo();
    }
}