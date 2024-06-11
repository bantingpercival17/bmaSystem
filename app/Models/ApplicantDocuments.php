<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class ApplicantDocuments extends Model
{
    use HasFactory;
    protected $connection = 'mysql2';
    protected $fillable = [
        'applicant_id',
        'document_id',
        'file_links',
        'feedback',
        'is_approved',
        'staff_id',
        'is_removed'
    ];
    public function document()
    {
        return $this->belongsTo(Documents::class, 'document_id');
    }
    public function account()
    {
        return $this->belongsTo(ApplicantAccount::class, 'applicant_id');
    }
    public function staff()
    {
        return $this->belongsTo(Staff::class, 'staff_id');
    }
    function documentsV2()
    {
        return $this->belongsTo(Documents::class, 'document_id');
    }
    public function scopeWithDocuments($query)
    {
        $databaseName = env('DB_DATABASE');
        $documentsTable = "{$databaseName}.documents";

        return $query->join(DB::raw($documentsTable), function ($join) {
            $join->on('documents.id', '=', 'applicant_documents.documents_id');
        })
            ->where('documents.is_removed', false)
            ->where('applicant_documents.is_removed', false);
    }
}
