<?php

use App\Http\Livewire\EmployeeView;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth'])->group(function () {
    Route::get('/employee', EmployeeView::class)->name('employee.view');
});
