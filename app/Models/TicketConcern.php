<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TicketConcern extends Model
{
    use HasFactory;
    protected $connection = 'mysql2';

    /* public function ticket_concern()
    {
        return $this->belongsTo(Ticket::class, 'ticket_id');
    } */
    public function ticket_issue()
    {
        return $this->belongsTo(TicketIssue::class, 'issue_id');
    }
    public function ticket()
    {
        return $this->belongsTo(Ticket::class, 'ticket_id');
    }
}
