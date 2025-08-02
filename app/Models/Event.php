<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Event extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'description',
        'event_date',
        'location',
        'image',
        'status',
        'max_attendees',
        'current_attendees',
        'event_type',
        'created_by'
    ];

    protected $casts = [
        'event_date' => 'datetime',
        'max_attendees' => 'integer',
        'current_attendees' => 'integer'
    ];

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function registrations(): HasMany
    {
        return $this->hasMany(EventRegistration::class);
    }

    public function visits()
    {
        return $this->hasMany(EventVisit::class);
    }

    public function isRegistered(User $user)
    {
        return $this->registrations()->where('user_id', $user->id)->exists();
    }

    /**
     * Get the event's image URL.
     *
     * @return string|null
     */
    public function getImageUrlAttribute()
    {
        if (!$this->image) {
            return null;
        }

        if (filter_var($this->image, FILTER_VALIDATE_URL)) {
            return $this->image;
        }

        return asset('storage/' . $this->image);
    }
}
