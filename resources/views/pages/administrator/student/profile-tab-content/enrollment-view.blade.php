<div class="card">
    <div class="card-header pb-0 p-3">
        <h5 class="mb-1 text-primary"><b>ENROLLMENT STATUS</b></h5>
    </div>
    <div class="card-body">
        @if ($_student->enrollment_application_v2)
            @if ($_enrollment_status = $_student->enrollment_application_status($_student->enrollment_application_v2->academic)->first())
                @if ($_enrollment_status->payment_assessments)
                    @if ($_enrollment_status->payment_assessments->payment_assessment_paid)
                        @if ($_enrollment_status->enrollment_cancellation)
                            <p class="text-danger fw-bolder">ENROLLMENT CANCELLED</p>
                            <p>{{ $_enrollment_status->enrollment_cancellation->date_of_cancellation }}</p>
                        @else
                            <form action="{{ route('registrar.enrollment_cancellation') }}"
                                id="form-enrollment-cancellation" method="post" class="mb-5"
                                enctype="multipart/form-data">
                                @csrf
                                <input type="hidden" name="enrollment" value="{{ $_enrollment_status->id }}">
                                <label for="" class="text-primary fw-bolder">ENROLLMENT CANCELLATION</label>
                                <div class="form-group">
                                    <small class="form-label fw-bolder">TYPE OF CANCELLATION</small>
                                    <select name="type" id="" class="form-select">
                                        <option value="dropped" {{ old('type') == 'dropped' ? 'selected' : '' }}>Dropping
                                            Form
                                        </option>
                                        <option value="withdrawn" {{ old('type') == 'withdrawn' ? 'selected' : '' }}>
                                            Withdrawal Form</option>
                                    </select>
                                    @error('type')
                                        <small class="badge bg-danger mt-3">{{ $message }}</small>
                                    @enderror
                                </div>
                                <div class="form-group">
                                    <small class="form-label fw-bolder">DATE OF CANCELLATION</small>
                                    <input type="date" name="date" id="" class="form-control"
                                        value="{{ old('date') }}">
                                    @error('date')
                                        <small class="badge bg-danger mt-3">{{ $message }}</small>
                                    @enderror
                                </div>
                                <div class="form-group">
                                    <small class="form-label fw-bolder">CANCELLATION EVIDENCE</small>
                                    <input type="file" name="file" id="" class="form-control">
                                    @error('file')
                                        <small class="badge bg-danger mt-3">{{ $message }}</small>
                                    @enderror
                                </div>
                                <button type="button" class="btn btn-sm btn-primary btn-cancellation w-100"
                                    data-form="form-enrollment-cancellation">SUBMIT</button>
                            </form>
                        @endif
                    @endif
                @endif
            @endif
        @endif
        @include('pages.administrator.student.components')
        <div class="iq-timeline0 m-0 d-flex align-items-center justify-content-between position-relative">
            @yield('enrollment-step')

        </div>
    </div>
</div>
<div class="card">
    <div class="card-header pb-0 p-3">
        <h5 class="mb-1"><b>ENROLLMENT HISTORY</b></h5>
    </div>
    <div class="card-body">
        @if (count($_student->enrollment_history))
            @foreach ($_student->enrollment_history as $item)
                <div class="account-list">
                    <div class="row">
                        <div class="col-md-8">
                            <small class="fw-bolder">
                                SCHOOL ACADEMIC
                            </small> <br>
                            <label for="" class="text-primary fw-bolder">
                                {{ strtoupper($item->academic->semester . ' - ' . $item->academic->school_year) }}
                            </label>
                        </div>
                        <div class="col-md">
                            <small class="fw-bolder">
                                ENROLLMENT DATE
                            </small> <br>
                            <label for="" class="badge bg-secondary">
                                {{ $item->payment_assessments ? $item->payment_assessments->created_at->format('F d,Y') : '' }}
                            </label>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-4">
                            <small class="fw-bolder">
                                COURSE / STRAND
                            </small> <br>
                            <label for="" class="badge bg-primary">
                                {{ $item->course->course_name }}
                            </label>
                        </div>
                        <div class="col-md-3">
                            <small class="fw-bolder">
                                YEAR LEVEL
                            </small> <br>
                            <label for="" class="badge bg-primary">
                                {{ strtoupper(Auth::user()->staff->convert_year_level($item->year_level)) }}
                            </label>
                        </div>
                        <div class="col-md-3">
                            <small class="fw-bolder">
                                CURRICULUM
                            </small> <br>
                            <label for="" class="badge bg-primary">
                                {{ strtoupper($item->curriculum->curriculum_name) }}
                            </label>
                        </div>
                        <div class="col-md-2">
                            <small class="fw-bolder">
                                COR
                            </small> <br>
                            <a href="{{ route('registrar.student-information-report') }}?_assessment={{ base64_encode($item->id) }}"
                                class="badge bg-info" target="_blank">PRINT</a>
                        </div>
                    </div>

                </div>

                <hr>
            @endforeach
        @else
            <div class="enrollment-list row">
                <label for="" class="fw-bolder text-muted">NO ENROLLMENT DETIALS</label>
            </div>
        @endif
    </div>
</div>

</div>
@section('js')
    <script>
        $('.btn-cancellation').click(function(event) {
            Swal.fire({
                title: 'Enrollment Cancellation',
                text: "Do you want to submit?",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes'
            }).then((result) => {
                var form = $(this).data('form');
                if (result.isConfirmed) {
                    console.log(form)
                    document.getElementById(form).submit()
                    //$('#' + form).submit();
                }
            })
            event.preventDefault();
        })
    </script>
@endsection
