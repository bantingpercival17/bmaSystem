<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CourseSemestralFees extends Model
{
    use HasFactory;
    protected $fillable = ['course_id', 'curriculum_id', 'academic_id', 'year_level', 'is_removed'];

    public function curriculum()
    {
        return $this->belongsTo(Curriculum::class, 'curriculum_id');
    }
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
    public function particular_tags($_tag)
    {
        $_fees =  $this->hasMany(SemestralFee::class, 'course_semestral_fee_id')
            ->select('p.particular_name', 'pf.particular_amount')
            ->join('particular_fees as pf', 'pf.id', 'semestral_fees.particular_fee_id')
            ->join('particulars as p', 'p.id', 'pf.particular_id')
            ->where('p.particular_tag', '=', $_tag)->sum('pf.particular_amount');
        if ($_tag == 'tuition_tags') {
            $_number_of_units = $this->course->units($this)->units;
            $_fees = $_fees  * $_number_of_units;
        }
        return $_fees;
    }
    public function semestral_fee_list()
    {
        return $this->hasMany(SemestralFee::class, 'course_semestral_fee_id')->where('is_removed', false);
    }
    public function course()
    {
        return $this->belongsTo(CourseOffer::class, 'course_id');
    }
    public function academic()
    {
        return $this->belongsTo(AcademicYear::class, 'academic_id');
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
            if ($_data->enrollment_assessment->course_id == 3) {
                $_tuition_fee =  $this->hasMany(SemestralFee::class, 'course_semestral_fee_id')
                    ->selectRaw("sum(pf.particular_amount) as fees")
                    ->join('particular_fees as pf', 'semestral_fees.particular_fee_id', 'pf.id')
                    ->join('particulars as p', 'p.id', 'pf.particular_id')
                    ->where('p.particular_tag', '!=', 'addition_tags')
                    ->where('semestral_fees.is_removed', false)->get();
                $_other_fees =   $this->hasMany(SemestralFee::class, 'course_semestral_fee_id')
                    ->selectRaw("sum(pf.particular_amount) as fees")
                    ->join('particular_fees as pf', 'semestral_fees.particular_fee_id', 'pf.id')
                    ->join('particulars as p', 'p.id', 'pf.particular_id')
                    ->where('p.particular_tag', '=', 'addition_tags')->get();
                $_tuition_fee =  intval($_tuition_fee[0]['fees']) + 710;
                $_monthly_fee = ($_tuition_fee - ($_tuition_fee * 0.20));
                return ($_monthly_fee / 4);
            } else {
                $_tuition_fee = $this->hasMany(SemestralFee::class, 'course_semestral_fee_id')
                    ->selectRaw("sum(pf.particular_amount) as fees")
                    ->join('particular_fees as pf', 'semestral_fees.particular_fee_id', 'pf.id')
                    ->where('semestral_fees.is_removed', false)->get();
                $_total_fees =  ($_tuition_fee[0]->fees * .035) + $_tuition_fee[0]->fees;
                return $_total_fees / 5;
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
    public function total_payments($_data)
    {
        if ($_data->payment_mode == 1) {
            // Installment 
            // Get the Monthly Payment
            if ($_data->enrollment_assessment->course_id == 3) {
                $_tuition_fee =  $this->hasMany(SemestralFee::class, 'course_semestral_fee_id')
                    ->selectRaw("sum(pf.particular_amount) as fees")
                    ->join('particular_fees as pf', 'semestral_fees.particular_fee_id', 'pf.id')
                    ->join('particulars as p', 'p.id', 'pf.particular_id')
                    ->where('p.particular_tag', '!=', 'addition_tags')
                    ->where('semestral_fees.is_removed', false)->get();
                $_other_fees =   $this->hasMany(SemestralFee::class, 'course_semestral_fee_id')
                    ->selectRaw("sum(pf.particular_amount) as fees")
                    ->join('particular_fees as pf', 'semestral_fees.particular_fee_id', 'pf.id')
                    ->join('particulars as p', 'p.id', 'pf.particular_id')
                    ->where('p.particular_tag', '=', 'addition_tags')->get();
                $_tuition_fee =  intval($_tuition_fee[0]['fees']) + 710;
                $_monthly_fee = ($_tuition_fee - ($_tuition_fee * 0.20));
                return intval($_tuition_fee) + intval($_other_fees[0]->fees);
            } else {
                $_tuition_fee = $this->hasMany(SemestralFee::class, 'course_semestral_fee_id')
                    ->selectRaw("sum(pf.particular_amount) as fees")
                    ->join('particular_fees as pf', 'semestral_fees.particular_fee_id', 'pf.id')
                    ->where('semestral_fees.is_removed', false)->get();
                return ($_tuition_fee[0]->fees * .035) + $_tuition_fee[0]->fees;
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
    public function upon_enrollment($_data)
    {
        if ($_data->payment_mode == 1) {
            // Installment 
            // Get the Monthly Payment
            if ($_data->enrollment_assessment->course_id == 3) {
                $_tuition_fee =  $this->hasMany(SemestralFee::class, 'course_semestral_fee_id')
                    ->selectRaw("sum(pf.particular_amount) as fees")
                    ->join('particular_fees as pf', 'semestral_fees.particular_fee_id', 'pf.id')
                    ->join('particulars as p', 'p.id', 'pf.particular_id')
                    ->where('p.particular_tag', '!=', 'addition_tags')
                    ->where('semestral_fees.is_removed', false)->get();
                $_other_fees =   $this->hasMany(SemestralFee::class, 'course_semestral_fee_id')
                    ->selectRaw("sum(pf.particular_amount) as fees")
                    ->join('particular_fees as pf', 'semestral_fees.particular_fee_id', 'pf.id')
                    ->join('particulars as p', 'p.id', 'pf.particular_id')
                    ->where('p.particular_tag', '=', 'addition_tags')->get();
                $_tuition_fee =  intval($_tuition_fee[0]['fees']) + 710;
                return ($_tuition_fee * 0.20) + intval($_other_fees[0]->fees);
            } else {
                $_tuition_fee = $this->hasMany(SemestralFee::class, 'course_semestral_fee_id')
                    ->selectRaw("sum(pf.particular_amount) as fees")
                    ->join('particular_fees as pf', 'semestral_fees.particular_fee_id', 'pf.id')
                    ->where('semestral_fees.is_removed', false)->get();
                return ($_tuition_fee[0]->fees * .035) + $_tuition_fee[0]->fees;
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
    public function monthly_fees($_data)
    {
        if ($_data->payment_mode == 1) {
            // Installment 
            // Get the Monthly Payment
            if ($_data->enrollment_assessment->course_id == 3) {
                $_tuition_fee =  $this->hasMany(SemestralFee::class, 'course_semestral_fee_id')
                    ->selectRaw("sum(pf.particular_amount) as fees")
                    ->join('particular_fees as pf', 'semestral_fees.particular_fee_id', 'pf.id')
                    ->join('particulars as p', 'p.id', 'pf.particular_id')
                    ->where('p.particular_tag', '!=', 'addition_tags')
                    ->where('semestral_fees.is_removed', false)->get();
                $_other_fees =   $this->hasMany(SemestralFee::class, 'course_semestral_fee_id')
                    ->selectRaw("sum(pf.particular_amount) as fees")
                    ->join('particular_fees as pf', 'semestral_fees.particular_fee_id', 'pf.id')
                    ->join('particulars as p', 'p.id', 'pf.particular_id')
                    ->where('p.particular_tag', '=', 'addition_tags')->get();
                $_tuition_fee =  intval($_tuition_fee[0]['fees']) + 710;
                $_monthly_fee = ($_tuition_fee - ($_tuition_fee * 0.20));
                return ($_monthly_fee / 4);
            } else {
                $_tuition_fee = $this->hasMany(SemestralFee::class, 'course_semestral_fee_id')
                    ->selectRaw("sum(pf.particular_amount) as fees")
                    ->join('particular_fees as pf', 'semestral_fees.particular_fee_id', 'pf.id')
                    ->where('semestral_fees.is_removed', false)->get();
                $_total_fees =  ($_tuition_fee[0]->fees * .035) + $_tuition_fee[0]->fees;
                return $_total_fees / 5;
            }
        } else {
            // Full-Payment 
            // Get the Payment
            $_tuition_fee =  $this->hasMany(SemestralFee::class, 'course_semestral_fee_id')
                ->selectRaw("sum(pf.particular_amount) as fees")
                ->join('particular_fees as pf', 'semestral_fees.particular_fee_id', 'pf.id')
                ->where('semestral_fees.is_removed', false)->get();
            return 0;
        }
    }
    public function total_tuition_fee($_data)
    {
        if ($_data->course_id == 3) {
            # Sum all Particulars   
            $_tuition_fee =  $this->hasMany(SemestralFee::class, 'course_semestral_fee_id')
                ->selectRaw("sum(pf.particular_amount) as fees")
                ->join('particular_fees as pf', 'semestral_fees.particular_fee_id', 'pf.id')
                ->where('semestral_fees.is_removed', false)->get();
            $_tuition_fee = $_tuition_fee[0]->fees;
        } else {
            //Get Semestral Fees
            // Get Unit First 
            $_number_of_units = $_data->course->units($_data)->units;
            $_miscellaneous = SemestralFee::select('p.particular_tag')
                ->selectRaw("sum(pf.particular_amount) as fees")
                ->join('particular_fees as pf', 'pf.id', 'semestral_fees.particular_fee_id')
                ->join('particulars as p', 'p.id', 'pf.particular_id')
                ->where('semestral_fees.course_semestral_fee_id', $_data->id)
                ->where('p.particular_tag', '!=', 'tuition_tags')
                ->get();

            $_tuition = SemestralFee::select('p.particular_tag')
                ->selectRaw("sum(pf.particular_amount) as fees")
                ->join('particular_fees as pf', 'pf.id', 'semestral_fees.particular_fee_id')
                ->join('particulars as p', 'p.id', 'pf.particular_id')
                ->where('semestral_fees.course_semestral_fee_id', $_data->id)
                ->where('p.particular_tag', 'tuition_tags')
                ->get();


            $_tuition_fee = ($_tuition[0]->fees * $_number_of_units) + $_miscellaneous[0]->fees;
            //$_tuition_fee = $_number_of_units;
        }
        return $_tuition_fee;
    }
    public function installment_fee($_course_fee, $_amount)
    {
        return $_course_fee->course_id == 3 ? ($_amount + 710) : ($_amount + ($_amount * 0.035));
    }
}
