<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BookUserView extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'book_id',
        'ip_address',
        'viewed_at',
        'user_agent',
        'platform',
        'browser',
        'device'
    ];

    /**
     * Get browser information based on user agent.
     * This method doesn't use Agent since we now store the
     * information directly when recording the view.
     */
    public function getBrowserInfoAttribute()
    {
        return [
            'browser' => $this->browser,
            'platform' => $this->platform,
            'device' => $this->device,
        ];
    }

    public static function existsWithinTimeWindow($bookId, $userId, $ipAddress, $minutes = 60)
    {
        return static::where('book_id', $bookId)
            ->where('ip_address', $ipAddress)
            ->where(function ($query) use ($userId) {
                if ($userId) {
                    $query->where('user_id', $userId);
                } else {
                    $query->whereNull('user_id');
                }
            })
            ->where('user_agent', request()->userAgent())
            ->where('viewed_at', '>=', now()->subMinutes($minutes))
            ->exists();
    }

    public function book()
    {
        return $this->belongsTo(Book::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
