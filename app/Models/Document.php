<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Document extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'title',
        'description',
        'file_path',
        'uploaded_by',
        'type',
    ];

    /**
     * Get the user who uploaded the document.
     */
    public function uploader()
    {
        return $this->belongsTo(User::class, 'uploaded_by');
    }

    /**
     * Get the views for this document.
     */
    public function views()
    {
        return $this->hasMany(DocumentUserView::class);
    }

    /**
     * Get view count for this document.
     */
    public function getViewCountAttribute()
    {
        return $this->views()->count();
    }

    /**
     * Get the document types as options.
     */
    public static function typeOptions()
    {
        return [
            'exam' => 'Exam',
            'article' => 'Article',
            'book' => 'Book',
            'research_paper' => 'Research Paper',
            'audio_book' => 'Audio Book',
            'podcast' => 'Podcast',
        ];
    }
}
