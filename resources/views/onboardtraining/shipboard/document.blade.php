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
                <div class="card-header d-flex justify-content-between">
                    <div class="header-title">
                        <p class="card-title text-primary h4">
                            <b>{{ date('F - Y', strtotime(base64_decode(request()->input('_j')))) }}</b>
                        </p>
                        <small class="text-muted"><b>MONTHLY NARATIVE</b></small>
                    </div>
                </div>
                <div class="card-body">
                    <ul class="navbar-nav iq-main-menu" id="sidebar-menu">
                        @foreach ($_journals as $_journal)
                            <li class="nav-item">
                                <a class="nav-link" data-bs-toggle="collapse" href="#home-{{ $_journal->id }}"
                                    role="button" aria-expanded="false" aria-controls="home">
                                    <p
                                        class="{{ $_journal->is_approved == null ? 'text-muted' : ($_journal->is_approved == 1 ? 'text-primary' : 'text-danger') }} h5">
                                        <b>{{ strtoupper($_journal->journal_type) }}</b>
                                    </p>
                                    @if ($_journal->is_approved == 1)
                                        <small class="text-muted">DATE APPROVED: </small>
                                        <b>{{ $_journal->updated_at->format('F d, Y') }}</b>
                                        <br>
                                        <small class="text-muted">APPROVED BY: </small>
                                        <b>{{ $_journal->staff->first_name . ' ' . $_journal->staff->last_name }}</b>
                                    @elseif($_journal->is_approved == 2)
                                        <small class="text-muted">DATE DISAPPROVED: </small>
                                        <b>{{ $_journal->updated_at->format('F d, Y') }}</b>
                                        <br>
                                        <small class="text-muted">DISAPPROVED BY: </small>
                                        <b>{{ $_journal->staff->first_name . ' ' . $_journal->staff->last_name }}</b>
                                        <br>
                                        <small class="text-muted">FEEDBACK: </small>
                                        <b class="text-danger">{{ $_journal->feedback }}</b>
                                    @endif

                                </a>
                                <div class="sub-nav collapse active" id="home-{{ $_journal->id }}"
                                    data-bs-parent="#sidebar">
                                    <div class="form-group" id="narative-{{ $_journal }}">

                                        @if ($_journal->remark != null)
                                            <label for="" class="text-muted h6"><b><small>REMARKS</small></b></label>
                                            <textarea class="form-control" id="" cols="30" rows="4"
                                                disabled>{{ $_journal->remark }}</textarea>
                                        @endif
                                        <label for="" class="text-muted h6"><b><small>DOCUMENTS</small></b></label>
                                        <div class="d-grid gap-card grid-cols-4">
                                            @include('layouts.icon-main')
                                            @if ($_journal)
                                                @foreach (json_decode($_journal->file_links) as $links)
                                                    <a for="" data-documents={{ $links }} class="btn-documents col">
                                                        @php
                                                            $myFile = pathinfo($links);
                                                            $_ext = $myFile['extension'];
                                                            $_file = $myFile['basename'];
                                                            $_file = str_replace('[' . str_replace('@bma.edu.ph', '', $_midshipman->account->campus_email) . ']', '', $_file);
                                                            
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
                                                        <small>{{ mb_strimwidth($_file, 0, 10, '...') }}</small>

                                                    </a>
                                                @endforeach
                                            @endif
                                        </div>
                                        @if ($_journal->is_approved == null)
                                            <form action="{{ route('onboard.narative-report-disapproved') }}"
                                                method="post" class="needs-validation" novalidate>
                                                @csrf
                                                <input type="hidden" name="_narative"
                                                    value="{{ base64_encode($_journal->id) }}">
                                                <div
                                                    class="d-flex justify-content-between align-items-center mb-2  flex-wrap">
                                                    <small class="text-muted"><b>FEEDBACK</b></small>
                                                    <textarea name="_feedback" class="form-control" cols="30" rows="3"
                                                        required></textarea>
                                                </div>
                                                <div class="row">
                                                    <div class="col-md">
                                                        <a href="{{ route('onboard.narative-report-approved') }}?_n={{ base64_encode($_journal->id) }}"
                                                            class="btn btn-primary btn-sm w-100">APPROVED</a>
                                                    </div>
                                                    <div class="col-md">
                                                        <button type="submit"
                                                            class="btn btn-danger btn-sm w-100">DISAPPROVED</button>
                                                    </div>
                                                </div>
                                            </form>
                                        @endif
                                    </div>
                                </div>
                            </li>
                            <hr>
                        @endforeach

                    </ul>


                </div>
            </div>

        </div>
        <div class="col-md-6">

            <iframe class="frame-documents" src="{{ $links }}" frameborder="0" width="100%" height="700px"></iframe>
        </div>

    </div>
@endsection
