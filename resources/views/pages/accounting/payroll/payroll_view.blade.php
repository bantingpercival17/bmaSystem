@extends('layouts.app-main')
@section('page-title', 'Payroll Cut-off')
@section('beardcrumb-content')
    <li class="breadcrumb-item">
        <a href="">
            <svg width="14" height="14" class="me-2" viewBox="0 0 22 22" fill="none"
                xmlns="http://www.w3.org/2000/svg">
                <path
                    d="M8.15722 19.7714V16.7047C8.1572 15.9246 8.79312 15.2908 9.58101 15.2856H12.4671C13.2587 15.2856 13.9005 15.9209 13.9005 16.7047V16.7047V19.7809C13.9003 20.4432 14.4343 20.9845 15.103 21H17.0271C18.9451 21 20.5 19.4607 20.5 17.5618V17.5618V8.83784C20.4898 8.09083 20.1355 7.38935 19.538 6.93303L12.9577 1.6853C11.8049 0.771566 10.1662 0.771566 9.01342 1.6853L2.46203 6.94256C1.86226 7.39702 1.50739 8.09967 1.5 8.84736V17.5618C1.5 19.4607 3.05488 21 4.97291 21H6.89696C7.58235 21 8.13797 20.4499 8.13797 19.7714V19.7714"
                    stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"></path>
            </svg>Employees List
        </a>
    </li>
    <li class="breadcrumb-item active" aria-current="page">
        Payroll Cut-off
    </li>
@endsection
@section('page-content')
    <div class="card">
        <div class="card-header d-flex justify-content-between">

            <div class="header-title">
                <h4 class="card-title">{{ date('F Y', strtotime($_payroll->cut_off)) }}</h4>
                <small class="fw-bolder">{{ strtoupper(str_replace('-', ' ', $_payroll->period)) }}</small>
            </div>
            <div class="float-end">
                <a href="{{ route('accounting.salary-details-template') }}" class="btn btn-sm btn-info text-white">Payroll
                    without Deduction</a>
                <a href="{{ route('accounting.salary-details-template') }}" class="btn btn-sm btn-info text-white">Payroll
                    with Deduction</a>
                <button type="button" class="btn btn-primary btn-sm btn-form-document" data-bs-toggle="modal"
                    data-bs-target=".document-view-modal">
                    Upload Salary Details</button>
            </div>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive mt-4">
                <table id="basic-table" class="table table-striped mb-0" role="grid">
                    <thead>
                        <tr>
                            <th>EMPLOYEE'S NAME</th>
                            <th>DEPARTMENT</th>
                            <th>JOB DESCRIPTION</th>
                            <th>SALARY DETAILS</th>
                        </tr>
                    </thead>
                    <tbody>

                        {{-- @if (count($_employees) > 0)
                            @foreach ($_employees as $_employee)
                                <tr>
                                    <td>{{ $_employee->last_name . ', ' . trim(str_replace(['2/m', 'C/e', '2/o', '3/e', 'Engr.', 'Capt.', 'C/m'], '', $_employee->first_name)) }}
                                    </td>
                                    <td>{{ $_employee->department }}</td>
                                    <td>{{ strtoupper($_employee->job_description) }}</td>
                                    <td>
                                        @if ($_employee->salary_details)
                                            salary Details
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        @else
                            <tr>
                                <td colspan="2">No Data</td>
                            </tr>
                        @endif --}}
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <div class="modal fade document-view-modal" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-xs">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel1">Upload Employee Salary Details</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">
                    </button>
                </div>
                <div class="modal-body">
                    <form action="{{ route('accounting.employees-upload-salary-details') }}" method="post"
                        enctype="multipart/form-data">
                        @csrf
                        <div class="form-group">
                            <input type="file" name="_file" id="" class="form-control">
                        </div>
                        <div class="form-group">
                            <button type="submit" class="btn btn-sm btn-primary">Upload</button>
                        </div>
                    </form>
                </div>
            </div>

        </div>
    </div>
@endsection
