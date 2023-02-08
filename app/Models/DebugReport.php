<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DebugReport extends Model
{
    use HasFactory;
    protected $fillable = [
        'type_of_user',
        'user_name',
        'user_ip_address',
        'error_message',
        'url_error',
        'data_resolve',
        'is_status'
    ];
}
