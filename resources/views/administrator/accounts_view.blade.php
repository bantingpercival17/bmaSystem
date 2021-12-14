@extends('app')
@section('page-title', 'Accounts')
@section('css')
    <!-- Toastr -->
    <link rel="stylesheet" href="{{ asset('assets/plugins/toastr/toastr.min.css') }}">
@endsection
@section('page-content')
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title"><span class="text-muted"><b>ADD STUDENTS</b></span></h3>
                    <div class="card-tools">
                        <form action="/administrator/accounts" method="post" enctype="multipart/form-data">
                            @csrf
                            <div class="input-group input-group-sm">
                                <div class="custom-file">
                                    <input type="file" class="custom-file-input" id="customFile" name="_file" required>
                                    <label class="custom-file-label" for="customFile">Choose file</label>
                                </div>
                                <div class="input-group-append">
                                    <button class="btn btn-info btn-sm" type="submit">UPLOAD</button>
                                </div>
                                <div class="input-group-append">
                                    <button class="btn btn-success btn-sm" data-toggle="modal" data-target="#modal-lg"
                                        type="button">
                                        <i class="fa fa-users"></i> Add Accounts </button>
                                </div>
                            </div>
                        </form>

                    </div>
                </div>
                <div class="card-body">
                    <div class="row">
                        @foreach ($_employees as $_data)
                            <div class="col-12 col-sm-6 col-md-4 d-flex align-items-stretch flex-column">
                                <div class="card bg-light d-flex flex-fill">
                                    <div class="card-header text-muted border-bottom-0">
                                        {{ $_data ? $_data->department : '-' }}
                                    </div>
                                    <div class="card-body pt-0">
                                        <div class="row">
                                            <div class="col-7">
                                                <h2 class="lead">
                                                    <b>{{ $_data->first_name . ' ' . $_data->last_name }}</b>
                                                </h2>
                                                <p class="h6">{{ $_data->job_description }}</p>
                                                <p class="text-muted text-sm"><b>Role</b>
                                                    <br>
                                                    @foreach ($_data->user->roles as $role)
                                                        <span class="badge badge-success">{{ $role->display_name }}</span>
                                                    @endforeach
                                                </p>
                                                <ul class="ml-4 mb-0 fa-ul text-muted">
                                                    <li class="small">
                                                        Email: <b>{{ $_data->user->email }}</b>
                                                    </li>
                                                </ul>
                                            </div>
                                            <div class="col-5 text-center">
                                                @php
                                                    if (file_exists(public_path('assets/img/staff/' . strtolower(str_replace(' ', '_', $_data->user->name)) . '.jpg'))) {
                                                        $_image = strtolower(str_replace(' ', '_', $_data->user->name)) . '.jpg';
                                                    } else {
                                                        $_image = 'avatar.png';
                                                    }
                                                @endphp
                                                <img src="{{ asset('/assets/img/staff/' . $_image) }}" alt="user-avatar"
                                                    class="img-circle img-fluid">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="card-footer">
                                        <div class="text-right">

                                            <a href="/administrator/qr-code/{{ $_data->id }}"
                                                class="btn btn-sm btn-secondary"><i class="fas fa-qrcode"></i></a>
                                            <button class="btn btn-sm btn-info"><i class="fa fa-unlock"></i> </button>
                                            <a href="/administrator/accounts/view?_e={{ Crypt::encrypt($_data->id) }}"
                                                class="btn btn-sm btn-primary">
                                                <i class="fas fa-user"></i> View Profile
                                            </a>
                                        </div>
                                        <p></p>
                                        <form action="/administrator/accounts/profile-picture" method="post"
                                            enctype="multipart/form-data">
                                            @csrf
                                            <div class="input-group input-group-sm">
                                                <input type="hidden" name="_id" value="{{ $_data->id }}">
                                                <div class="custom-file">
                                                    <input type="file" class="custom-file-input" id="customFile"
                                                        name="_file" required>
                                                    <label class="custom-file-label" for="customFile">Choose
                                                        file</label>
                                                </div>
                                                <div class="input-group-append">
                                                    <button class="btn btn-info btn-sm" type="submit"><i
                                                            class="fa fa-upload"></i></button>
                                                </div>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>


                </div>
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
            <!-- /.modal-content -->
        </div>
        <!-- /.modal-dialog -->
    </div>
@endsection
