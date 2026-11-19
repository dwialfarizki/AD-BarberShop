<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PageSection extends Model
{
    use HasFactory;

    protected $fillable = [
        'section_key',
        'title',
        'display_order',
        'is_visible',
    ];

    protected function casts(): array
    {
        return [
            'display_order' => 'integer',
            'is_visible' => 'boolean',
        ];
    }

    public function scopeVisible($query)
    {
        return $query->where('is_visible', true)->orderBy('display_order', 'asc');
    }
}
