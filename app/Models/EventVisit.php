<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EventVisit extends Model
{
    use HasFactory;

    protected $fillable = [
        'event_id',
        'user_id',
        'ip_address',
        'user_agent',
        'browser',
        'platform',
        'device',
        'visited_at'
    ];

    protected $casts = [
        'visited_at' => 'datetime',
    ];

    public function event()
    {
        return $this->belongsTo(Event::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
