<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Notification extends Model
{
    use HasFactory;
    protected $fillable = ['user_id', 'service', 'account_name', 'phone', 'amount', 'screenshot', 'status'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
