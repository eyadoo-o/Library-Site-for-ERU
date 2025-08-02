<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Skill extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'category',
        'description',
        'mentor_id',
        'rating',
        'confirmed'
    ];

    protected $casts = [
        'confirmed' => 'boolean',
    ];

    public function mentor()
    {
        return $this->belongsTo(User::class, 'mentor_id');
    }
}
