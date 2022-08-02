@extends('layouts.app-main')
@php
$_title = 'Course Syllabus';
@endphp
@section('page-title', $_title)
@section('page-mode', 'dark-mode')
@section('beardcrumb-content')
    <li class="breadcrumb-item">
        <a href="{{ route('teacher.course-syllabus') }}">
            <svg width="14" height="14" class="me-2" viewBox="0 0 22 22" fill="none"
                xmlns="http://www.w3.org/2000/svg">
                <path
                    d="M8.15722 19.7714V16.7047C8.1572 15.9246 8.79312 15.2908 9.58101 15.2856H12.4671C13.2587 15.2856 13.9005 15.9209 13.9005 16.7047V16.7047V19.7809C13.9003 20.4432 14.4343 20.9845 15.103 21H17.0271C18.9451 21 20.5 19.4607 20.5 17.5618V17.5618V8.83784C20.4898 8.09083 20.1355 7.38935 19.538 6.93303L12.9577 1.6853C11.8049 0.771566 10.1662 0.771566 9.01342 1.6853L2.46203 6.94256C1.86226 7.39702 1.50739 8.09967 1.5 8.84736V17.5618C1.5 19.4607 3.05488 21 4.97291 21H6.89696C7.58235 21 8.13797 20.4499 8.13797 19.7714V19.7714"
                    stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"></path>
            </svg>Course Syllabus</a>
    </li>
    <li class="breadcrumb-item">
        <a
            href="{{ route('teacher.course-syllabus-editor') . '?course_syllabus=' . base64_encode($_course_syllabus->id) }}">
            {{ 'Subject: ' . $_course_syllabus->subject->subject_code }}
        </a>
    </li>
    <li class="breadcrumb-item active" aria-current="page">{{ $_title }}</li>
@endsection
@section('page-content')
    <div class="content">
        <div class="card">
            <div class="card-header">
                <label for=""
                    class="fw-bolder text-primary h4">{{ $_course_syllabus->subject->subject_code }}</label>
                <br> <small>{{ $_course_syllabus->subject->subject_name }}</small>
            </div>
            <div class="card-body">
                @include('pages.teacher.course-syllabus.part-tab-layouts.part-one')
                @include('pages.teacher.course-syllabus.part-tab-layouts.part-two')
                @include('pages.teacher.course-syllabus.part-tab-layouts.part-three')
                @if (request()->input('part') == 'part1')
                    @if (request()->input('section') == 'stcw-reference')
                    @endif
                @endif
            </div>
        </div>

    </div>


    <div class="modal fade model-add-reference" tabindex="-1" role="dialog" aria-labelledby="model-add-referenceTitle"
        aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-scrollable modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title modal-title" id="model-add-referenceTitle"></h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">
                    </button>
                </div>
                <div class="modal-body">
                    <form action="{{ route('teacher.stcw-reference-add') }}" method="post" id="modal-form-add">
                        @csrf
                        <input type="hidden" name="stcw" class="stcw" value="">
                        <input type="hidden" name="stcw_reference" class="stcw_reference" value="">
                        <div class="form-group">
                            <small for="" class="form-label">CONTENT</small>
                            <textarea name="content" id="modal-editor"cols="30" rows="5" class="form-control"></textarea>
                        </div>

                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-primary btn-sm btn-modal-form" data-form="modal-form-add">SAVE
                        CONTENT</button>
                </div>
            </div>
        </div>
    </div>
@endsection
@section('js')
    <script src="{{ asset('resources/plugin/editor/js/ckeditor.js') }}"></script>
    <script src="{{ asset('resources/plugin/editor/js/sample.js') }}"></script>
    <script>
        initSample();
        let editor = ['course_limitations', 'faculty_requirements',
            'teaching_facilities'/* , 'teaching_aids', 'references' */
        ]
        editor.forEach(element => {
            CKEDITOR.replace(element)
        });
        CKEDITOR.replace('modal-editor')
        // STCW REFERENCE
        $('.add-stcw').click(function(event) {
            Swal.fire({
                title: 'Course Syllabus',
                text: "Do you want to add?",
                icon: 'info',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes'
            }).then((result) => {
                var form = $(this).data('form');
                if (result.isConfirmed) {

                    //console.log(form)
                    document.getElementById(form).submit()
                }
            })
            event.preventDefault();
        })
        // Remove
        $('.btn-remove').click(function(event) {
            Swal.fire({
                title: 'Course Syllabus',
                text: "Do you want to remove?",
                icon: 'info',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes'
            }).then((result) => {
                var _url = $(this).data('url');
                if (result.isConfirmed) {
                    window.location.href = _url
                }
            })
            event.preventDefault();
        })
        $('.btn-add').click(function(event) {
            var model_title = $(this).data('title');
            var reference = $(this).data('stcw');
            var id = $(this).data('id');
            $('.modal-title').text(model_title)
            $('.stcw_reference').val(reference)
            $('.stcw').val(id)
            event.preventDefault();
        })
        $('.btn-modal-form').click(function(event) {
            Swal.fire({
                title: 'Course Syllabus',
                text: "Do you want to add?",
                icon: 'info',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes'
            }).then((result) => {
                var form = $(this).data('form');
                if (result.isConfirmed) {

                    console.log(form)
                    document.getElementById(form).submit()
                }
            })
            event.preventDefault();
        })
        /* $('.btn-add').click(function(event) {
            Swal.fire({
                title: 'Course Syllabus',
                text: "Do you want to add?",
                icon: 'info',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes'
            }).then((result) => {
                var form = $(this).data('form');
                if (result.isConfirmed) {

                    //console.log(form)
                    document.getElementById(form).submit()
                }
            })
            event.preventDefault();
        }) */

        $('.btn-form-add').click(function(event) {
            Swal.fire({
                title: 'Topic Materials',
                text: "Do you want to add?",
                icon: 'info',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes'
            }).then((result) => {
                var form = $(this).data('form');
                if (result.isConfirmed) {

                    //console.log(form)
                    document.getElementById(form).submit()
                }
            })
            event.preventDefault();
        })
    </script>
@endsection
