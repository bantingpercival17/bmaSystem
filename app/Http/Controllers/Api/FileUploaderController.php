<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\URL;

class FileUploaderController extends Controller
{
    
    public function upload_encrypt_files($_file, $_path = 'public', $_folder = 'extra')
    {
        if (!$_file) {
            return null;
        }
        // Get Student Number
        $_student_number = auth()->user() ? str_replace('@bma.edu.ph', '', trim(auth()->user()->email)) : str_replace('@gmail.com', '', trim(auth()->user()->personal_email));
        // Get the extention of files
        $filename = $_student_number . '/' . $_folder . '/' . time() . '.' . $_file->getClientOriginalExtension();
        // File Path Format : $_path.'/'.student-number.'/'.$_folder
        $_path = $_path;
        Storage::disk($_path)->put($filename, fopen($_file, 'r+'));
        return URL::to('/') . '/storage/' . $_path . '/' . $filename;
    }
}
