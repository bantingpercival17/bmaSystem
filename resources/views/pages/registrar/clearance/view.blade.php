@extends('layouts.app-main')
@php
$_title = 'Semestral Clearance';
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
    <div class=" mt-6 py-0">
        <form action="{{ route('registrar.e-clearance') }}" method="post">
            @csrf
            <div class="card">
                <div class="card-header d-flex justify-content-between">
                    <div class="header-title">
                        <h4 class="card-title">E-Clearance</h4>
                    </div>
                    <div class="card-tool">
                        <div class="form-check d-block">
                            <input class="form-check-input input-select" data-check="subject-clearance" type="checkbox"
                                id="flexCheckChecked-4">
                            <label class="form-check-label" for="flexCheckChecked-4">
                                Select All
                            </label>
                        </div>
                        <input type="hidden" name="_clearance_data" value="registrar">
                        <button type="submit" class="btn btn-primary">SUBMIT</button>
                    </div>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-striped" id="datatable" data-toggle="data-table">
                            <thead>
                                <tr>
                                    <th>Student Number</th>
                                    <th>Midshipman Name</th>
                                    <th>Section</th>
                                    <th>Clearance Status</th>
                                    <th>Comment</th>
                                </tr>
                            </thead>
                            <tbody>
                                @if (count($_enrollment))
                                    @foreach ($_enrollment as $_key => $_data)
                                        <tr>
                                            <td>{{ $_data->student->account ? $_data->student->account->student_number : '-' }}
                                            </td>
                                            <td>{{ strtoupper($_data->student->last_name . ', ' . $_data->student->first_name) }}
                                            </td>
                                            <td>
                                                {{ $_data->student->section(Auth::user()->staff->current_academic()->id)->first() ? $_data->student->section(Auth::user()->staff->current_academic()->id)->first()->section->section_name : '-' }}
                                            </td>
                                            <td>
                                                <div class="form-check d-block">
                                                    <input class="form-check-input input-select-subject-clearance"
                                                        type="checkbox" id="flexCheckChecked-3-{{ $_key }}"
                                                        name="data[{{ $_key }}][e_clearance]"
                                                        value="{{ $_data->student->id }}"
                                                        {{ $_data->student->non_academic_clearance('registrar') ? ($_data->student->non_academic_clearance('registrar')->is_approved == 1 ? 'checked' : '') : '' }}>
                                                    <label class="form-check-label"
                                                        for="flexCheckChecked-3-{{ $_key }}">
                                                        CLEARED
                                                    </label>
                                                </div>
                                            </td>
                                            <td>
                                                <div class="form-group">
                                                    <label for="" class="text-muted"><small><b>COMMENT</b></small>
                                                    </label>
                                                    <input type="text" class="form-control"
                                                        name="data[{{ $_key }}][comment]"
                                                        value="{{-- {{ $_data->student->clearance('registrar') ? $_data->student->clearance('registrar')->comments : '' }} --}}">
                                                    <input type="hidden" name="data[{{ $_key }}][sId]"
                                                        value="{{ base64_encode($_data->student->id) }}">
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                @else
                                    <tr>
                                        <th colspan="3">No Data</th>
                                    </tr>
                                @endif
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </form>

    </div>

@endsection
