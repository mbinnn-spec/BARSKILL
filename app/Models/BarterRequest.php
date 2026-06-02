<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BarterRequest extends Model
{
    use HasFactory;

    protected $fillable = [
        'skill_id',
        'requester_name',
        'session_date',
        'duration',
        'notes',
        'status',
        'is_rated'
    ];

    public function fromUser()
    {
        return $this->belongsTo(User::class, 'from_user_id');
    }

    public function toUser()
    {
        return $this->belongsTo(User::class, 'to_user_id');
    }

    public function skill()
    {
        return $this->belongsTo(Skill::class);
    }

    public function requesterUser()
    {
        return $this->belongsTo(User::class, 'requester_name', 'name');
    }

    public function schedule()
    {
        return $this->hasOne(Schedule::class);
    }
}