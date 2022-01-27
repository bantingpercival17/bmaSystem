@extends('layouts.app-main')
@php
$_title = 'Particular';
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
        <div class="card">
            <div class="card-header d-flex justify-content-between">
                <div class="header-title">
                    <h4 class="card-title">Create Particular</h4>
                </div>
            </div>
            <div class="card-body">
                <form action="{{ route('accounting.create-particular') }}" method="post">
                    @csrf
                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="" class="form-label">Particular Name</label>
                                <input type="text" name="_name" class="form-control" value="{{ old('_name') }}">
                                @error('_name')
                                    <label for="" class="text-danger">{{ $message }}</label>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="" class="form-label">Particular Type</label>
                                <select name="_type" class="form-select">
                                    <option value="tuition_type">Tuition Fee</option>
                                    <option value="additional_type">Additional Fee</option>
                                </select>
                                @error('_type')
                                    <label for="" class="text-danger">{{ $message }}</label>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="" class="form-label">Particular Tagging</label>
                                <select name="_tag" class="form-select">
                                    <option value="tuition_tags">Tuition Tag</option>
                                    <option value="miscellaneous_tags">Miscellaneous Fee</option>
                                    <option value="other_tags">Others Fee</option>
                                    <option value="addition_tags">Additional Fee</option>
                                </select>
                                @error('_tag')
                                    <label for="" class="text-danger">{{ $message }}</label>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="form-group">
                                <label for="" class="form-label">Department</label>
                                <select name="_department" class="form-select">
                                    <option value="senior_high">Senior High School</option>
                                    <option value="college">College</option>
                                </select>
                                @error('_department')
                                    <label for="" class="text-danger">{{ $message }}</label>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="form-group">
                                <button type="submit" class="btn btn-primary w-100">Create Particular</button>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
        <div class="card">
            <div class="card-header d-flex justify-content-between">
                <div class="header-title">
                    <h4 class="card-title">Set Up Particular Fees</h4>
                </div>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        <a href="{{ route('accounting.particular-fee-view') }}?_department=senior_high"
                            class="btn btn-primary w-100">Senior High Department</a>
                    </div>
                    <div class="col-md-6">
                        <a href="{{ route('accounting.particular-fee-view') }}?_department=college"
                            class="btn btn-primary w-100">College Department</a>
                    </div>
                </div>
            </div>
        </div>
        <div class="card">
            <div class="card-header d-flex justify-content-between">
                <div class="header-title">
                    <h4 class="card-title">Particular List</h4>
                </div>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-striped" id="datatable" data-toggle="data-table">
                        <thead>
                            <tr>
                                <th>Particular Name</th>
                                <th>Department</th>
                            </tr>
                        </thead>
                        <tbody>
                            @if (count($_particulars))
                                @foreach ($_particulars as $item)
                                    <tr>
                                        <td>{{ $item->particular_name }}</td>
                                        <td>{{ ucwords(str_replace('_', ' ', $item->department)) }}</td>
                                    </tr>
                                @endforeach
                            @endif
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

    </div>
@endsection
