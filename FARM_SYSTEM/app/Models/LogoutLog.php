<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LogoutLog extends Model
{
    use HasFactory;

    protected $fillable = ['user_login_id', 'logout_time', 'ip_address', 'user_agent', 'logout_message'];

    public function user()
    {
        return $this->belongsTo(UserLogin::class, 'user_login_id'); 
    }
    
}
