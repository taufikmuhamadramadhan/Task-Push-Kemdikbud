<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Application extends Model
{
    use HasFactory;

    protected $fillable = ['id', 'user_id', 'token', 'expired_at'];

    public $timestamps = false;

    protected $keyType = 'string';
}
