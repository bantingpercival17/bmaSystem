<div class="card">
    <div class="card-header pb-0 p-3">
        <h5 class="mb-1"><b>ENROLLMENT HISTORY</b></h5>
    </div>
    <div class="card-body">
        @if (count($_student->enrollment_history))
        @foreach ($_student->enrollment_history as $item)
        <div class="account-list">
            <div class="row">
                <div class="col-md-6">
                    <small class="fw-bolder">
                        SCHOOL ACADEMIC
                    </small> <br>
                    <label for="" class="text-primary fw-bolder">
                        {{ strtoupper($item->academic->semester . ' - ' . $item->academic->school_year) }}
                    </label>
                </div>
                <div class="col-md-3">
                    <small class="fw-bolder">
                        SECTION
                    </small> <br>
                    @php
                    $student_section = $_student->section($item->academic_id)->first();
                    $route = $student_section ? route('registrar.semestral-grade-form-ad2') . '?student=' . base64_encode($_student->id) . '&_section=' . base64_encode($student_section->section->id) . '&_academic=' . base64_encode($item->academic_id) : '';
                    @endphp
                    <label for="" class="badge bg-secondary">
                        {{ $student_section ? $student_section->section->section_name : 'NO SECTION' }}
                    </label>
                </div>
                <div class="col-md-3">
                    <small class="fw-bolder">
                        PRINT COG
                    </small> <br>
                    @php
                    $student_section = $_student->section($item->academic_id)->first();
                    @endphp
                    <a href="{{ $route }}" class="badge bg-primary">PRINT</a>
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
                <div class="col-md">
                    <small class="fw-bolder">
                        CURRICULUM
                    </small> <br>
                    <label for="" class="badge bg-primary">
                        {{ strtoupper($item->curriculum->curriculum_name) }}
                    </label>
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