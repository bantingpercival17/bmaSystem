@section('page-title', 'Enrollment')

<div class="row">
    <div class="col-md-8">
        <p class="display-6 fw-bolder text-primary">ENROLLMENT {{$academic}}</p>
        <div class="row">
            <div class="col">
                <small class="text-primary"><b>SEARCH STUDENT NAME</b></small>
                <div class="form-group search-input">
                    <input type="search" class="form-control" placeholder="Search Pattern: Lastname, Firstname"
                        wire:model="searchInput">
                </div>
            </div>
        </div>
        <div class="d-flex justify-content-between mb-3">
            <div>
                @if ($searchInput != '')
                    <p for="" class="h5">
                        <small class="text-muted"> Search Result:</small>
                        <span class="fw-bolder h6 text-primary"> {{ strtoupper($searchInput) }}</span>
                    </p>
                @else
                    <span class="fw-bolder">
                        Recent Enrollee
                    </span>
                @endif
            </div>

            @if ($searchInput == '')
                @if (count($studentsList) > 0)
                    <div class="mb-3 float-end">
                        {{ $studentsList->links() }}
                    </div>
                @endif
            @else
                <span class="text-muted h6">
                    No. Result: <b>{{ count($studentsList) }}</b>
                </span>
            @endif
        </div>

        <div class="search-container">
            @include('pages.registrar.enrollment.widgets.enrollment-card-v2')
            @if (count($studentsList) > 0)
                @yield('student-enrollment-card')
            @else
                <div class="card">
                    <div class="row no-gutters">
                        <div class="col-md-6 col-lg-4">
                            <img src="http://bma.edu.ph/img/student-picture/midship-man.jpg" class="card-img"
                                alt="#">
                        </div>
                        <div class="col-md-6 col-lg-8">
                            <div class="card-body">
                                <h4 class="card-title text-primary">
                                    <b>STUDENT NOT FOUND</b>
                                </h4>
                                <p class="card-text">
                                    <span>STUDENT NUMBER</span>
                                    <br>
                                    <span>COURSE | YEAR LEVEL | SECTION</span>


                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            @endif
        </div>
        @if ($searchInput == '')
            @if (count($studentsList) > 0)
                <div class="mb-3 float-end">
                    {{ $studentsList->links() }}
                </div>
            @endif
        @endif

    </div>
    <div class="col-md-4">
        <div class="form-content mb-2">
            <a href="{{ route('enrollment.enrolled-student-list') }}{{ request()->input('_academic') ? '?_academic=' . request()->input('_academic') : '' }}"
                class="badge bg-primary w-100">{{ strtoupper('List of Enrolled Students') }}</a>
            <a
                href="{{ route('enrollment.withdrawn-list') }}{{ request()->input('_academic') ? '?_academic=' . request()->input('_academic') : '' }}"class="badge bg-primary w-100">{{ strtoupper('List of Withdrawn & Dropped') }}</a>
        </div>
        @foreach ($courseLists as $_course)
            <div class="col-md">
                <a
                    href="{{ route('enrollment.view-v2') }}?_course={{ base64_encode($_course->id) }}&view={{ request()->input('view') }}{{ request()->input('_academic') ? '&_academic=' . request()->input('_academic') : '' }}">
                    <div class="card">
                        <div class="card-body">
                            <div class="d-flex align-items-center justify-content-between mb-2">
                                <h5 class="text-primary">
                                    {{ $_course->course_name }}
                                </h5>
                                <a href="javascript:void(0);">
                                    <svg width="32" viewBox="0 0 24 24" fill="none"
                                        xmlns="http://www.w3.org/2000/svg">
                                        <path
                                            d="M17.8877 10.8967C19.2827 10.7007 20.3567 9.50473 20.3597 8.05573C20.3597 6.62773 19.3187 5.44373 17.9537 5.21973"
                                            stroke="currentColor" stroke-width="1.5" stroke-linecap="round"
                                            stroke-linejoin="round">
                                        </path>
                                        <path
                                            d="M19.7285 14.2505C21.0795 14.4525 22.0225 14.9255 22.0225 15.9005C22.0225 16.5715 21.5785 17.0075 20.8605 17.2815"
                                            stroke="currentColor" stroke-width="1.5" stroke-linecap="round"
                                            stroke-linejoin="round">
                                        </path>
                                        <path fill-rule="evenodd" clip-rule="evenodd"
                                            d="M11.8867 14.6638C8.67273 14.6638 5.92773 15.1508 5.92773 17.0958C5.92773 19.0398 8.65573 19.5408 11.8867 19.5408C15.1007 19.5408 17.8447 19.0588 17.8447 17.1128C17.8447 15.1668 15.1177 14.6638 11.8867 14.6638Z"
                                            stroke="currentColor" stroke-width="1.5" stroke-linecap="round"
                                            stroke-linejoin="round">
                                        </path>
                                        <path fill-rule="evenodd" clip-rule="evenodd"
                                            d="M11.8869 11.888C13.9959 11.888 15.7059 10.179 15.7059 8.069C15.7059 5.96 13.9959 4.25 11.8869 4.25C9.7779 4.25 8.0679 5.96 8.0679 8.069C8.0599 10.171 9.7569 11.881 11.8589 11.888H11.8869Z"
                                            stroke="currentColor" stroke-width="1.5" stroke-linecap="round"
                                            stroke-linejoin="round">
                                        </path>
                                        <path
                                            d="M5.88509 10.8967C4.48909 10.7007 3.41609 9.50473 3.41309 8.05573C3.41309 6.62773 4.45409 5.44373 5.81909 5.21973"
                                            stroke="currentColor" stroke-width="1.5" stroke-linecap="round"
                                            stroke-linejoin="round">
                                        </path>
                                        <path
                                            d="M4.044 14.2505C2.693 14.4525 1.75 14.9255 1.75 15.9005C1.75 16.5715 2.194 17.0075 2.912 17.2815"
                                            stroke="currentColor" stroke-width="1.5" stroke-linecap="round"
                                            stroke-linejoin="round">
                                        </path>
                                    </svg>
                                </a>
                            </div>
                            <div class="d-flex align-items-center justify-content-between">
                                <h3 class="conter">{{ count($_course->enrollment_application) }}</h3>
                            </div>
                        </div>
                    </div>
                </a>
            </div>
        @endforeach

    </div>
</div>