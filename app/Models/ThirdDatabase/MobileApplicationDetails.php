<?php

namespace App\Models\ThirdDatabase;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MobileApplicationDetails extends Model
{
    use HasFactory;
    protected $fillable = ['app_name', 'description', 'app_logo_path', 'is_removed'];

    function latest_version()
    {
        return $this->hasOne(MobileApplicationVersions::class, 'app_id')->orderBy('id', 'desc');
    }
    function version_list()
    {
        return $this->hasMany(MobileApplicationVersions::class, 'app_id')->orderBy('id', 'desc');
    }
    function downloads()
    {
        return $this->hasMany(MobileApplicationDonwloads::class, 'app_id');
    }
}
