<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Research extends Model
{
    protected $table = 'researches';

    protected $fillable = [
        'title', 'authors', 'abstract', 'journal', 'year',
        'doi', 'link', 'category', 'is_published', 'sort_order', 'created_by',
    ];

    protected $casts = [
        'is_published' => 'boolean',
        'year' => 'integer',
    ];

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function scopePublished($query)
    {
        return $query->where('is_published', true);
    }
}
