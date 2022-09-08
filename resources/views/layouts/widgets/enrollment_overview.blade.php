@php
$_url_role = ['administrator/dashboard', 'accounting/dashboard', 'registrar/dashboard'];
$_course_enrolled = ['admin.course-enrolled', 'accounting.course-enrolled', 'registrar.course-enrolled'];

$_course_url = route($_course_enrolled[0]);
foreach ($_url_role as $key => $_data) {
    $_course_url = request()->is($_data . '*') ? route($_course_enrolled[$key]) : $_course_url;
    //$_course_url = $value; //request()->is(route($value))
}
@endphp
<section>
    <p class="display-6 fw-bolder text-primary">Enrollment Overview</p>
    <div class="row">
        <div class="col-md-3">
            <div class="card  iq-purchase" data-iq-gsap="onStart" data-iq-position-y="50" data-iq-rotate="0"
                data-iq-trigger="scroll" data-iq-ease="power.out" data-iq-opacity="0">
                <div class="card-body">
                    <div class="d-flex align-items-center justify-content-between">
                        <h3 class="counter">{{ count($_total_population) }}</h3>
                        {{-- {{$_course->enrollment_list}} --}}
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
                            Total Population
                        </h5>

                    </div>

                </div>
            </div>
        </div>
        @foreach ($_courses as $_course)
            {{-- <div class="col-md-3">
                <a
                    href="{{ route('enrollment.enrolled-list') }}?_course={{ base64_encode($_course->id) }}{{ request()->input('_academic') ? '&_academic=' . request()->input('_academic') : '' }}">
                    <div class="card  iq-purchase" data-iq-gsap="onStart" data-iq-position-y="50" data-iq-rotate="0"
                        data-iq-trigger="scroll" data-iq-ease="power.out" data-iq-opacity="0">
                        <div class="card-body">
                            <div class="d-flex align-items-center justify-content-between">
                                <h3 class="counter">
                                    {{ count($_course->enrollment_list) }}
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
                            @php
                                
                                $_level = [4, 3, 2, 1];
                                $_level = $_course->id == 3 ? [11, 12] : $_level;
                                $_course_color = $_course->id == 1 ? 'text-primary' : '';
                                $_course_color = $_course->id == 2 ? 'text-info' : $_course_color;
                                $_course_color = $_course->id == 3 ? 'text-warning' : $_course_color;
                            @endphp
                            @foreach ($_level as $item)
                                <div class="d-flex align-items-center justify-content-between mb-2">
                                    <h5 class="text-muted">
                                        {{ Auth::user()->staff->convert_year_level($item) }}
                                    </h5>
                                    <h5 class="fw-bolder text-primary">
                                        {{ count($_course->enrollment_list_by_year_level($item)->get()) }}
                                    </h5>
                                </div>
                            @endforeach

                        </div>
                    </div>
                </a>
            </div> --}}
            <div class="col-md">
                <a
                    href="{{ route('enrollment.enrolled-list') }}?_course={{ base64_encode($_course->id) }}{{ request()->input('_academic') ? '&_academic=' . request()->input('_academic') : '' }}">
                    <div class="card">
                        <div class="card-body">
                            @php
                                $_level = [4, 3, 2, 1];
                                $_level = $_course->id == 3 ? [11, 12] : $_level;
                                $_course_color = $_course->id == 1 ? 'text-primary' : '';
                                $_course_color = $_course->id == 2 ? 'text-info' : $_course_color;
                                $_course_color = $_course->id == 3 ? 'text-warning' : $_course_color;
                            @endphp
                            <div class="d-flex justify-content-between">
                                <div>
                                    <div>
                                        <h2 class="counter fw-bolder text-muted" style="visibility: visible;">
                                            {{ count($_course->enrollment_list) }}</h2>
                                    </div>
                                </div>
                                <div>
                                    <span><b class="badge bg-primary">{{ $_course->course_code }}</b></span>
                                </div>
                            </div>
                            @foreach ($_level as $item)
                                <div class="d-flex justify-content-between mt-2">
                                    <div>
                                        <span> {{ Auth::user()->staff->convert_year_level($item) }}</span>
                                    </div>
                                    <div>
                                        <span class="counter text-muted fw-bolder" style="visibility: visible;">
                                            {{ count($_course->enrollment_list_by_year_level($item)->get()) }}</span>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </a>

            </div>
        @endforeach
    </div>
</section>


<div class="card">
    <div class="card-header d-flex justify-content-between">
        <div class="header-title">
            <h4 class="card-title">Summary Overview</h4>
        </div>
    </div>
    <div class="card-body p-0">
        <div class="table-responsive mt-4">
            <table id="basic-table" class="table table-striped mb-0" role="grid">
                <thead>
                    <tr class="text-center">
                        <th>COURSE</th>
                        <th>EXPECTED <br> ENROLLEE</th>
                        <th>NOT <br> CLEARED</th>
                        <th>CLEARED</th>
                        <th>ENROLLMENT <br> ASSESSMENT</th>
                        @if (Auth::user()->staff->current_academic()->semester == 'First Semester')
                            <th>BRIDGING <br> PROGRAM</th>
                        @endif
                        <th>FOR <br> ASSESSMENT</th>
                        <th>PAYMENT <br> VERIFICATION</th>
                        <th>TOTAL <br> ENROLLED</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($_courses as $_course)
                        <tr>
                            <td>{{ $_course->course_name }}</td>
                            <td>
                                {{ count($_course->previous_enrolled) }}
                            </td>
                            <td>
                                <a
                                    href="{{ route('registrar.dashboard-student-clearance-list') . '?_course=' . base64_encode($_course->id) }}&_clearance_status=not-cleared">
                                    {{ count($_course->students_not_clearance) }}
                                </a>
                            </td>
                            <td>
                                <a
                                    href="{{ route('registrar.dashboard-student-clearance-list') . '?_course=' . base64_encode($_course->id) }}">
                                    {{ count($_course->students_clearance) }}
                                </a>
                            </td>

                            <td>
                                <a
                                    href="{{ route('enrollment.view') }}?_course={{ base64_encode($_course->id) }}">{{ count($_course->enrollment_application) }}</a>
                            </td>
                            @if (Auth::user()->staff->current_academic()->semester == 'First Semester')
                                @if ($_course->id != 3)
                                    <td>
                                        <a
                                            href="{{ route('registrar.bridging-program') }}?_course={{ base64_encode($_course->id) }}">
                                            {{ count($_course->student_bridging_program) }}</a>
                                    </td>
                                @else
                                    <td>
                                        -
                                    </td>
                                @endif
                            @endif
                            <td>
                                <a
                                    href="{{ route('enrollment.payment-assessment') . '?_course=' . base64_encode($_course->id) }}">
                                    {{ count($_course->payment_assessment) }}
                                </a>
                            </td>
                            <td>
                                <a
                                    href="{{ route('accounting.dashboard-payment-assessment') . '?_course=' . base64_encode($_course->id) }}&_payment=true">
                                    {{ count($_course->payment_transaction) }}
                                </a>
                            </td>
                            <td>
                                <a
                                    href="{{ route('enrollment.enrolled-list') }}?_course={{ base64_encode($_course->id) }}&_academic={{ request()->input('_academic') }}">
                                    {{ count($_course->enrollment_list) }}
                                </a>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        <div class="table-responsive mt-4">
            @php
                $_title = ['EXPECTED ENROLLEE', 'NOT CLEARED', 'CLEARED', 'ENROLLMENT ASSESSMENT', 'BRIDGING PROGRAM', 'TUITION FEE ASSESSMENT', 'TUITION FEE PAYMENT', 'PAYMENT VERIFICATION','PAYMENT VERIFICATION [DISAPPROVED]', 'TOTAL ENROLLED'];
            @endphp
            <table id="basic-table" class="table table-striped mb-0" role="grid">
                <thead>
                    <tr>
                        <th>COURSE</th>
                        @foreach ($_courses as $course)
                            <th class="text-center"colspan="{{ $course->id == 3 ? 2 : 4 }}">
                                {{ $course->course_name }}</th>
                        @endforeach
                    </tr>
                    <tr>
                        <th>YEAR LEVEL</th>
                        @foreach ($_courses as $course)
                            @php
                                $_level = [4, 3, 2, 1];
                                $_level = $course->id == 3 ? [11, 12] : $_level;
                                $_course_color = $course->id == 1 ? 'text-primary' : '';
                                $_course_color = $course->id == 2 ? 'text-info' : $_course_color;
                                $_course_color = $course->id == 3 ? 'text-primary' : $_course_color;
                            @endphp
                            @foreach ($_level as $level)
                                <th class="{{ $_course_color }}">
                                    {{ $course->id == 3 ? 'G' . $level : $level . '/C' }}</th>
                            @endforeach
                        @endforeach
                    </tr>

                </thead>
                <tbody>
                    @foreach ($_title as $item)
                        <tr>
                            <th>{{ $item }}</th>
                            @foreach ($_courses as $course_1)
                                @php
                                    $_level = [4, 3, 2, 1];
                                    $_level = $course_1->id == 3 ? [11, 12] : $_level;
                                    $_course_color = $course_1->id == 1 ? 'text-primary' : '';
                                    $_course_color = $course_1->id == 2 ? 'text-info' : $_course_color;
                                    $_course_color = $course_1->id == 3 ? 'text-primary' : $_course_color;
                                    
                                @endphp
                                @foreach ($_level as $level)
                                    @php
                                        //'ENROLLMENT ASSESSMENT', 'BRIDGING PROGRAM',
                                        $_function = [];
                                        $_function = $item == 'EXPECTED ENROLLEE' ? $course_1->expected_enrollee_year_level($level)->get() : $_function;
                                        $_function = $item == 'NOT CLEARED' ? $course_1->students_not_clearance_year_level($level)->get() : $_function;
                                        $_function = $item == 'ENROLLMENT ASSESSMENT' ? $course_1->enrollment_assessment_year_level($level)->get() : $_function;
                                        $_function = $item == 'BRIDGING PROGRAM' ? $course_1->student_bridging_program_year_level($level)->get() : $_function;
                                        $_function = $item == 'TUITION FEE ASSESSMENT' ? $course_1->payment_assessment_sort($level)->get() : $_function;
                                        $_function = $item == 'TUITION FEE PAYMENT' ? $course_1->payment_transaction_year_level($level)->get() : $_function;
                                        $_function = $item == 'PAYMENT VERIFICATION' ? $course_1->payment_transaction_online_year_level($level)->get() : $_function;
                                        $_function = $item == 'PAYMENT VERIFICATION [DISAPPROVED]' ? $course_1->payment_transaction_online_status_year_level($level)->get() : $_function;
                                        $_function = $item == 'TOTAL ENROLLED' ? $course_1->enrollment_list_by_year_level($level)->get() : $_function;
                                        
                                        $value = count($_function);
                                    @endphp
                                    <th><a href="{{ route('enrollment.status') . '?_course=' . base64_encode($course_1->id) . '&level=' . $level . '&category=' . strtolower($item) }}{{ request()->input('_academic') ? '&_academic=' . request()->input('_academic') : '' }}"
                                            class="{{ $_course_color }}">{{ $value }}</a></th>
                                @endforeach
                            @endforeach
                        </tr>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
@section('js')
    <script>
        @if (Auth::user()->staff)
            @if (count(Auth::user()->staff->message_ticket_concern()) > 0)
                Toastify({
                    text: "You have  {{ count(Auth::user()->staff->message_ticket_concern()) }} unread concern, <a href='{{ route('ticket.view') }}' class='text-warning'> see here </a> ",
                    //duration: 3000,
                    //close: true,
                    //gravity: "top",
                    position: "right",
                    backgroundColor: "#4fbe87",
                }).showToast();
            @endif
        @endif
    </script>
@endsection
