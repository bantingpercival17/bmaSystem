<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CourseSemestralFees extends Model
{
    use HasFactory;
    protected $fillable = ['course_id', 'curriculum_id', 'academic_id', 'year_level', 'is_removed'];

    public function semestral_fees($_data)
    {
        return SemestralFee::select('p.particular_tag')
            ->selectRaw("sum(pf.particular_amount) as fees")
            ->join('particular_fees as pf', 'pf.id', 'semestral_fees.particular_fee_id')
            ->join('particulars as p', 'p.id', 'pf.particular_id')
            ->where('semestral_fees.course_semestral_fee_id', $_data)
            ->groupBy('p.particular_tag')
            ->where('p.particular_tag', '!=', 'addition_tags')
            ->get();
    }
    public function additional_fees($_data)
    {
        return SemestralFee::select('p.particular_name', 'pf.particular_amount')
            /* ->selectRaw("sum(pf.particular_amount) as fees") */
            ->join('particular_fees as pf', 'pf.id', 'semestral_fees.particular_fee_id')
            ->join('particulars as p', 'p.id', 'pf.particular_id')
            ->where('semestral_fees.course_semestral_fee_id', $_data)
            /*  ->groupBy('p.particular_tag') */
            ->where('p.particular_tag', '=', 'addition_tags')
            ->get();
    }
    public function course()
    {
        return $this->belongsTo(CourseOffer::class, 'course_id');
    }
    public function academic()
    {
        return $this->belongsTo(AcademicYear::class, 'academic_id');
    }
    public function payment_assessments()
    {
        return $this->belongsTo(PaymentAssessment::class, 'course_semestral_fee_id');
    }
    public function fee()
    {
        return $this->hasMany(SemestralFee::class, 'course_semestral_fee_id')
            ->selectRaw("sum(pf.particular_amount) as fees")
            ->join('particular_fees as pf', 'semestral_fees.particular_fee_id', 'pf.id')
            ->where('semestral_fees.is_removed', false);
    }
    public function payment_amount($_data)
    {
        if ($_data->payment_mode == 1) {
            // Installment 
            // Get the Monthly Payment
            if ($_data->course_id == 3) {
                $_tuition_fee =  $this->hasMany(SemestralFee::class, 'course_semestral_fee_id')
                    ->selectRaw("sum(pf.particular_amount) as fees")
                    ->join('particular_fees as pf', 'semestral_fees.particular_fee_id', 'pf.id')
                    ->join('particulars as p', 'p.id', 'pf.particular_id')
                    ->where('p.particular_tag', '!=', 'addition_tags')
                    ->where('semestral_fees.is_removed', false)->get();
                $_other_fees =    $_tuition_fee =  $this->hasMany(SemestralFee::class, 'course_semestral_fee_id')
                    ->selectRaw("sum(pf.particular_amount) as fees")
                    ->join('particular_fees as pf', 'semestral_fees.particular_fee_id', 'pf.id')
                    ->join('particulars as p', 'p.id', 'pf.particular_id')
                    ->where('p.particular_tag', '=', 'addition_tags')->get();
                $_tuition_fee = $_tuition_fee[0]->fees + 710;
                $_monthly_fee = $_tuition_fee - ($_tuition_fee * 0.20);
                return ($_monthly_fee / 4);
            } else {
                $_tuition_fee = $this->hasMany(SemestralFee::class, 'course_semestral_fee_id')
                    ->selectRaw("sum(pf.particular_amount) as fees")
                    ->join('particular_fees as pf', 'semestral_fees.particular_fee_id', 'pf.id')
                    ->where('semestral_fees.is_removed', false)->get();
                return $_tuition_fee[0]->fees / 5;
            }
        } else {
            // Full-Payment 
            // Get the Payment
            $_tuition_fee =  $this->hasMany(SemestralFee::class, 'course_semestral_fee_id')
                ->selectRaw("sum(pf.particular_amount) as fees")
                ->join('particular_fees as pf', 'semestral_fees.particular_fee_id', 'pf.id')
                ->where('semestral_fees.is_removed', false)->get();
            return $_tuition_fee[0]->fees;
        }
    }
}
