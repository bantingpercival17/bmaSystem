<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ShipBoardInformation extends Model
{
    use HasFactory;
    protected $fillable = ['student_id','company_name','vessel_name','vessel_type', 'sbt_batch','shipping_company','shipboard_status','start_date','end_date','number_days','is_removed'];
}
