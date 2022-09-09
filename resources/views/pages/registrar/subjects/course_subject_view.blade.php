@extends('layouts.app-main')
@php
$_title = 'Subjects';
@endphp
@section('page-title', 'Subjects')
@section('beardcrumb-content')
    <li class="breadcrumb-item">
        <a href="{{ route('registrar.subject-view') }}">
            <svg width="14" height="14" class="me-2" viewBox="0 0 22 22" fill="none"
                xmlns="http://www.w3.org/2000/svg">
                <path
                    d="M8.15722 19.7714V16.7047C8.1572 15.9246 8.79312 15.2908 9.58101 15.2856H12.4671C13.2587 15.2856 13.9005 15.9209 13.9005 16.7047V16.7047V19.7809C13.9003 20.4432 14.4343 20.9845 15.103 21H17.0271C18.9451 21 20.5 19.4607 20.5 17.5618V17.5618V8.83784C20.4898 8.09083 20.1355 7.38935 19.538 6.93303L12.9577 1.6853C11.8049 0.771566 10.1662 0.771566 9.01342 1.6853L2.46203 6.94256C1.86226 7.39702 1.50739 8.09967 1.5 8.84736V17.5618C1.5 19.4607 3.05488 21 4.97291 21H6.89696C7.58235 21 8.13797 20.4499 8.13797 19.7714V19.7714"
                    stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"></path>
            </svg>{{ $_title }}
        </a>
    </li>
    <li class="breadcrumb-item active" aria-current="page">
        {{ $_course->course_name }}
    </li>
@endsection
@section('js')
    <script>
        $(document).on('click', '.btn-form-grade', function(evt) {
            $('.form-view').attr('src', $(this).data('grade-url'))
            console.log($(this).data('grade-url'))
        });
    </script>
@endsection
@section('page-content')
    <div class="content">
        @if ($_course)
            <div class="curriculum-content">
                <ul class="nav nav-tabs" id="myTab-three" role="tablist">
                    @foreach ($_curriculums as $key => $curriculum)
                        <li class="nav-item">
                            <a class="nav-link {{ $key == 0 ? 'active' : '' }}"
                                id="{{ str_replace(' ', '-', str_replace('[3-1]', '', strtolower($curriculum->curriculum_name))) }}-tab-three"
                                data-bs-toggle="tab"
                                href="#{{ str_replace(' ', '-', str_replace('[3-1]', '', strtolower($curriculum->curriculum_name))) }}-three"
                                role="tab"
                                aria-controls="{{ str_replace(' ', '-', str_replace('[3-1]', '', strtolower($curriculum->curriculum_name))) }}"
                                aria-selected="true"><small>{{ strtoupper($curriculum->curriculum_name) }}</small></a>
                        </li>
                    @endforeach

                </ul>
                <div class="tab-content mt-5" id="myTabContent-4">
                    @foreach ($_curriculums as $key => $curriculum)
                        <div class="tab-pane fade show {{ $key == 0 ? ' active' : '' }}"
                            id="{{ str_replace(' ', '-', str_replace('[3-1]', '', strtolower($curriculum->curriculum_name))) }}-three"
                            role="tabpanel"
                            aria-labelledby="{{ str_replace(' ', '-', str_replace('[3-1]', '', strtolower($curriculum->curriculum_name))) }}-tab-three">
                            <label for=""
                                class="h4 text-primary fw-bolder">{{ strtoupper($curriculum->curriculum_name) }}</label>
                            @php
                                $_year_level = $_course->id == 3 ? [11, 12] : [4, 3, 2, 1];
                                $_academic = Auth::user()->staff->current_academic();
                            @endphp
                            @foreach ($_year_level as $_level)
                                @if (count(
                                    $_course->course_subject([$curriculum->id, $_level, Auth::user()->staff->current_academic()->semester])) > 0)
                                    <div class="card">
                                        <div class="card-header">
                                            <label class="card-title text-muted fw-bolder">
                                                {{ strtoupper(Auth::user()->staff->convert_year_level($_level)) }}
                                            </label>
                                        </div>
                                        <div class="card-body table-responsive p-0">
                                            <table class="table table-head-fixed text-nowrap">
                                                <thead>
                                                    <tr>
                                                        <th>SUBJECT CODE / DESCRIPTION</th>
                                                        <th>SECTION</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    @if ($_subject = $_course->course_subject([
                                                        $curriculum->id,
                                                        $_level,
                                                        Auth::user()->staff->current_academic()->semester,
                                                    ]))
                                                        @foreach ($_subject as $_subject)
                                                            <tr>
                                                                <td>
                                                                    <a
                                                                        href="{{ route('registrar.course-subject-handle-view') }}?_subject={{ base64_encode($_subject->id) }}{{ request()->input('_academic') ? '&_academic=' . request()->input('_academic') : '' }}">
                                                                        <span class="text-primary"><b>
                                                                                {{ $_subject->subject_code }}</b></span>
                                                                        <br>
                                                                        <small> {{ $_subject->subject_name }}</small>
                                                                    </a>
                                                                </td>
                                                                <td>
                                                                    @if ($_subject->section($_academic->id)->count() > 0)
                                                                        @foreach ($_subject->section($_academic->id)->get() as $_section)
                                                                            <small
                                                                                class="mt-2 btn-form-grade badge bg-primary"
                                                                                data-bs-toggle="modal"
                                                                                data-bs-target=".grade-view-modal"
                                                                                data-grade-url="{{ route('registrar.subject-grade') }}?_subject={{ base64_encode($_section->id) }}&_period={{ request()->input('_period') }}&_preview=pdf&_form=ad2">
                                                                                {{ $_section->section->section_name }}
                                                                                <br>[
                                                                                {{ $_section->staff->first_name . ' ' . $_section->staff->last_name }}]</small>
                                                                        @endforeach
                                                                    @else
                                                                        <span class="badge badge-secondary">ADD
                                                                            SECTION</span>
                                                                    @endif
                                                                </td>

                                                            </tr>
                                                        @endforeach
                                                    @else
                                                        <tr>
                                                            <td colspan="2">NO SUBJECT</td>
                                                        </tr>
                                                    @endif
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                @endif
                            @endforeach
                        </div>
                    @endforeach
                </div>
            </div>


        @endif
    </div>

    <div class="modal fade grade-view-modal" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-xl">
            <div class="modal-content">
                <iframe class="form-view iframe-placeholder" src="" width="100%" height="600px">
                </iframe>
            </div>
        </div>
    </div>
@endsection
