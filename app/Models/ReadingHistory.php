<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ReadingHistory extends Model
{
    use HasFactory;

    protected $table = 'reading_history';

    protected $fillable = [
        'user_id',
        'book_id',
        'document_id',
        'accessed_at',
        'ip_address',
        'user_agent',
        'browser',
        'platform',
        'device'
    ];

    protected $casts = [
        'accessed_at' => 'datetime',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function book()
    {
        return $this->belongsTo(Book::class);
    }

    public function document()
    {
        return $this->belongsTo(Document::class);
    }
}
