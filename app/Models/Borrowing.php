<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Borrowing extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'user_id',
        'book_id',
        'borrowed_at',
        'due_date',
        'returned_at',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'borrowed_at' => 'datetime',
        'due_date' => 'datetime',
        'returned_at' => 'datetime',
    ];

    /**
     * Get the user who borrowed the book.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the book that was borrowed.
     */
    public function book()
    {
        return $this->belongsTo(Book::class);
    }

    /**
     * Check if the borrowing is overdue.
     */
    public function isOverdue()
    {
        return $this->returned_at === null && now() > $this->due_date;
    }
}
