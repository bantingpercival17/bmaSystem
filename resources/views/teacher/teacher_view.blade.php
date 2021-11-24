@extends('app')
@section('page-title', $_subject->section->section_name . ' | ' . $_subject->curriculum_subject->subject->subject_code)
@section('page-navigation')
    <ol class="breadcrumb ">
        <li class="breadcrumb-item "><a
                href="/teacher/grade-reports?_form={{ request()->input('_form') }}&_period={{ request()->input('_period') }}">Grade
                Submission</a></li>
        <li class="breadcrumb-item ">
            <a
                href="/teacher/grade-reports?_form={{ request()->input('_form') }}&_period={{ request()->input('_period') }}">
                FORM {{ strtoupper(request()->input('_form') . ' ' . request()->input('_period')) }}
            </a>
        </li>
        <li class="breadcrumb-item active">
            {{ $_subject->curriculum_subject->subject->subject_code }}</li>
    </ol>
@endsection

@section('page-content')
    <div class="card card-outline card-primary">
        <div class="card-body">
            <div class="row">
                <div class="col-md-3 text-center">
                    @php
                        if (file_exists(public_path('assets/img/staff/' . strtolower(str_replace(' ', '_', $_staff->user->name)) . '.jpg'))) {
                            $_image = strtolower(str_replace(' ', '_', $_staff->user->name)) . '.jpg';
                        } else {
                            $_image = 'avatar.png';
                        }
                    @endphp
                    <img src="{{ asset('/assets/img/staff/' . $_image) }}" alt="user-avatar" class="img-circle img-fluid"
                        width="100">
                    <p class="h6 text-muted">
                        <b>
                            {{ strtoupper($_staff->first_name . ' ' . $_staff->last_name) }}
                        </b>
                    </p>
                </div>
                <div class="col-md">
                    <div class="row">
                        <div class="col-md">
                            <label for="" class="text-muted">SUBJECT:</label>
                            <label for=""
                                class="text-info">{{ $_subject->curriculum_subject->subject->subject_code }}</label>
                        </div>
                        <div class="col-md">
                            <label for="" class="text-muted">SECTION:</label>
                            <label for="" class="text-info">{{ $_subject->section->section_name }}</label>
                        </div>
                        <div class="col-md-5">
                            <label for="" class="text-muted">FORM & PERIOD:</label>
                            <label for="" class="text-info"> FORM
                                {{ strtoupper(request()->input('_form') . ' ' . request()->input('_period')) }}</label>
                        </div>
                    </div>
                    <div class="row">

                        <div class="col-md-3">
                            <label for="" class="text-muted">SUBJECTS HANDLED</label>
                            <div class="btn-group">
                                <span class="btn btn-info">
                                    SUBJECTS
                                </span>
                                @if ($_subject_data = $_subject->submitted_grade(request()->input('_form'), request()->input('_period')))
                                    <button type="button" class="btn btn-info dropdown-toggle dropdown-icon"
                                        data-toggle="dropdown">
                                        <span class="sr-only">Toggle Dropdown</span>
                                    </button>
                                    <div class="dropdown-menu" role="menu">
                                        @if ($_staff->subjects_handles->count() > 0)
                                            @foreach ($_staff->subjects_handles as $_subject_1)
                                                @if ($_subject_style = $_subject_1->submitted_grade(request()->input('_form'), request()->input('_period')))
                                                    <a class="dropdown-item text-{{ $_subject_style->is_approved === 1 ? 'success' : ($_subject_style->is_approved === 0 ? 'danger' : 'info') }}"
                                                        href="{{ '/teacher/grade-reports/instructor?_form=' . request()->input('_form') . '&_period=' . request()->input('_period') . '&_academic=' . request()->input('_academic') . '&_subject=' . Crypt::encrypt($_subject_1->id) }}">
                                                        {{ $_subject_1->curriculum_subject->subject->subject_code . ' - ' . $_subject_1->section->section_name }}
                                                    </a>
                                                @else
                                                    <span class="dropdown-item text-secondary">
                                                        {{ $_subject_1->curriculum_subject->subject->subject_code . ' - ' . $_subject_1->section->section_name }}</span>
                                                @endif

                                            @endforeach
                                        @else
                                            <span class="dropdown-item">No Subjects Handled</span>
                                        @endif
                                    </div>
                                @endif
                            </div>
                        </div>
                        <div class="col-md">
                            @if ($_subject_status = $_subject->submitted_grade(request()->input('_form'), request()->input('_period')))

                                @if ($_subject_status->is_approved === 1)
                                    <div class="row">
                                        <div class="col-md">
                                            <label for="" class="text-muted">FORM STATUS : </label>
                                            <label class="text-success">APPROVED </label>
                                        </div>
                                        <div class="col-md">
                                            <form action="/teacher/grade-reports" method="post">
                                                @csrf
                                                <input type="hidden" name="_submission"
                                                    value="{{ Crypt::encrypt($_subject_data->id) }}">
                                                <input type="hidden" name="_status" value="0">
                                                <div class="row">
                                                    <div class="form-group col-md">
                                                        <button type="submit"
                                                            class="btn btn-warning btn-block">REVISION</button>
                                                    </div>
                                                    <input type="hidden" name="_comments" value="For Grade revision.">

                                                </div>
                                            </form>
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="col-md">
                                            <label class="text-muted">VERIFY DATE: </label>
                                            <label
                                                class="text-success">{{ $_subject_status->updated_at->format('M d Y') }}
                                            </label>
                                        </div>
                                        <div class="col-md">
                                            <label class="text-muted">VERIFY BY: </label>
                                            <label class="text-success">{{ strtoupper($_subject_status->approved_by) }}
                                            </label>
                                        </div>
                                    </div>
                                @elseif($_subject_status->is_approved === 0)
                                    <label for="" class="text-muted">FORM STATUS : </label>
                                    <label class="text-danger">DISAPPROVED </label>
                                    <p>
                                        <label class="text-muted">FEEDBACK: </label>
                                        <label class="text-success">{{ $_subject_status->comments }}
                                        </label>
                                    </p>
                                    <div class="row">
                                        <div class="col-md">
                                            <label class="text-muted">VERIFY DATE: </label>
                                            <label
                                                class="text-success">{{ $_subject_status->updated_at->format('M d Y') }}
                                            </label>
                                        </div>
                                        <div class="col-md">
                                            <label class="text-muted">VERIFY BY: </label>
                                            <label class="text-success">{{ strtoupper($_subject_status->approved_by) }}
                                            </label>
                                        </div>
                                    </div>
                                @else
                                    <label for="" class="text-muted">FORM CHECKING</label>
                                    <div class="row">
                                        <form action="/teacher/grade-reports" method="post">
                                            @csrf
                                            <input type="hidden" name="_submission"
                                                value="{{ Crypt::encrypt($_subject_data->id) }}">
                                            <input type="hidden" name="_status" value="1">
                                            <div class="row">
                                                <div class="form-group col-md">
                                                    <button type="submit"
                                                        class="btn btn-success btn-block">APPROVED</button>
                                                </div>
                                                {{-- <div class="col-md">
                                                    <button class="btn btn-danger"></button>
                                                </div> --}}
                                            </div>
                                        </form>
                                        <div class="col-md">
                                            <form action="/teacher/grade-reports" method="post">
                                                @csrf
                                                <input type="hidden" name="_submission"
                                                    value="{{ Crypt::encrypt($_subject_data->id) }}">
                                                <input type="hidden" name="_status" value="0">
                                                <div class="row">
                                                    <div class="form-group col-md">
                                                        <button type="submit"
                                                            class="btn btn-danger btn-block">DISAPPROVED</button>
                                                    </div>
                                                    <div class="form-group col-md-8">
                                                        <textarea name="_comments" id="" cols="40" rows="2"
                                                            required></textarea>
                                                    </div>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                @endif

                            @endif



                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>

    <iframe
        src="/teacher/grade-reports/subject?_s={{ Crypt::encrypt($_subject->id) }}&_form={{ request()->input('_form') }}&_period={{ request()->input('_period') }}"
        width="100%" height="500px">
    </iframe>
@endsection
