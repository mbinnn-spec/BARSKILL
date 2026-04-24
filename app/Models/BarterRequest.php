<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BarterRequest extends Model
{
    use HasFactory;

    protected $fillable = [
        'from_user_id',
        'to_user_id',
        'skill_id',
        'date',
        'time',
        'duration',
        'note',
        'status'
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

    public function schedule()
    {
        return $this->hasOne(Schedule::class);
    }
}