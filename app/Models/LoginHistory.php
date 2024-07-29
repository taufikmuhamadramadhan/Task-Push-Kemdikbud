<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LoginHistory extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id', 'status', 'device', 'created_at'
    ];

    public $timestamps = false;

    protected $keyType = 'string';

    protected $table = 'login_history';
}
