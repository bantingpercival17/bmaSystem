@extends('app')
@section('page-title', 'Employees')
@section('page-navigation')
    <ol class="breadcrumb ">
        <li class="breadcrumb-item"><a href="/">Dashboard</a></li>
        <li class="breadcrumb-item active">Employees</li>

    </ol>
@endsection
@section('page-content')
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title"><span class="text-muted"><b>List of Employees</b></span></h3>
                    <div class="card-tools">


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
                                                    <b>{{ strtoupper($_data->first_name . ' ' . $_data->last_name) }}</b>
                                                </h2>
                                                <p class="h6">{{ $_data->job_description }}</p>
                                                <p class="text-muted text-sm">
                                                    <b>Email</b>
                                                    <br>
                                                    {{ $_data->user->email }}
                                                </p>
                                            </div>
                                            <div class="col-5 text-center">
                                                <img src="{{ $_data->profile_picture() }}" alt="user-avatar"
                                                    class="img-circle img-fluid">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="card-footer">
                                        <a href="/administrative/employees/view?_e={{ base64_encode($_data->id) }}"
                                            class="btn btn-sm btn-primary">
                                            <i class="fas fa-user"></i> View Profile
                                        </a>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>


                </div>
            </div>
        </div>
    </div>
@endsection
