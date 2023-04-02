<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ShippingAgencies extends Model
{
    use HasFactory;
    protected $fillable = [
        'agency_name', 'agency_address', 'staff_id', 'is_removed'
    ];
}
