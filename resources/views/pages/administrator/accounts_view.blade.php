@extends('layouts.app-main')
@section('page-title', 'Accounts')
@section('page-content')
<div class="card">
    <div class="card-header d-flex justify-content-between">
        <div class="header-title">
            <h4 class="card-title">Add Epmloyees</h4>
        </div>
        <div class="card-tools">
            <form action="/administrator/accounts" method="post" enctype="multipart/form-data">
                @csrf
                <div class="row">
                    <div class="col-md">
                        <div class="form-group">
                            <input class="form-control" type="file" id="customFile1" name="_file">
                        </div>
                    </div>
                    <div class="col-md-3">
                        <button class="btn btn-info text-white" type="submit">UPLOAD</button>
                    </div>
                    <div class="col-md-4">
                        <a href="{{route('admin.add-employee')}}" class="btn btn-primary btn-sm">ADD EMPLOYEE</a>
                        <!--  <button type="button" class="btn btn-primary btn-sm mt-2" data-bs-toggle="modal" data-bs-target="#add-employee">
                            Add Employee
                        </button> -->
                        <a href="{{ route('export-employee') }}" class="btn btn-info btn-sm">EXPORT</a>
                    </div>
                </div>

            </form>

        </div>
    </div>
    <div class="card-body p-0">
        <div class="table-responsive mt-4">
            <table id="datatable" class="table table-striped" data-toggle="data-table">
                <thead>
                    <tr>

                        <th>Employee Name</th>
                        <th>Department</th>
                        <th>Email</th>
                        <th>Roles</th>
                        <th>Password Reset</th>
                        <th>Account Status</th>
                    </tr>
                </thead>
                <tbody>
                    @if (count($_employees) > 0)
                    @foreach ($_employees as $_data)
                    <tr>

                        <td>
                            <div class="d-flex align-items-center">
                                <img class=" avatar-rounded img-fluid avatar-45 me-3 bg-soft-primary" src="{{ asset($_data->profile_pic($_data)) }}" alt="profile">

                                <a href="{{ route('employee.view') }}?employee={{ base64_encode($_data->id) }}">
                                    {{ strtoupper($_data->first_name . ' ' . $_data->last_name) }}
                                </a>

                            </div>
                        </td>
                        <td>
                            {{ $_data ? $_data->department : '-' }}
                        </td>
                        <td>
                            {{ $_data ? $_data->user->email : '-' }}
                        </td>
                        <td>
                            @foreach ($_data->user->roles as $role)
                            <span class="mt-2 badge bg-primary">{{ $role->display_name }}</span>
                            @endforeach
                        </td>
                        <td>
                            <form action="{{ route('admin.reset-password') }}" method="post">
                                @csrf
                                <input type="hidden" name="_employee" value="{{ base64_encode($_data->id) }}">
                                <div class="form-group">
                                    <button type="submit" class="btn btn-primary">Reset Password</button>
                                </div>

                            </form>
                        </td>
                        <td>
                            @if ($_data->is_removed == true)
                            <a href="{{ route('admin.deactive-account') }}?staff={{ base64_encode($_data->id) }}&status=active" class="btn btn-primary btn-sm text-white">ACTIVE</a>
                            @else
                            <a href="{{ route('admin.deactive-account') }}?staff={{ base64_encode($_data->id) }}&status=deactive" class="btn btn-danger btn-sm text-white">DEACTIVE</a>
                            @endif

                        </td>
                    </tr>
                    @endforeach
                    @else
                    <tr>
                        <td></td>
                    </tr>
                    @endif

                </tbody>
            </table>
        </div>
    </div>
</div>
<div class="modal fade" id="modal-lg">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Add Account</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form action="/administrator/accounts" method="post">
                    @csrf
                    <div class="row">
                        <div class="form-group col-4">
                            <label for="">Staff No.</label>
                            <input type="text" class="form-control" name="staff_no">
                        </div>
                        <div class="form-group col">
                            <label for="">Email</label>
                            <input type="text" class="form-control" name="email">
                        </div>
                        <div class="form-group col-3">
                            <label for="">Job Description</label>
                            <input type="text" class="form-control" name="job_description">
                        </div>

                    </div>
                    <div class="row">
                        <div class="form-group col">
                            <label for="">First Name</label>
                            <input type="text" class="form-control" name="fname">
                        </div>
                        <div class="form-group col">
                            <label for="">Last Name</label>
                            <input type="text" class="form-control" name="lname">
                        </div>
                        <div class="form-group col">
                            <label for="">Middle Name</label>
                            <input type="text" class="form-control" name="mname">
                        </div>
                    </div>
                    <div class="row">
                        <div class="form-group col">
                            <label for="">Role</label>
                            <select name="role" id="" class="form-control">
                                <option value="-">Select Role</option>
                                @foreach ($_role as $role)
                                <option value="{{ $role->id }}">{{ $role->display_name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group col">
                            <label for="">Department</label>
                            @php
                            $_department = ['ADMINISTRATIVE', 'ICT', 'REGISTRAR', 'ACCOUNTING', 'OBTO', 'ACADEMIC', 'MARINE TRANSPORTATION', 'MARINE ENGINEERING', 'SENIOR HIGH SCHOOL', 'EXO'];
                            @endphp
                            <select name="department" id="" class="form-control">
                                <option value="-">Select Department</option>
                                @foreach ($_department as $dept)
                                <option value="{{ $dept }}">{{ $dept }} DEPARTMENT</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <button class="btn btn-success">Create Account</button>
                </form>
            </div>
            <div class="modal-footer justify-content-between">
            </div>
        </div>
    </div>
</div>
<div class="modal fade  bd-example-modal-lg" id="add-employee" tabindex="-1" role="dialog" aria-labelledby="add-employee-Title" aria-hidden="true">
    <div class="modal-dialog modal-dialog-scrollable modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="add-employee-Title">Add Employee</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">
                </button>
            </div>
            <div class="modal-body">
                <form action="/administrator/accounts" method="post">
                    @csrf
                    <div class="row">
                        <div class="form-group col-4">
                            <label for="">Staff No.</label>
                            <input type="text" class="form-control" name="staff_no">
                        </div>
                        <div class="form-group col">
                            <label for="">Email</label>
                            <input type="text" class="form-control" name="email">
                        </div>
                        <div class="form-group col-3">
                            <label for="">Job Description</label>
                            <input type="text" class="form-control" name="job_description">
                        </div>

                    </div>
                    <div class="row">
                        <div class="form-group col">
                            <label for="">First Name</label>
                            <input type="text" class="form-control" name="fname">
                        </div>
                        <div class="form-group col">
                            <label for="">Last Name</label>
                            <input type="text" class="form-control" name="lname">
                        </div>
                        <div class="form-group col">
                            <label for="">Middle Name</label>
                            <input type="text" class="form-control" name="mname">
                        </div>
                    </div>
                    <div class="row">
                        <div class="form-group col">
                            <label for="">Role</label>
                            <select name="role" id="" class="form-control">
                                <option value="-">Select Role</option>
                                @foreach ($_role as $role)
                                <option value="{{ $role->id }}">{{ $role->display_name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group col">
                            <label for="">Department</label>
                            @php
                            $_department = ['ADMINISTRATIVE', 'ICT', 'REGISTRAR', 'ACCOUNTING', 'OBTO', 'ACADEMIC', 'MARINE TRANSPORTATION', 'MARINE ENGINEERING', 'SENIOR HIGH SCHOOL', 'EXO', 'MAINTENANCE'];
                            @endphp
                            <select name="department" id="" class="form-control">
                                <option value="-">Select Department</option>
                                @foreach ($_department as $dept)
                                <option value="{{ $dept }}">{{ $dept }} DEPARTMENT</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <button class="btn btn-success">Create Account</button>
                </form>
            </div>

        </div>
    </div>
</div>
@endsection