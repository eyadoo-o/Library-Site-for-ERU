<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Reservation extends Model
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
        'reserved_at',
        'status',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'reserved_at' => 'datetime',
    ];

    /**
     * Get the user who made the reservation.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the book that was reserved.
     */
    public function book()
    {
        return $this->belongsTo(Book::class);
    }

    /**
     * Get the status options.
     */
    public static function statusOptions()
    {
        return [
            'pending' => __('messages.reservation_status_pending'),
            'approved' => __('messages.reservation_status_approved'),
            'rejected' => __('messages.reservation_status_rejected'),
            'cancelled' => __('messages.reservation_status_cancelled'),
            'completed' => __('messages.reservation_status_completed'),
        ];
    }
}
