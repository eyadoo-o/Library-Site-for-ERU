<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SkillExchange extends Model
{
    use HasFactory;

    protected $fillable = [
        'mentor_id',
        'student_id',
        'skill_id',
        'exchange_with_id',
        'description',
        'status',
        'rating',
        'feedback'
    ];

    public function mentor()
    {
        return $this->belongsTo(User::class, 'mentor_id');
    }

    public function student()
    {
        return $this->belongsTo(User::class, 'student_id');
    }

    public function skill()
    {
        return $this->belongsTo(Skill::class);
    }

    public function exchangeWith()
    {
        return $this->belongsTo(Skill::class, 'exchange_with_id');
    }
}
