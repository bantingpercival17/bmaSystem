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
    <div class="card">
        <div class="card-header">
            <span class="h4 fw-bolder">DOCUMENTS</span>
            <form action="{{ route('admin.store-documents') }}" method="post">
                @csrf
                <div class="row">
                    <div class="col-4">
                        <div class="form-group">
                            <label for="" class="form-label">DOCUMENT NAME</label>
                            <input type="text" class="form-control" name="document_name"
                                value="{{ old('document_name') }}">
                            @error('document_name')
                                <span class="badge bg-danger">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>
                    <div class="col-4">
                        <div class="form-group">
                            <label for="" class="form-label">DOCUMENT DESCRIPTION</label>
                            <input type="text" class="form-control" name="document_details"
                                value="{{ old('document_details') }}">
                            @error('document_details')
                                <span class="badge bg-danger">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>
                    <div class="col-4">
                        <div class="form-group">
                            <label for="" class="form-label">DOCUMENT PROPOSE</label>
                            <input type="text" class="form-control" name="document_propose"
                                value="{{ old('document_propose') }}">
                            @error('document_propose')
                                <span class="badge bg-danger">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-6">
                        <div class="form-group">
                            <label for="" class="form-label">DEPARTMENT NEED</label>
                            <select name="department" class="form-select">
                                @foreach ($_department as $item)
                                    <option value="{{ $item->id }}">{{ $item->name }}</option>
                                @endforeach
                            </select>
                            @error('department')
                                <span class="badge bg-danger">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>
                    <div class="col-4">
                        <div class="form-group">
                            <label for="" class="form-label">YEAR LEVEL</label>
                            <select name="year_level" class="form-select">
                                <option value="11">Grade 11</option>
                                <option value="12">Grade 12</option>
                                <option value="1">1st Class</option>
                                <option value="2">2nd Class</option>
                                <option value="3">3rd Class</option>
                                <option value="4">4th Class</option>
                            </select>
                            @error('year_level')
                                <span class="badge bg-danger">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>
                    <div class="col">
                        <div class="form-group">
                            <button type="submit" class="btn btn-primary mt-5 w-100">CREATE</button>
                        </div>
                    </div>
                </div>
            </form>
        </div>
        <div class="card-body">
            <div class="table-responsive mt-4">
                @include('layouts.icon-main')
                <table id="basic-table" class="table table-striped mb-0" role="grid">
                    <thead>
                        <tr>
                            <th>Document Name</th>
                            <th>Documents Details</th>
                            <th>Document Propose</th>
                            <th>Year Level</th>
                            <th>Department</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($_documents as $document)
                            <tr>
                                <td>
                                    {{ $document->document_name }}
                                </td>
                                <td>
                                    {{ $document->document_details }}
                                </td>
                                <td>
                                    {{ $document->document_propose }}
                                </td>
                                <td>
                                    {{ $document->year_level }}
                                </td>
                                <td>
                                    {{ $document->department->name }}
                                </td>
                                <td>
                                    {{ $document->document_name }}
                                </td>

                            </tr>
                        @endforeach

                    </tbody>
                </table>
            </div>
        </div>
    </div>
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
                <div class="card-header">
                    <form action="{{ route('setting.store-academic') }}" method="post">
                        @csrf
                        <div class="form-group">
                            <label for="" class="form-label">School Year</label>
                            <input type="text" class="form-control" name="_school_year" placeholder="ex. 2021-2022">
                        </div>
                        <div class="form-group">
                            <label for="" class="form-label">Semester</label>
                            <select name="semester" id="" class="form-select">
                                <option value="First Semester">First Semester</option>
                                <option value="Second Semester">Second Semester</option>
                            </select>

                        </div>
                    </form>
                </div>
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


            <div class="card">
                <div class="card-header">
                    TERMS & AGREEMENT FOR STUDENT HANDBOOK REPORT
                </div>
                <div class="card-body">
                    @if (count($_academic) > 0)
                        @foreach ($_academic as $_data)
                            <div class="row mt-3">
                                <div class="col">
                                    <b class="text-primary">
                                        {{ $_data->semester }}
                                    </b>
                                    {{ $_data->school_year }}
                                </div>
                                <div class="col">
                                    <a class="btn btn-sm btn-info text-white"
                                        href="{{ route('admin.student-handbook-logs') }}?_academic={{ base64_encode($_data->id) }}">view
                                        logs</a>
                                </div>
                            </div>
                        @endforeach
                    @else
                        <p>SCHOOL YEAR</p>
                    @endif
                </div>
            </div>
        </div>
    </div>
@endsection
