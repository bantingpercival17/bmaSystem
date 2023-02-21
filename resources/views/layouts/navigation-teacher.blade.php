@section('teacher-navbar')
    <div class="nav-scroller text-center mt-0">
        @php
            $grade_route = $_subject->curriculum_subject->subject->subject_code == 'NSTP 1' && $_subject->curriculum_subject->subject->subject_code == 'NSTP 1' ? 'teacher.grade-sheet-special' : 'teacher.grade-sheet';
            $_routes = [['name' => 'Lesson', 'route' => 'teacher.subject-view'], ['name' => 'Student', 'route' => 'teacher.subject-class-students'], ['name' => 'Semestral Clearance', 'route' => 'teacher.semestral-clearance'], ['name' => 'Grading Sheet', 'route' => $grade_route]];
        @endphp
        <nav class="nav nav-underline bg-soft-primary  pb-0" aria-label="Secondary navigation ">
            <div class="d-flex" id="head-check">
                @foreach ($_routes as $_route)
                    <a href="{{ route($_route['route']) }}?_subject={{ base64_encode($_subject->id) }}{{ $_route['name'] == 'Grading Sheet' ? '&_period=midterm' : '' }}"
                        class="nav-link {{ request()->routeIs($_route['route']) ? 'active' : '' }}">{{ $_route['name'] }}</a>
                @endforeach
            </div>
        </nav>
    </div>
    <div class="row m-3">
        <div class="col-md">
            <small class="text-muted">SUBJECT NAME</small> <br>
            <label class="text-primary fw-bolder h6">{{ $_subject->curriculum_subject->subject->subject_name }}</label>
        </div>
        <div class="col-md">
            <small class="text-muted">SUBJECT CODE</small> <br>
            <label class="text-primary fw-bolder h6">{{ $_subject->curriculum_subject->subject->subject_code }}</label>
        </div>
    </div>
@endsection
