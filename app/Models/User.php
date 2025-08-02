<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Jetstream\HasProfilePhoto;
use Laravel\Sanctum\HasApiTokens;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable
{
    use HasApiTokens;
    use HasFactory;
    use HasProfilePhoto;
    use Notifiable;
    use HasRoles;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'type',
        'profile_photo_path',
        'confirmed',
        'faculty_id'
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The accessors to append to the model's array form.
     *
     * @var array<int, string>
     */
    protected $appends = [
        'profile_photo_url',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    /**
     * Get all book borrowings for the user.
     */
    public function borrowings()
    {
        return $this->hasMany(Borrowing::class);
    }

    /**
     * Get all book reservations for the user.
     */
    public function reservations()
    {
        return $this->hasMany(Reservation::class);
    }

    /**
     * Get all skills offered by the user.
     */
    public function skillOffers()
    {
        return $this->hasMany(SkillOffer::class);
    }

    /**
     * Get all skills requested by the user.
     */
    public function skillRequests()
    {
        return $this->hasMany(SkillRequest::class);
    }

    /**
     * Get all skills for which the user is a mentor.
     */
    public function skills()
    {
        return $this->hasMany(Skill::class, 'mentor_id');
    }

    /**
     * Get all events created by the user.
     */
    public function createdEvents()
    {
        return $this->hasMany(Event::class, 'created_by');
    }

    /**
     * Get all events registered by the user.
     */
    public function eventRegistrations()
    {
        return $this->hasMany(EventRegistration::class);
    }

    /**
     * Get all documents uploaded by the user.
     */
    public function uploadedDocuments()
    {
        return $this->hasMany(Document::class, 'uploaded_by');
    }

    /**
     * Get all study groups created by the user.
     */
    public function createdStudyGroups()
    {
        return $this->hasMany(StudyGroup::class, 'created_by');
    }

    /**
     * Get all study groups the user is a member of.
     */
    public function studyGroups()
    {
        return $this->belongsToMany(StudyGroup::class, 'study_group_members')
                    ->withPivot('joined_at')
                    ->withTimestamps();
    }
}
