@php
$_route = route('applicant-lists') . '?' . (request()->input('_academic') ? '&_academic=' . request()->input('_academic') . '&' : '');
@endphp
<section>
    <p class="display-6 fw-bolder text-primary">Entrance Examination Overview</p>
    <div class="row">
        <div class="col-lg-12 col-xl-3">
            <div class="card  iq-purchase" data-iq-gsap="onStart" data-iq-position-y="50" data-iq-rotate="0" data-iq-ease="power.out" data-iq-opacity="0">
                <div class="card-body">
                    <div class="d-flex align-items-center justify-content-between">
                        <h3 class="counter">{{ Auth::user()->staff->total_applicants() }}</h3>
                        <a href="javascript:void(0);">
                            <svg width="32" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <path d="M17.8877 10.8967C19.2827 10.7007 20.3567 9.50473 20.3597 8.05573C20.3597 6.62773 19.3187 5.44373 17.9537 5.21973" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round">
                                </path>
                                <path d="M19.7285 14.2505C21.0795 14.4525 22.0225 14.9255 22.0225 15.9005C22.0225 16.5715 21.5785 17.0075 20.8605 17.2815" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round">
                                </path>
                                <path fill-rule="evenodd" clip-rule="evenodd" d="M11.8867 14.6638C8.67273 14.6638 5.92773 15.1508 5.92773 17.0958C5.92773 19.0398 8.65573 19.5408 11.8867 19.5408C15.1007 19.5408 17.8447 19.0588 17.8447 17.1128C17.8447 15.1668 15.1177 14.6638 11.8867 14.6638Z" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round">
                                </path>
                                <path fill-rule="evenodd" clip-rule="evenodd" d="M11.8869 11.888C13.9959 11.888 15.7059 10.179 15.7059 8.069C15.7059 5.96 13.9959 4.25 11.8869 4.25C9.7779 4.25 8.0679 5.96 8.0679 8.069C8.0599 10.171 9.7569 11.881 11.8589 11.888H11.8869Z" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round">
                                </path>
                                <path d="M5.88509 10.8967C4.48909 10.7007 3.41609 9.50473 3.41309 8.05573C3.41309 6.62773 4.45409 5.44373 5.81909 5.21973" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round">
                                </path>
                                <path d="M4.044 14.2505C2.693 14.4525 1.75 14.9255 1.75 15.9005C1.75 16.5715 2.194 17.0075 2.912 17.2815" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round">
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
                @foreach ($_courses as $_course)
                <div class="col-md-4">
                    <a href="{{ $_route . '_course=' . base64_encode($_course->id) }}&view=verified-applicant">
                        <div class="card iq-purchase" data-iq-gsap="onStart" data-iq-position-y="50" data-iq-rotate="0" data-iq-ease="power.out" data-iq-opacity="0">
                            <div class="card-body">
                                <div class="d-flex align-items-center justify-content-between">
                                    <h3 class="counter">
                                        {{ count($_course->applicant_registrants) }}
                                        {{-- {{count($_course->student_applicants)}} --}}
                                    </h3>
                                    <svg width="32" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                        <path d="M17.8877 10.8967C19.2827 10.7007 20.3567 9.50473 20.3597 8.05573C20.3597 6.62773 19.3187 5.44373 17.9537 5.21973" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round">
                                        </path>
                                        <path d="M19.7285 14.2505C21.0795 14.4525 22.0225 14.9255 22.0225 15.9005C22.0225 16.5715 21.5785 17.0075 20.8605 17.2815" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round">
                                        </path>
                                        <path fill-rule="evenodd" clip-rule="evenodd" d="M11.8867 14.6638C8.67273 14.6638 5.92773 15.1508 5.92773 17.0958C5.92773 19.0398 8.65573 19.5408 11.8867 19.5408C15.1007 19.5408 17.8447 19.0588 17.8447 17.1128C17.8447 15.1668 15.1177 14.6638 11.8867 14.6638Z" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round">
                                        </path>
                                        <path fill-rule="evenodd" clip-rule="evenodd" d="M11.8869 11.888C13.9959 11.888 15.7059 10.179 15.7059 8.069C15.7059 5.96 13.9959 4.25 11.8869 4.25C9.7779 4.25 8.0679 5.96 8.0679 8.069C8.0599 10.171 9.7569 11.881 11.8589 11.888H11.8869Z" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round">
                                        </path>
                                        <path d="M5.88509 10.8967C4.48909 10.7007 3.41609 9.50473 3.41309 8.05573C3.41309 6.62773 4.45409 5.44373 5.81909 5.21973" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round">
                                        </path>
                                        <path d="M4.044 14.2505C2.693 14.4525 1.75 14.9255 1.75 15.9005C1.75 16.5715 2.194 17.0075 2.912 17.2815" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round">
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
                <h4 class="card-title">Summary Overview</h4>
            </div>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table id="basic-table" class="table table-striped mb-0" role="grid">
                    <thead>
                        <tr class="text-center">
                            <th rowspan="2">COURSE</th>
                            <th rowspan="2">PRE-REGISTRATION</th>
                            <th rowspan="2">INCOMPLETE <br> DOCUMENTS</th>
                            <th colspan="4">INFORMATION VERIFICATION</th>
                            <th>BMA-ALUMNUS</th>
                            <th colspan="2">ENTRANCE EXAMINATION PAYMENT</th>
                            <th colspan="{{ Auth::user()->email == 'p.banting@bma.edu.ph' || Auth::user()->email == 'k.j.cruz@bma.edu.ph' ? 3 : 2 }}">
                                ENTRANCE EXAMINATION</th>
                            <th rowspan="2">BRIEFING</th>
                            <th colspan="3">MEDICAL EXAMINATION</th>
                            <th rowspan="2">QUALIFIED FOR ENROLLMENT</th>
                        </tr>
                        <tr>

                            <th>FOR CHECKING</th>
                            <th>QUALIFIED</th>
                            <th>NOT QUALIFIED</th>
                            <th>VERIFIED</th>
                            <th></th>
                            <th>FOR VERIFICATION</th>
                            <th>PAYMENT VERIFED</th>
                            @if (Auth::user()->email == 'p.banting@bma.edu.ph' || Auth::user()->email == 'k.j.cruz@bma.edu.ph')
                            <th>ONGOING</th>
                            @endif
                            <th>PASSED</th>
                            <th>FAILED</th>
                            <th>FOR SCHEDULING</th>
                            <th>SCHEDULED</th>
                            <th>RESULT</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($_courses as $_course)
                        <tr>
                            <td class="course-btn" data-course="{{ $_course->id }}">
                                {{ $_course->course_name }}
                            </td>
                            <td>
                                <a href="{{ $_route . '_course=' . base64_encode($_course->id) }}&view=pre-registration">
                                    {{ count($_course->applicant_pre_registrations) }}
                                </a>

                            </td>
                            <td>

                                <a href="{{ $_route . '_course=' . base64_encode($_course->id) }}&view=incomplete-document">
                                    {{ count($_course->applicant_incomplete_documents) }}
                                </a>
                            </td>
                            <td>
                                <a href="{{ $_route . '_course=' . base64_encode($_course->id) }}&view=for-checking">
                                    {{ count($_course->applicant_for_checking) }}
                                </a>
                            </td>
                            <td>
                                <a href="{{ $_route . '_course=' . base64_encode($_course->id) }}&view=verified-applicant">
                                    {{ count($_course->verified_applicants) }}
                                </a>
                            </td>
                            <td>
                                <a href="{{ $_route . '_course=' . base64_encode($_course->id) }}&view=not-qualified">
                                    {{ count($_course->applicant_not_qualified) }}
                                </a>
                            </td>
                            <td>
                                <a href="{{ $_route . '_course=' . base64_encode($_course->id) }}&view=verified">
                                    {{ count($_course->applicant_verified_documents) }}
                                </a>
                            </td>
                            <td>
                                <a href="{{ $_route . '_course=' . base64_encode($_course->id) }}&view=alumnia">
                                    {{ count($_course->applicant_alumnia) }}
                                </a>
                            </td>
                            <td>
                                {{ count($_course->applicant_payment_verification_v2) }}
                                <a href="{{ $_route . '_course=' . base64_encode($_course->id) }}&view=entrance-examination-payment-verification">
                                    {{ count($_course->applicant_payment_verification) }}
                                </a>
                            </td>
                            <td>
                                <a href="{{ $_route . '_course=' . base64_encode($_course->id) }}&view=entrance-examination-payment-verified">
                                    {{ count($_course->applicant_payment_verified) }}
                                </a>
                            </td>
                            {{-- <td>
                                    <a
                                        href="{{ route('applicant-examination-status') . '?_course=' . base64_encode($_course->id) . '&_status=' . base64_encode('ready-for-examination') }}{{ request()->input('_academic') ? '&_academic=' . request()->input('_academic') : '' }}">{{ count($_course->applicant_examination_ready) }}</a>
                            </td> --}}
                            @if (Auth::user()->email == 'p.banting@bma.edu.ph' || Auth::user()->email == 'k.j.cruz@bma.edu.ph')
                            <td>
                                <a href="{{ $_route . '_course=' . base64_encode($_course->id) }}&view=ongoing-examination">
                                    {{ count($_course->applicant_examination_ongoing) }}
                                </a>
                            </td>
                            @endif
                            <td>
                                <a href="{{ $_route . '_course=' . base64_encode($_course->id) }}&view=entrance-examination-passer">
                                    {{ count($_course->applicant_examination_passed) }}
                                </a>
                            </td>
                            <td>
                                <a href="{{ $_route . '_course=' . base64_encode($_course->id) }}&view=examination-failed">
                                    {{ count($_course->applicant_examination_failed) }}
                                </a>
                            </td>
                            <td>
                                <a href="{{ $_route . '_course=' . base64_encode($_course->id) }}&view=briefing-orientation">
                                    {{ count($_course->applicant_virtual_orientation) }}
                                </a>
                            </td>
                            <td>
                                <a href="{{ $_route . '_course=' . base64_encode($_course->id) }}&view=medical-appointment">
                                    {{ count($_course->applicant_medical_appointment) }}
                                </a>
                            </td>
                            <td>
                                <a href="{{ $_route . '_course=' . base64_encode($_course->id) }}&view=medical-scheduled">
                                    {{ count($_course->applicant_medical_scheduled) }}
                                </a>
                            </td>
                            <td>

                                <a href="{{ $_route . '_course=' . base64_encode($_course->id) }}&view=medical-results">
                                    {{ count($_course->applicant_medical_results) }}
                                </a>
                            </td>
                            <td>
                                <a href="{{ $_route . '_course=' . base64_encode($_course->id) }}&view=qualified">{{ count($_course->applicant_qualified_to_enrolled) }}</a>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</section>



@section('js')
<script>
    $(document).on('click', '.course-btn', function() {
        var data = $(this).data('course')
        $.get('applicant-list?course=' + data, function(respond) {
            respond.applicant.forEach(element => {
                $.get('applicant/notification?_applicant=' + element.id, function(respond) {
                    if (respond.data.respond == '200') {
                        console.info(respond.data.message)
                    }
                })
            });
        })
    })
</script>
@endsection