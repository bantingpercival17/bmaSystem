@extends('app')
@section('page-title', $_academic->semester . ' | ' . $_academic->school_year)
@section('page-navigation')
    <ol class="breadcrumb ">
        <li class="breadcrumb-item"><a href="/">Dashboard</a></li>
        <li class="breadcrumb-item"><a href="/administrator/subjects">Curriculum</a></li>
        @if (request()->input('_d'))
            <li class="breadcrumb-item">
                <a href="/administrator/subjects/class?_c={{ Crypt::encrypt($_academic->id) }}">
                    {{ $_academic->semester . ' | ' . $_academic->school_year }}
                </a>
            </li>
            <li class="breadcrumb-item active"> {{ $_course_view->course_name }}</li>
        @else
            <li class="breadcrumb-item active"> {{ $_academic->semester . ' | ' . $_academic->school_year }}</li>
        @endif

    </ol>
@endsection
@php
$_link_department = url()->current() . '?_c=' . request()->input('_c');
$_department = request()->input('_d') ? Crypt::decrypt(request()->input('_d')) : 0;
@endphp
@section('page-content')
    <div class="row">
        @if ($_course->count() > 0)
            @foreach ($_course as $data)
                <div class="col-md">
                    <a href="{{ $_link_department . '&_d=' . Crypt::encrypt($data->id) }}">
                        <div class="card ">
                            <div class="card-body box-profile">
                                <h5
                                    class="{{ request()->input('_d') ? ($_department == $data->id ? 'text-success' : 'text-muted') : 'text-info' }}">
                                    {{ $data->course_name }}
                                </h5>
                                <p class="text-muted ">{{ $data->school_level }}</p>
                            </div>
                        </div>
                    </a>
                </div>
            @endforeach
        @endif
    </div>
    <div class="content">
        @if ($_course_view)
            <label for="" class="text-muted h5">| SUBJECT COURSE</label>
            @foreach ($_curriculum as $curriculum)
                @php
                    $_year_level = $_department == 3 ? [11, 12] : [4, 3, 2, 1];
                @endphp
                <div class="card">
                    <div class="card-header">
                        <label for="" class="h6 text-muted">{{ strtoupper($curriculum->curriculum_name) }}</label>
                    </div>
                    <div class="card-body">
                        @foreach ($_year_level as $_level)
                            @if ($_course_view->course_subject([$curriculum->id, $_level, $_academic->semester])->count() > 0)
                                <div class="card">
                                    <div class="card-header">
                                        <label class="card-title text-muted">
                                            <b>{{ $_department == 3 ? 'GRADE ' . $_level : $_level . ' CLASS' }}
                                            </b>
                                        </label>
                                    </div>
                                    <div class="card-body table-responsive p-0">
                                        <table class="table table-head-fixed text-nowrap">
                                            <thead>
                                                <tr>
                                                    <th>SUBJECT CODE</th>
                                                    <th>SUBJECT DESCRIPTION</th>
                                                    <th>SECTION</th>
                                                    <th>ACTION</th>

                                                </tr>
                                            </thead>
                                            <tbody>
                                                @if ($_subject = $_course_view->course_subject([$curriculum->id, $_level, $_academic->semester]))
                                                    @foreach ($_subject as $_subject)
                                                        <tr data-widget="expandable-table" aria-expanded="false">
                                                            <td>{{ $_subject->subject_code }}</td>
                                                            <td>{{ $_subject->subject_name }}</td>
                                                            <td>
                                                                @if ($_subject->section($_academic->id)->count() > 0)
                                                                    @foreach ($_subject->section($_academic->id)->get() as $_section)
                                                                        <span class="badge badge-info">
                                                                            {{ $_section->section->section_name }} <br>[
                                                                            {{ $_section->staff->first_name . ' ' . $_section->staff->last_name }}]
                                                                        </span>
                                                                    @endforeach
                                                                @else
                                                                    <span class="badge badge-secondary">ADD SECTION</span>
                                                                @endif
                                                            </td>
                                                            <td>
                                                                <button class="btn btn-success">
                                                                    <i class="fa fa-plus"></i>
                                                                </button>
                                                            </td>
                                                        </tr>
                                                        <tr class="expandable-body">
                                                            <td colspan="5">
                                                                <div class="p-0">
                                                                    <table class="table table-hover">
                                                                        <thead>
                                                                            <tr>
                                                                                <th>LEVEL & SECTION</th>
                                                                                <th>TEACHER HANDLED</th>
                                                                                <th>ACTION</th>
                                                                            </tr>
                                                                        </thead>
                                                                        <tbody>

                                                                            @if ($_subject->section($_academic->id)->count() > 0)
                                                                                @foreach ($_subject->section($_academic->id)->get() as $_section)
                                                                                    <tr>
                                                                                        <td class="text-muted">
                                                                                            {{ $_section->id }}
                                                                                            {{ $_section->section->section_name }}
                                                                                        </td>
                                                                                        <td class="text-muted">
                                                                                            {{ $_section->staff->first_name . ' ' . $_section->staff->last_name }}
                                                                                        </td>
                                                                                        <td>
                                                                                            <a href="/administrator/subjects/class/removed?_c={{ Crypt::encrypt($_section->id) }}"
                                                                                                class="btn btn-danger">
                                                                                                <i
                                                                                                    class="fa fa-minus"></i>
                                                                                            </a>
                                                                                        </td>
                                                                                    </tr>

                                                                                @endforeach
                                                                            @endif
                                                                            @include('widgets.forms')
                                                                            @yield('subject-class-form')
                                                                        </tbody>
                                                                    </table>
                                                                </div>
                                                            </td>
                                                        </tr>
                                                    @endforeach
                                                @else
                                                    <tr>
                                                        <td colspan="5">NO SUBJECT</td>
                                                    </tr>
                                                @endif
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            @endif

                        @endforeach
                    </div>
                </div>
            @endforeach
        @else
            <div class="card card-primary ">
                <div class="card-body box-profile">
                    <form action="/administrator/subjects-handle" method="post" enctype="multipart/form-data">
                        @csrf
                        <input type="hidden" name="_academic" value="{{ request()->input('_c') }}">
                        <div class="form-group col-md-4">
                            <input type="file" class="form-control" name="_file">
                        </div>
                        <div class="form-group">
                            <button type="submit" class="btn btn-success">IMPORT</button>
                        </div>
                    </form>
                    <div class="text-center">

                        <label for="" class="text-warning h3">PLEASE SELECT COURSE</label>
                    </div>

                </div>
            </div>

        @endif
    </div>
@endsection
