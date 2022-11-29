<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ShipBoardInformation extends Model
{
  use HasFactory;
  //protected $fillable = ['student_id','company_name','vessel_name','vessel_type', 'sbt_batch','shipping_company','company_group','embarked','disembarked','shipboard_status','start_date','end_date','number_days','is_removed'];
  protected $fillable = [
    'student_id',
    'company_name',
    'vessel_name',
    'vessel_type',
    'shipping_company',
    'shipboard_status',
    'sbt_batch',
    'embarked',
    'disembarked'
  ];
  public function student()
  {
    return $this->belongsTo(StudentDetails::class, 'student_id');
  }
  public function performance_report()
  {
    return $this->hasMany(ShipboardPerformanceReport::class,'shipboard_id')->where('is_removed', false)->orderBy('date_covered', 'asc');
  }
}
