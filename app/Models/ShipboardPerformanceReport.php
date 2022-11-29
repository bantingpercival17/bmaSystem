<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ShipboardPerformanceReport extends Model
{
    use HasFactory;
    protected $fillable = [
        'shipboard_id', 'month', 'date_covered', 'task_trb', 'trb_code', 'date_preferred', 'daily_journal', 'have_signature'
    ];
}