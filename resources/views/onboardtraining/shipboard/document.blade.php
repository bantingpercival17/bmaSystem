@extends('layouts.app-main')
@php
$_title = 'Shipboard Monitoring';
@endphp
@section('page-title', $_title)
@section('page-navigation')
    <ol class="breadcrumb ">
        <li class="breadcrumb-item"><a href="/">Dashboard</a></li>
        <li class="breadcrumb-item active"> Midshipman</li>

    </ol>
@endsection
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
@section('js')
    <script>
        $(document).on('click', '.btn-documents', function(evt) {
            $('.frame-documents').attr('src', $(this).data('documents'))
        });
    </script>
@endsection
@section('page-content')
    <div class="card mb-2">
        <div class="row no-gutters">
            <div class="col-md col-lg-2">

                <img src="{{ $_midshipman ? $_midshipman->profile_pic($_midshipman->account) : 'http://bma.edu.ph/img/student-picture/midship-man.jpg' }}"
                    class="card-img " alt="#">
            </div>
            <div class="col-md-8 col-lg-8">
                <div class="card-body">
                    <h4 class="card-title text-primary">
                        <b>{{ $_midshipman ? strtoupper($_midshipman->last_name . ', ' . $_midshipman->first_name) : 'MIDSHIPMAN NAME' }}</b>
                    </h4>
                    <p class="card-text">
                        <span>STUDENT NUMBER: <b>
                                {{ $_midshipman ? $_midshipman->account->student_number : '-' }}</b></span>
                        <br>
                        <span>COURE: <b>
                                {{ $_midshipman ? $_midshipman->enrollment_assessment->course->course_name : '-' }}</b></span>

                    </p>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-6">
            <div class="card">
                <div class="card-header">
                    <p class="card-title h4 text-primary "><b>{{ strtoupper($_journal->journal_type) }}</b></p>
                </div>
                <div class="card-body">
                    <label for="" class="text-muted h6"><b>REMARKS</b></label>
                    <textarea class="form-control" id="" cols="30" rows="5">
                                                                    {{ $_journal->remark }}
                                                                </textarea>
                    <label for="" class="text-muted h6"><b>DOCUMENTS</b></label>
                    <div class="d-grid gap-card grid-cols-4">
                        @include('layouts.icon-main')
                        @if ($_journal)
                            @foreach (json_decode($_journal->file_links) as $links)
                                <a for="" data-documents={{ $links }} class="btn-documents col">
                                    @php
                                        $myFile = pathinfo($links);
                                        $_ext = $myFile['extension'];
                                        $_file = $myFile['basename'];
                                        
                                    @endphp
                                    <i>
                                        @if ($_ext == 'docx' || $_ext == 'doc')
                                            @yield('icon-doc')
                                        @elseif ($_ext == 'pdf')
                                            @yield('icon-pdf')
                                        @elseif ($_ext == 'png')
                                            @yield('icon-png')
                                        @elseif ($_ext == 'jpg' || $_ext == 'jpeg')
                                            @yield('icon-jpg')
                                        @else
                                        @endif
                                    </i>
                                    <small>{{ str_replace('[' . str_replace('@bma.edu.ph', '', $_midshipman->account->campus_email) . ']', '', $_file) }}</small>

                                </a>
                            @endforeach
                        @endif
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-6">

            <iframe class="frame-documents" src="{{ $links }}" frameborder="0" width="100%" height="700px"></iframe>
        </div> 

    </div>
@endsection
