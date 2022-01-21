<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class DepartmentHeadController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('department-head');
    }
}
