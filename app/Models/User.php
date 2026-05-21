<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'email',
        'password',
        'role'
    ];

    protected $hidden = [
        'password',
        'remember_token'
    ];

    protected $casts = [
        'password' => 'hashed',
    ];

    //  Skill
    public function skills()
    {
        return $this->belongsToMany(Skill::class, 'user_skills')
            ->withPivot('rating', 'is_active')
            ->withTimestamps();
    }

    // Barter
    public function sentBarters()
    {
        return $this->hasMany(BarterRequest::class, 'from_user_id');
    }

    public function receivedBarters()
    {
        return $this->hasMany(BarterRequest::class, 'to_user_id');
    }

    //  Chat
    public function chatsAsUser1()
    {
        return $this->hasMany(Chat::class, 'user1_id');
    }

    public function chatsAsUser2()
    {
        return $this->hasMany(Chat::class, 'user2_id');
    }

    //  Notif
    public function notifications()
    {
        return $this->hasMany(Notification::class);
    }
}