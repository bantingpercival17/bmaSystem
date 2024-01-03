<div>
    <p class="display-6 fw-bolder text-primary">Entrance Examination Overview</p>
    <div class="row">
        <div class="col-lg-12 col-xl-3">
            <div class="card  iq-purchase" data-iq-gsap="onStart" data-iq-position-y="50" data-iq-rotate="0"
                data-iq-ease="power.out" data-iq-opacity="0">
                <div class="card-body">
                    <div class="d-flex align-items-center justify-content-between">
                        <h3 class="counter"></h3>
                        <a href="javascript:void(0);">
                            <svg width="32" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
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
                    <div class="d-flex align-items-center justify-content-between mb-2">
                        <h5 class="text-primary">
                            Total Registrants
                        </h5>

                    </div>

                </div>
            </div>
        </div>
        <div class="col-lg-12 col-xl-9">
            <div class="row">
                @foreach ($courses as $_course)
                    <div class="col-md-4">
                        <a href="">
                            <div class="card iq-purchase" data-iq-gsap="onStart" data-iq-position-y="50"
                                data-iq-rotate="0" data-iq-ease="power.out" data-iq-opacity="0">
                                <div class="card-body">
                                    <div class="d-flex align-items-center justify-content-between">
                                        <h3 class="counter">
                                            {{-- {{ count($_course->applicant_registrants) }} --}}
                                        </h3>
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

                                    </div>
                                    <div class="d-flex align-items-center justify-content-between mb-2">
                                        <h5 class="text-primary">
                                            {{ $_course->course_code }}
                                        </h5>

                                    </div>
                                </div>
                            </div>
                        </a>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
    <div class="card">
        <div class="card-header d-flex justify-content-between">
            <div class="header-title">
                <h4 class="card-title text-primary fw-bolder">SUMMARY VIEW</h4>
            </div>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table id="basic-table" class="table table-striped mb-0" role="grid">
                    <thead>
                        <tr class="text-center">
                            <th></th>
                            @foreach ($courses as $course)
                                <th> {{ $course->course_name }}</th>
                            @endforeach
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($tableHeader as $index => $headers)
                            <tr>
                                <th colspan="4" class="text-center fw-bolder text-primary">
                                    {{ strtoupper($headers[0]) }}
                                </th>
                            </tr>
                            @foreach ($headers[1] as $item)
                                <tr>
                                    <th>{{ strtoupper(str_replace('_', ' ', $item)) }}</th>
                                    @foreach ($courses as $course)
                                        <td class="text-center">
                                            <a
                                                href="{{ route('applicant.overview') . '?_course=' . base64_encode($course->id) . '&_category=' . $item }}{{ request()->input('_academic') ? '&_academic=' . request()->input('_academic') : '' }}">
                                                {{ count($course->applicant_count_per_category($item)) }}
                                            </a>
                                        </td>
                                    @endforeach
                                </tr>
                            @endforeach
                        @endforeach
                    </tbody>
                    {{--  <tbody>
                        @foreach ($_courses as $_course)
                            <tr>
                                <td class="course-btn" data-course="{{ $_course->id }}">
                                    {{ $_course->course_name }}
                                </td>
                                @foreach ($tableHeader as $index => $headers)
                                    @if ($index > 0)
                                        @foreach ($headers[1] as $index => $item)
                                            <td class="text-center">
                                                <a
                                                    href="{{ route('applicant.overview') . '?_course=' . base64_encode($_course->id) . '&_category=' . $item }}{{ request()->input('_academic') ? '&_academic=' . request()->input('_academic') : '' }}">
                                                    {{ count($_course->applicant_count_per_category($item)) }}
                                                </a>

                                            </td>
                                        @endforeach
                                    @endif
                                @endforeach
                            </tr>
                        @endforeach
                    </tbody> --}}
                </table>
            </div>
        </div>
    </div>
</div>
