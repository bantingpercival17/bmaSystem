<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StaffPictures extends Model
{
    use HasFactory;
    protected $fillable = ['staff_id','image_path','is_actived','is_removed'];
}
