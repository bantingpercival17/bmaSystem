@extends('layouts.app-main')
@php
$_title = 'Setting';
@endphp
@section('page-title', $_title)
@section('beardcrumb-content')
    <li class="breadcrumb-item active" aria-current="page">
        <svg width="14" height="14" class="me-2" viewBox="0 0 22 22" fill="none"
            xmlns="http://www.w3.org/2000/svg">
            <path
                d="M8.15722 19.7714V16.7047C8.1572 15.9246 8.79312 15.2908 9.58101 15.2856H12.4671C13.2587 15.2856 13.9005 15.9209 13.9005 16.7047V16.7047V19.7809C13.9003 20.4432 14.4343 20.9845 15.103 21H17.0271C18.9451 21 20.5 19.4607 20.5 17.5618V17.5618V8.83784C20.4898 8.09083 20.1355 7.38935 19.538 6.93303L12.9577 1.6853C11.8049 0.771566 10.1662 0.771566 9.01342 1.6853L2.46203 6.94256C1.86226 7.39702 1.50739 8.09967 1.5 8.84736V17.5618C1.5 19.4607 3.05488 21 4.97291 21H6.89696C7.58235 21 8.13797 20.4499 8.13797 19.7714V19.7714"
                stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"></path>
        </svg>{{ $_title }}
    </li>
@endsection
@section('page-content')
    <div class="row">
        <div class="col-md-5">
            <label for="" class="text-primary h4">ROLES</label>
            <div class="card">
                <div class="card-header">
                    <form action="{{ route('setting.store-role') }}" method="post">
                        @csrf
                        <div class="form-group">
                            <label for="" class="form-label">Role name</label>
                            <input type="text" class="form-control" name="_role_name">
                        </div>
                    </form>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th><b>Role Name</b></th>
                                    <th><b>Action</b></th>
                                </tr>
                            </thead>
                            <tbody>

                                @if (count($_roles) > 0)
                                    @foreach ($_roles as $_data)

                                        <tr>
                                            <th>
                                                <b class="text-primary">
                                                    {{ $_data->display_name }}
                                                </b>
                                            <td></td>
                                        </tr>
                                    @endforeach
                                @else
                                    <tr>
                                        <th colspan="4" class="text-center text-muted"> Empty Roles</th>
                                    </tr>
                                @endif
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-7">
            <label for="" class="text-primary h4">Academics</label>
            <div class="card">
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th><b>Semester</b></th>
                                    <th><b>School Year</b></th>
                                </tr>
                            </thead>
                            <tbody>

                                @if (count($_academic) > 0)
                                    @foreach ($_academic as $_data)

                                        <tr>
                                            <th>
                                                <b class="text-primary">
                                                    {{ $_data->semester }}
                                                </b>
                                            <td> {{ $_data->school_year }}</td>
                                        </tr>
                                    @endforeach
                                @else
                                    <tr>
                                        <th colspan="4" class="text-center text-muted"> Empty Roles</th>
                                    </tr>
                                @endif
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
