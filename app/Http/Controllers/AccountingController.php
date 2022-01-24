<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class AccountingController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('accounting');
    }
    public function index()
    {
        return view('pages.accounting.dashboard.view');
    }
}
