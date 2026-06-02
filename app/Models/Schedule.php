<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Schedule extends Model
{
    use HasFactory;

    protected $fillable = [
        'barter_request_id',
        'date',
        'start_time',
        'end_time'
    ];

    public function barter()
    {
        return $this->belongsTo(BarterRequest::class);
    }
}