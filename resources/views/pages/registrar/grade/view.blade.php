@extends('layouts.app-main')
@php
    $_title = 'Semestral Grade';
@endphp
@section('page-title', $_title)
@section('beardcrumb-content')
    @if (request()->input('_course'))
        <li class="breadcrumb-item active">
            <a
                href="{{ route('registrar.semestral-grade-view') }}{{ request()->input('_academic') ? '?_academic=' . request()->input('_academic') : '' }}">
                <svg width="14" height="14" class="me-2" viewBox="0 0 22 22" fill="none"
                    xmlns="http://www.w3.org/2000/svg">
                    <path
                        d="M8.15722 19.7714V16.7047C8.1572 15.9246 8.79312 15.2908 9.58101 15.2856H12.4671C13.2587 15.2856 13.9005 15.9209 13.9005 16.7047V16.7047V19.7809C13.9003 20.4432 14.4343 20.9845 15.103 21H17.0271C18.9451 21 20.5 19.4607 20.5 17.5618V17.5618V8.83784C20.4898 8.09083 20.1355 7.38935 19.538 6.93303L12.9577 1.6853C11.8049 0.771566 10.1662 0.771566 9.01342 1.6853L2.46203 6.94256C1.86226 7.39702 1.50739 8.09967 1.5 8.84736V17.5618C1.5 19.4607 3.05488 21 4.97291 21H6.89696C7.58235 21 8.13797 20.4499 8.13797 19.7714V19.7714"
                        stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"></path>
                </svg>{{ $_title }}
            </a>

        </li>
        <li class="breadcrumb-item active" aria-current="page">
            Sections
        </li>
    @else
        <li class="breadcrumb-item active" aria-current="page">
            <svg width="14" height="14" class="me-2" viewBox="0 0 22 22" fill="none"
                xmlns="http://www.w3.org/2000/svg">
                <path
                    d="M8.15722 19.7714V16.7047C8.1572 15.9246 8.79312 15.2908 9.58101 15.2856H12.4671C13.2587 15.2856 13.9005 15.9209 13.9005 16.7047V16.7047V19.7809C13.9003 20.4432 14.4343 20.9845 15.103 21H17.0271C18.9451 21 20.5 19.4607 20.5 17.5618V17.5618V8.83784C20.4898 8.09083 20.1355 7.38935 19.538 6.93303L12.9577 1.6853C11.8049 0.771566 10.1662 0.771566 9.01342 1.6853L2.46203 6.94256C1.86226 7.39702 1.50739 8.09967 1.5 8.84736V17.5618C1.5 19.4607 3.05488 21 4.97291 21H6.89696C7.58235 21 8.13797 20.4499 8.13797 19.7714V19.7714"
                    stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"></path>
            </svg>{{ $_title }}
        </li>
    @endif

@endsection
@section('js')
    <script>
        $(document).on('click', '.btn-form-grade', function(evt) {
            $('.form-view').attr('src', $(this).data('grade-url'))
        });
    </script>
@endsection
@section('page-content')
    @foreach ($_courses as $_course)
        @php
            $_year_level = $_course->id == 3 ? ['GRADE 11', 'GRADE 12'] : ['4/C', '3/C', '2/C', '1/C'];
            $_section_name = ['FORECASTLE', 'STARBOARD', 'MIDSHIP', 'PORT'];
            $_section_name = $_course->id == 1 ? ['BRIDGING', 'BSMARE ALPHA', 'BSMARE BRAVO', 'BSMARE CHARLIE', 'BSMARE CURR 2006', 'BSMARE CURR 2013', 'BSMARE CURR 2015', 'BSMARE CURR 2018'] : $_section_name;
            $_section_name = $_course->id == 2 ? ['BRIDGING', 'BSMT ALPHA', 'BSMT BRAVO', 'BSMT CHARLIE', 'BSMT CURR 2006', 'BSMT CURR 2013', 'BSMT CURR 2015', 'BSMT CURR 2018'] : $_section_name;
            $academic = Auth::user()->staff->current_academic();
            
        @endphp
        <div class="card">
            <div class="card-header d-flex justify-content-between">
                <div class="header-title">
                    <h4 class="card-title fw-bolder">{{ $_course->course_name }}</h4>
                </div>
            </div>
            <div class="card-body">
                <div class="table-responsive mt-4">
                    <table id="basic-table" class="table table-striped mb-0" role="grid">
                        <thead>
                            <tr>
                                <th>YEAR LEVEL</th>
                                <th>SECTIONS</th>
                                <th>EXPORT</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($_year_level as $level)
                                @php
                                    $_sections = $_course->section([Auth::user()->staff->current_academic()->id, $level])->get();
                                @endphp
                                <tr>
                                    <td class="">
                                        {{ strtoupper(Auth::user()->staff->convert_year_level($level)) }}</td>
                                    <td>
                                        @if (count($_sections) > 0)
                                            @foreach ($_sections as $item)
                                                <a
                                                    href="{{ route('registrar.semestral-grade-view') }}?_section={{ base64_encode($item->id) }}{{ request()->input('_academic') ? '&_academic=' . request()->input('_academic') : '' }}">
                                                    <span class="badge bg-primary">{{ $item->section_name }}</span>
                                                </a>
                                            @endforeach
                                        @else
                                            <span class="badge bg-info">No Section</span>
                                        @endif
                                    </td>
                                    <td class="row">
                                        @if (count($_sections) > 0)
                                            <button type="button" class="btn btn-danger btn-sm btn-form-grade mt-3 col-md"
                                                data-bs-toggle="modal" data-bs-target=".grade-view-modal"
                                                data-grade-url="{{ route('registrar.semestral-grade-semestral-report') }}?_year_level={{ str_replace('/C', '', $level) }}&_course={{ base64_encode($_course->id) }}{{ request()->input('_academic') ? '&_academic=' . request()->input('_academic') : '' }}">
                                                PDF FILE</button>
                                            <br>
                                            <a href="{{ route('registrar.subject-grade-export') }}?_year_level={{ str_replace('/C', '', $level) }}&_course={{ base64_encode($_course->id) }}{{ request()->input('_academic') ? '&_academic=' . request()->input('_academic') : '' }}"
                                                class="btn btn-sm btn-primary ms-2 mt-3 col-md">EXCEL FILE</a>
                                        @endif

                                    </td>
                                </tr>
                            @endforeach

                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    @endforeach
    <div class="modal fade grade-view-modal" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-xl">
            <div class="modal-content">
                <iframe class="form-view iframe-placeholder" src="" width="100%" height="700px">
                </iframe>
            </div>
        </div>
    </div>

@endsection
