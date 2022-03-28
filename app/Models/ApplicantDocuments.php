<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ApplicantDocuments extends Model
{
    use HasFactory;
    protected $connection = 'mysql2';
    public function document()
    {
        return $this->belongsTo(Documents::class, 'document_id');
    }
}
