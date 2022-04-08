<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TicketIssue extends Model
{
    use HasFactory;
    protected $connection = 'mysql2';
    protected $fillable = ['issue_name', 'department_id'];
    public function department()
    {
        return $this->belongsTo(Department::class, 'department_id');
    }
    public function ticket_concern()
    {
        return $this->hasMany(TicketConcern::class, 'issue_id');
    }
    public function ticket()
    {
        return $this->belongsTo(Ticket::class, 'ticket_id');
    }
}
