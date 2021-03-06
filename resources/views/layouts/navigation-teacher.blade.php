@section('teacher-navbar')
    <div class="nav-scroller text-center">
        @php
            $_routes = [['name' => 'Lesson', 'route' => 'teacher.subject-view'], ['name' => 'Student', 'route' => 'teacher.subject-class-students'], ['name' => 'Semestral Clearance', 'route' => 'teacher.semestral-clearance'], ['name' => 'Grading Sheet', 'route' => 'teacher.grading-sheet']];
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
@endsection
