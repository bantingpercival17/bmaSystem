@section('administrator-side')
    @php
    $_role = 'administrator';
    $_array_link = [['Dashboard', 'fa-tachometer-alt', $_role . '/dashboard'], ['Enrollment', 'fa-clipboard', $_role . '/enrollment'], ['Student', 'fa-user', $_role . '/students'], ['Accounts', 'fa-users', $_role . '/accounts'], ['Attendance', 'fa-clock', $_role . '/attendance'], ['Subjects', 'fa-clipboard-list', $_role . '/subjects'], ['Section', 'fa-chalkboard-teacher', $_role . '/classes'], ['Paymongo', 'fa-money-bill-alt', $_role . '/paymongo']];
    @endphp
    @foreach ($_array_link as $_nav_item)
        <li class="nav-item">
            <a href="/{{ $_nav_item[2] }}" class="nav-link  {{ Request::is($_nav_item[2] . '/*') ? 'active' : '-' }}">
                <i class="nav-icon  fas {{ $_nav_item[1] }}"></i>
                <p>
                    {{ $_nav_item[0] }}
                </p>
            </a>
        </li>
    @endforeach

    {{-- <li class="nav-item">
        <a href="/administrator/examination" class="nav-link active">
            <i class="nav-icon  fas fa-file"></i>
            <p>
                Examination
            </p>
        </a>
    </li> --}}
@endsection

@section('accounting-side')
    @php
    $_role = 'accounting';
    $_array_link = [['Dashboard', 'fa-tachometer-alt', $_role . '/dashboard'], ['Assessment Fee', 'fa-clipboard', $_role . '/assessment-fee'], ['Payments', 'fa-user', $_role . '/payments'], ['Fees', 'fa-money-bill-alt', $_role . '/fees']];
    @endphp
    @foreach ($_array_link as $_nav_item)
        <li class="nav-item">
            <a href="/{{ $_nav_item[2] }}" class="nav-link {{ request()->is($_nav_item[2]) ? 'active' : '' }} ">
                <i class="nav-icon  fas {{ $_nav_item[1] }}"></i>
                <p>
                    {{ $_nav_item[0] }}
                </p>
            </a>
        </li>
    @endforeach
@endsection

@section('teacher-side')
    @php
    $_role = '/teacher';
    $_position = Auth::user()->staff->job_description;

    $_array_link = [['Subject', 'fa-clipboard-list', $_role . '/subjects'], ['Previous Subject', 'fa-clipboard', $_role . '/previous-subjects'] /* , ['Section', 'fa-chalkboard-teacher', $_role . '/sections'] */];

    @endphp
    @foreach ($_array_link as $_nav_item)
        <li class="nav-item">
            <a href="{{ $_nav_item[2] }}" class="nav-link  {{ Request::is($_nav_item[2] . '/*') ? 'active' : '' }}">
                <i class="nav-icon  fas {{ $_nav_item[1] }}"></i>
                <p>
                    {{ $_nav_item[0] }}
                </p>
            </a>
        </li>
    @endforeach
    @if ($_position == 'Department Head' || $_position == 'DEPARTMENT HEAD')
        <li class="nav-item">
            <a href="#" class="nav-link">
                <i class="nav-icon fas fa-copy"></i>
                <p>
                    Grade Submission
                    <i class="fas fa-angle-left right"></i>
                </p>
            </a>
            <ul class="nav nav-treeview" style="display: none;">
                <li class="nav-item">
                    <a href="/teacher/grade-reports?_form=ad1&_period=midterm&_academic={{ Crypt::encrypt(3) }}"
                        class="nav-link">
                        <i class="far fa-circle nav-icon"></i>
                        <p>FORM AD-01 (MIDTERM)</p>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="/teacher/grade-reports?_form=ad1&_period=finals&_academic={{ Crypt::encrypt(3) }}"
                        class="nav-link">
                        <i class="far fa-circle nav-icon"></i>
                        <p>FORM AD-01 (FINALS)</p>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="/teacher/grade-reports?_form=ad2&_academic" class="nav-link">
                        <i class="far fa-circle nav-icon"></i>
                        <p>FORM AD-02</p>
                    </a>
                </li>
            </ul>
        </li>
    @endif

@endsection



@section('executive-side')
    @php
    $_role = 'executive';
    $_array_link = [['Attendace', 'fa-clock', $_role . '/attendance'],['Scanner','fa-scan',$_role.'/attendance-checker']];
    @endphp
    @foreach ($_array_link as $_nav_item)
        <li class="nav-item">
            <a href="/{{ $_nav_item[2] }}" class="nav-link {{ request()->is($_nav_item[2]) ? 'active' : '' }} ">
                <i class="nav-icon  fas {{ $_nav_item[1] }}"></i>
                <p>
                    {{ $_nav_item[0] }}
                </p>
            </a>
        </li>
    @endforeach
@endsection
