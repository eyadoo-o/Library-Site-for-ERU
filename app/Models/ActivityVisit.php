<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ActivityVisit extends Model
{
    use HasFactory;

    protected $table = 'activity_visits';

    protected $fillable = [
        'activity_id',
        'user_id',
        'ip_address',
        'user_agent',
        'browser',
        'platform',
        'device',
        'visited_at',
    ];

    protected $casts = [
        'visited_at' => 'datetime',
    ];

    public function activity()
    {
        return $this->belongsTo(ActivityHub::class, 'activity_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
