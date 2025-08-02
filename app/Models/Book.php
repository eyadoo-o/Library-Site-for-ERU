<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Book extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'authors',
        'isbn',
        'category_id',
        'quantity',
        'format',
        'image',
        'file_path',
        'edition',
        'added_by',
    ];

    protected $casts = [
        'authors' => 'array',
    ];

    // Always consider digital books available regardless of quantity
    public function getIsAvailableAttribute()
    {
        return $this->format === 'digital' || $this->quantity > 0;
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    /**
     * Get the user who added the book.
     */
    public function addedBy()
    {
        return $this->belongsTo(User::class, 'added_by');
    }

    /**
     * Get authors as a comma-separated string.
     */
    public function getAuthorsStringAttribute()
    {
        return is_array($this->authors) ? implode(', ', $this->authors) : '';
    }

    public function views()
    {
        return $this->hasMany(BookUserView::class);
    }
}
