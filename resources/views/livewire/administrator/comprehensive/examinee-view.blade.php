@php
    $pageTitle = 'COMPREHENSIVE EXAMINEE';
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
                            class="card-img" alt="#">
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
                                </small>

                            </p>

                        </div>
                    </div>
                </div>
            </div>
            @if ($profile)
                <div class="examination-list">
                    @foreach ($competence as $item)
                        <div class="card m-2">
                            <div class="card-body">
                                <small for=""
                                    class="text-muted fw-bolder">{{ strtoupper($item->function) }}</small>
                                <p class="text-primary fw-bolder">
                                    {{ strtoupper($item->competence_code . ' - ' . $item->competence_name) }}
                                </p>
                                <div class="examination-result">
                                    <table class="table">
                                        <thead>
                                            <tr>
                                                <th>ATTEMP NO.</th>
                                                <th>RESULT</th>
                                                <th>DATE</th>
                                                <th>ACTION</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @if ($profile->comprehensive_examination->examination_attemp($item->id)->get())
                                                @foreach ($profile->comprehensive_examination->examination_attemp($item->id)->get() as $key => $results)
                                                    <tr>
                                                        <td>{{ $key + 1 }}</td>
                                                        <td><b>{{ $results->result }}</b></td>
                                                        <td>{{ $results->created_at }}</td>
                                                        <td>
                                                            @if (Auth::user()->email == 'p.banting@bma.edu.ph')
                                                                <a href="{{ route('admin.comprehensive-examination-removed') }}?examinee={{ base64_encode($results->id) }}"
                                                                    class="badge bg-secondary">remove</a>
                                                            @endif

                                                        </td>
                                                    </tr>
                                                    {{--  <div class="row">
                                                        <div class="col-md">
                                                            Attemp {{ $key + 1 }} : <b>{{ $results->result }}</b>
                                                        </div>
                                                        <div class="col-md">
                                                            <button class="btn btn-sm btn-secondary">ACTION</button>
                                                        </div>
                                                    </div>
                                                    <hr> --}}
                                                @endforeach
                                            @else
                                                <tr>
                                                    <td colspan="4">NO ATTEMP</td>
                                                </tr>
                                                No Attemps
                                            @endif
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif

        </div>
        <div class="col-md-4">
            <form>
                <label for="" class="text-primary fw-bolder">SEARCH STUDENT</label>
                <div class="form-group search-input">
                    <input type="search" class="form-control border border-primary" placeholder="Search..."
                        wire:model="inputStudent">
                </div>
                <div class=" d-flex justify-content-between mb-2">
                    <h6 class=" fw-bolder text-muted">
                        @if ($inputStudent != '')
                            Search Result: <span class="text-primary">{{ $inputStudent }}</span>
                        @else
                            {{ strtoupper('Recent Enrollee') }}
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
                        href="{{ route('student.view') }}?student={{ base64_encode($item->id) }}{{ request()->input('_academic') ? '&_academic=' . request()->input('_academic') : '' }}">
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
