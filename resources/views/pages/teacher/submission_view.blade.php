@extends('app')
@section('page-title', 'Grade Submission')
@section('page-navigation')
    <ol class="breadcrumb ">
        <li class="breadcrumb-item active">Grade Submission</li>
        <li class="breadcrumb-item active"> FORM
            {{ strtoupper(request()->input('_form') . ' ' . request()->input('_period')) }}</li>
    </ol>
@endsection
@section('page-content')
    <div class="row">
        @foreach ($_academics as $_academic)
            <div class="col">
                <a
                    href="/teacher/grade-reports?_form={{ request()->input('_form') }}&_period={{ request()->input('_period') }}&_academic={{ Crypt::encrypt($_academic->id) }}">
                    <div class="card card-primary ">
                        <div class="card-body box-profile">
                            <div>
                                <h4
                                    class="text-{{ Crypt::decrypt(request()->input('_academic')) == $_academic->id ? 'success' : 'info' }}">
                                    {{ $_academic->semester . ' | ' . $_academic->school_year }}
                                </h4>
                            </div>
                            <p class="text-muted">
                                <b>
                                    {{ $_academic->is_active == 1 ? 'CURRENT ACADEMIC YEAR' : 'OLD ACADEMIC YEAR' }}
                                </b>
                            </p>

                        </div>
                    </div>
                </a>

            </div>
        @endforeach
    </div>
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title"><b>INSTRUCTION LIST</b></h3>
                </div>
                <div class="card-body table-responsive p-0">
                    <table class="table table-head-fixed text-nowrap">
                        <thead>
                            <tr class="text-center text-muted">
                                <th style="width: 300px" rowspan="2">INSTRUCTOR NAME</th>
                                <th>SUBJECTS
                                    <br>
                                    <span class="badge badge-secondary">FORM NOT SUBMITTED</span>
                                    <span class="badge badge-info">FORM SUBMITTED</span>
                                    <span class="badge badge-success">FORM APPRROVED</span>
                                    <span class="badge badge-danger">FORM DISAPPRROVED</span>
                                </th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($_staffs as $_staff)
                                <tr>
                                    <td class="text-center">
                                        @php
                                            if (file_exists(public_path('assets/img/staff/' . strtolower(str_replace(' ', '_', $_staff->user->name)) . '.jpg'))) {
                                                $_image = strtolower(str_replace(' ', '_', $_staff->user->name)) . '.jpg';
                                            } else {
                                                $_image = 'avatar.png';
                                            }
                                        @endphp
                                        <img src="{{ asset('/assets/img/staff/' . $_image) }}" alt="user-avatar"
                                            class="img-circle img-fluid" width="150">
                                        <h3 class="text-muted h4">
                                            <b>
                                                {{ strtoupper($_staff->first_name . ' ' . $_staff->last_name) }}
                                            </b>
                                        </h3>
                                    </td>
                                    <td>

                                        @if ($_staff->subjects_handles->count() > 0)
                                            @php
                                                $_row = 0;
                                            @endphp
                                            @foreach ($_staff->subjects_handles as $_subject)
                                                @php
                                                    $_link = '/teacher/grade-reports/instructor?_form=' . request()->input('_form') . '&_period=' . request()->input('_period') . '&_academic=' . request()->input('_academic') . '&_subject=' . Crypt::encrypt($_subject->id);
                                                    if ($_subject_style = $_subject->submitted_grade(request()->input('_form'), request()->input('_period'))) {
                                                        $_status = $_subject_style->is_approved === 1 ? ['success', 'fa-thumbs-up'] : ($_subject_style->is_approved === 0 ? ['danger', 'fa-thumbs-down'] : ['info', 'fa-eye']);
                                                    } else {
                                                        $_status = ['secondary', 'fa-eye-slash'];
                                                    }
                                                    $_row += 1;
                                                @endphp
                                                <a href="{{ $_link }}" class="btn btn-app bg-{{ $_status[0] }}">
                                                    <i class="fas {{ $_status[1] }}"></i>
                                                    {{ $_subject->curriculum_subject->subject->subject_code . ' - ' . $_subject->section->section_name }}
                                                </a>
                                                @if ($_row <= 3)

                                                @else
                                                    @php
                                                        $_row = 0;
                                                    @endphp
                                                    <br>
                                                @endif
                                            @endforeach

                                        @else
                                            <p class="text-muted h4"><b>No Subject Handle</b></p>
                                        @endif

                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection
