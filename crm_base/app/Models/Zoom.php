<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Zoom extends Model
{
    protected $table = 'zoom_tokens';
    protected $fillable = ['access_token', 'expires_in', 'expires_at'];
}
