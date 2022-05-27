<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TicketChat extends Model
{
    use HasFactory;
    protected $connection = 'mysql2';
    protected $fillable = ['concern_id', 'staff_id', 'sender_column', 'message', 'group_id'];

    public function concern()
    {
        return $this->belongsTo(TicketConcern::class, 'concern_id');
    }
    public function staff()
    {
        return $this->belongsTo(Staff::class, 'staff_id');
    }
}
