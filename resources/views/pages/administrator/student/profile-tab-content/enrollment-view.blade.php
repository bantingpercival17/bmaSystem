<div class="card">
    <div class="card-header pb-0 p-3">
        <h5 class="mb-1 text-primary"><b>ENROLLMENT STATUS</b></h5>
    </div>
    <div class="card-body">
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
                    <a href="{{ route('registrar.student-information-report') }}?_assessment={{ base64_encode($item->id) }}" class="badge bg-info" target="_blank">PRINT</a>
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