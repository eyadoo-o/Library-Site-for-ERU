<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ActivityHub extends Model
{
    use HasFactory;

    protected $table = 'activity_hub';

    protected $fillable = [
        'name',
        'description',
        'created_by',
        'status',
        'location',
        'image',
        'activity_type',
        'start_date',
        'end_date',
        'current_members'
    ];

    protected $casts = [
        'start_date' => 'datetime',
        'end_date' => 'datetime',
    ];

    public function getStatusBadgeAttribute()
    {
        return match($this->status) {
            'active' => 'bg-green-100 text-green-800',
            'inactive' => 'bg-red-100 text-red-800',
            default => 'bg-gray-100 text-gray-800'
        };
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function visits()
    {
        return $this->hasMany(ActivityVisit::class, 'activity_id');
    }
}
