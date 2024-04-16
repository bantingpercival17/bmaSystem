<?php

namespace App\Models\ThirdDatabase;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MobileApplicationVersions extends Model
{
    use HasFactory;
    protected $fillable = ['app_id', 'version_name', 'description', 'app_path', 'is_removed'];
    function mobile_app()
    {
        return $this->belongsTo(MobileApplicationDetails::class, 'app_id');
    }
    function version_downloads()
    {
        return $this->hasMany(MobileApplicationDonwloads::class, 'version_id');
    }
}
