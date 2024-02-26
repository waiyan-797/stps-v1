<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\CarType;
use App\Models\User;

class Trip extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'distance',
        'duration',
        'waiting_time',

        'normal_fee',
        'waiting_fee',
        'extra_fee',
        'total_cost',

        'start_lat',
        'start_lng',
        'end_lat',
        'end_lng',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function cartype()
    {
        return $this->hasOne(CarType::class);
    }
}
