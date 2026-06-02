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
        'status'
    ];

    protected $appends = ['rating', 'review_count'];

    public function getRatingAttribute()
    {
        $avg = \App\Models\Rating::where('skill_id', $this->id)->avg('rating');
        return $avg ? round($avg, 1) : null;
    }

    public function getReviewCountAttribute()
    {
        return \App\Models\Rating::where('skill_id', $this->id)->count();
    }

    public function users()
    {
        return $this->belongsToMany(User::class, 'user_skills')
            ->withPivot('rating', 'is_active')
            ->withTimestamps();
    }
    
}