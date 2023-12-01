@php
    $pageTitle = 'Midshipman Information';
    $courseColor = 'bg-secondary';
@endphp
@section('page-title', $pageTitle)
<div>
    <div class="row">
        <div class="col-md-8">
            <p class="display-6 fw-bolder text-primary">{{ strtoupper($pageTitle) }}</p>
            <div class="card mb-2 shadow">
                <div class="row no-gutters">
                    <div class="col-md-3">
                        <img src="{{ $profile ? $profile->profile_picture() : 'http://bma.edu.ph/img/student-picture/midship-man.jpg' }}"
                            class="card-img" alt="student-image">
                    </div>
                    <div class="col-md ps-0">
                        <div class="card-body p-3 me-2">
                            <label for=""
                                class="fw-bolder text-primary h4">{{ $profile ? strtoupper($profile->last_name . ', ' . $profile->first_name) : 'MIDSHIPMAN NAME' }}</label>
                            <p class="mb-0">
                                <small class="fw-bolder badge {{ $courseColor }}">
                                    {{ $profile ? ($profile->account ? $profile->account->student_number : 'NEW STUDENT') : 'STUDENT NUMBER' }}
                                </small> |
                                <small class="fw-bolder badge {{ $courseColor }}">
                                    {{ $profile ? ($profile->enrollment_assessment ? $profile->enrollment_assessment->course->course_name : 'COURSE') : 'COURSE' }}
                                </small> |
                                <small class="fw-bolder badge {{ $courseColor }}">
                                    {{ $profile ? ($profile->enrollment_assessment ? strtoupper(Auth::user()->staff->convert_year_level($profile->enrollment_assessment->year_level)) : 'YEAR LEVEL') : 'YEAR LEVEL' }}
                                </small>
                            </p>
                            <p class="mb-0">
                                <small class="fw-bolder badge {{ $courseColor }}">
                                    {{ $profile ? ($profile->enrollment_assessment ? $profile->enrollment_assessment->curriculum->curriculum_name : 'NO CURRICULUM') : 'CURRICULUM' }}
                                </small>
                                @if ($profile)
                                    @if ($profile->enrollment_assessment)
                                        @if ($profile->enrollment_assessment->enrollment_category == 'SBT ENROLLMENT')
                                            |
                                            <small class="fw-bolder badge bg-secondary">
                                                {{ strtoupper($profile->shipboard_training->shipping_company) }}
                                            </small>
                                        @endif
                                    @endif
                                @endif

                            </p>
                        </div>
                    </div>
                </div>
            </div>
            @if ($profile)
                <nav class="nav nav-underline bg-soft-primary pb-0 text-center" aria-label="Secondary navigation">

                    <div class="d-flex" id="head-check">
                        @foreach ($subHeaders as $item)
                            <a class="nav-link {{ $activeCard == $item[0] ? 'active' : 'text-muted' }}"
                                wire:click="swtchTab('{{ $item[0] }}')">
                                {{ strtoupper(str_replace('-', ' ', $item[0])) }}
                            </a>
                        @endforeach
                        <!-- <a class="nav-link  {{ $activeCard == 'enrollment' ? 'active' : 'text-muted' }}">ENROLLMENT</a>
                    <a class="nav-link   {{ $activeCard == 'account' ? 'active' : 'text-muted' }}">ACCOUNT</a>
                    <a class="nav-link   {{ $activeCard == 'grades' ? 'active' : 'text-muted' }}">CERTIFICATE OF GRADE</a> -->
                    </div>
                </nav>
                <div class="mt-4">
                    @foreach ($subHeaders as $item)
                        @if ($activeCard == $item[0])
                            @include($item[1])
                        @endif
                    @endforeach

                </div>
            @endif

        </div>
        <div class="col-md-4">
            <form>
                <label for="" class="text-primary fw-bolder">SEARCH MIDSHIPMAN</label>
                <div class="form-group search-input">
                    <input type="search" class="form-control border border-primary" placeholder="Search..."
                        wire:model="inputStudent">
                </div>
                <div class=" d-flex justify-content-between mb-2">
                    <h6 class=" fw-bolder text-muted">
                        @if ($inputStudent != '')
                            Search Result: <span class="text-primary">{{ $inputStudent }}</span>
                        @else
                            {{ strtoupper('') }}
                        @endif
                    </h6>
                    <span class="text-muted h6">
                        No. Result: <b>{{ count($studentLists) }}</b>
                    </span>

                </div>
            </form>
            <div class="student-list">
                @forelse ($studentLists as $item)
                    <a
                        href="{{ route('onboard.midshipman-v2') }}?student={{ base64_encode($item->id) }}{{ request()->input('_academic') ? '&_academic=' . request()->input('_academic') : '' }}">
                        <div class="card mb-2 shadow shadow-info">
                            <div class="row no-gutters">
                                <div class="col-md-4">
                                    <img src="{{ $item ? $item->profile_picture() : 'http://bma.edu.ph/img/student-picture/midship-man.jpg' }}"
                                        class="avatar-100 rounded card-img" alt="student-image">
                                </div>
                                <div class="col-md p-1">
                                    <div class="card-body p-2">
                                        <small
                                            class="text-primary fw-bolder">{{ strtoupper($item->last_name . ', ' . $item->first_name) }}</small>
                                        <br>
                                        <small
                                            class="badge {{ $item->enrollment_assessment ? $item->enrollment_assessment->color_course() : 'bg-secondary' }} ">{{ $item->enrollment_assessment ? $item->enrollment_assessment->course->course_code : '-' }}</small>
                                        -
                                        <span>{{ $item->account ? $item->account->student_number : 'NEW STUDENT' }}</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </a>

                @empty
                    <div class="card mb-2">
                        <div class="row no-gutters">
                            <div class="col-md">
                                <div class="card-body ">
                                    <small class="text-primary fw-bolder">NOT FOUND</small>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforelse
            </div>
        </div>
    </div>
</div>
