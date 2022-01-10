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
                                    <p class="text-primary h5">
                                        <b>{{ strtoupper($_journal->journal_type) }}</b>
                                    </p>

                                </a>
                                <div class="sub-nav collapse" id="home-{{ $_journal->id }}" data-bs-parent="#sidebar">
                                    <div class="form-group" id="narative-{{ $_journal }}">
                                      
                                        @if ($_journal->remark != null)
                                            <label for="" class="text-muted h6"><b><small>REMARKS</small></b></label>
                                            <textarea class="form-control" id="" cols="30"
                                                rows="4">{{ $_journal->remark }}</textarea>
                                        @endif
                                        <label for="" class="text-muted h6"><b><small>DOCUMENTS</small></b></label>
                                        <div class="d-grid gap-card grid-cols-4">
                                            @include('layouts.icon-main')
                                            @if ($_journal)
                                                @foreach (json_decode($_journal->file_links) as $links)
                                                    <a for="" data-documents={{ $links }}
                                                        class="btn-documents col">
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
                                        <div class=" ">
                                            <form action="" method="post" class="needs-validation" novalidate>
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
                                                        <a href="" class="btn btn-primary btn-sm w-100">APPROVED</a>
                                                    </div>
                                                    <div class="col-md">
                                                        <button type="submit"
                                                            class="btn btn-danger btn-sm w-100">DISAPPROVED</button>
                                                    </div>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                               {{--  <ul class="sub-nav collapse" id="home-{{ $_journal->id }}" data-bs-parent="#sidebar">
                                    <li class="nav-item">
                                      
                                    </li>
                                </ul> --}}
                            </li>
                        @endforeach

                    </ul>


                </div>
            </div>
            <div class="card">
                <div class="card-header">
                    <p class="card-title h4 text-primary "><b>Narative Report</b></p>
                </div>
                <div class="card-body">
                    {{-- @foreach ($_journals as $_journal)
                        <div class="form-group" id="narative-{{ $_journal }}">
                            <p class="text-muted h5">
                                <b>{{ strtoupper($_journal->journal_type) }}</b>
                            </p>
                            @if ($_journal->remark != null)
                                <label for="" class="text-muted h6"><b><small>REMARKS</small></b></label>
                                <textarea class="form-control" id="" cols="30"
                                    rows="4">{{ $_journal->remark }}</textarea>
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
                            <div class=" ">
                                <form action="" method="post" class="needs-validation" novalidate>
                                    @csrf
                                    <input type="hidden" name="_narative" value="{{ base64_encode($_journal->id) }}">
                                    <div class="d-flex justify-content-between align-items-center mb-2  flex-wrap">
                                        <small class="text-muted"><b>FEEDBACK</b></small>
                                        <textarea name="_feedback" class="form-control" cols="30" rows="3"
                                            required></textarea>
                                    </div>
                                    <div class="row">
                                        <div class="col-md">
                                            <a href="" class="btn btn-primary btn-sm w-100">APPROVED</a>
                                        </div>
                                        <div class="col-md">
                                            <button type="submit" class="btn btn-danger btn-sm w-100">DISAPPROVED</button>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                        <br>
                    @endforeach --}}


                    <ul class="navbar-nav iq-main-menu" id="sidebar-menu">
                        <li class="nav-item">
                            <a class="nav-link" data-bs-toggle="collapse" href="#home" role="button"
                                aria-expanded="false" aria-controls="home">
                                <i class="icon">
                                    <svg width="20" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                        <path opacity="0.4"
                                            d="M16.0756 2H19.4616C20.8639 2 22.0001 3.14585 22.0001 4.55996V7.97452C22.0001 9.38864 20.8639 10.5345 19.4616 10.5345H16.0756C14.6734 10.5345 13.5371 9.38864 13.5371 7.97452V4.55996C13.5371 3.14585 14.6734 2 16.0756 2Z"
                                            fill="currentColor"></path>
                                        <path fill-rule="evenodd" clip-rule="evenodd"
                                            d="M4.53852 2H7.92449C9.32676 2 10.463 3.14585 10.463 4.55996V7.97452C10.463 9.38864 9.32676 10.5345 7.92449 10.5345H4.53852C3.13626 10.5345 2 9.38864 2 7.97452V4.55996C2 3.14585 3.13626 2 4.53852 2ZM4.53852 13.4655H7.92449C9.32676 13.4655 10.463 14.6114 10.463 16.0255V19.44C10.463 20.8532 9.32676 22 7.92449 22H4.53852C3.13626 22 2 20.8532 2 19.44V16.0255C2 14.6114 3.13626 13.4655 4.53852 13.4655ZM19.4615 13.4655H16.0755C14.6732 13.4655 13.537 14.6114 13.537 16.0255V19.44C13.537 20.8532 14.6732 22 16.0755 22H19.4615C20.8637 22 22 20.8532 22 19.44V16.0255C22 14.6114 20.8637 13.4655 19.4615 13.4655Z"
                                            fill="currentColor"></path>
                                    </svg>
                                </i>
                                <span class="item-name">Dashboard</span>
                                <i class="right-icon">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="18" fill="none" viewBox="0 0 24 24"
                                        stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M9 5l7 7-7 7" />
                                    </svg>
                                </i>
                            </a>
                            <ul class="sub-nav collapse" id="home" data-bs-parent="#sidebar">
                                <li class="nav-item">
                                    <a class="nav-link " aria-current="page" href="../../dashboard/index.html">
                                        <i class="icon">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="10" viewBox="0 0 24 24"
                                                fill="currentColor">
                                                <g>
                                                    <circle cx="12" cy="12" r="8" fill="currentColor"></circle>
                                                </g>
                                            </svg>
                                        </i>
                                        <i class="sidenav-mini-icon"> U</i>
                                        <span class="item-name">User</span>
                                    </a>
                                </li>
                            </ul>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
        <div class="col-md-6">

            <iframe class="frame-documents" src="{{-- {{ $links }} --}}" frameborder="0" width="100%"
                height="700px"></iframe>
        </div>

    </div>
@endsection
