<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ApplicantNoDocumentNotification extends Model
{
    use HasFactory;
    protected $connection = 'mysql2';
    protected $fillable = [
        'applicant_id',
        'document_id',
        'staff_id',
        'mail_status',
    ];
    public function staff()
    {
        return $this->belongsTo(Staff::class, 'staff_id');
    }
}
