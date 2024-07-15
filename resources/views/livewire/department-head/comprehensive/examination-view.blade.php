@php
    $pageTitle = 'Comprehensive Examination';
@endphp
@section('page-title', $pageTitle)
<div>
    <div class="row">
        <div class="col-lg-8">
            <p class="display-6 fw-bolder text-primary">{{ strtoupper($pageTitle) }}</p>
            @if ($addExaminationContent)
                <div class="content-body">
                    @foreach ($courses as $course)
                        <div class="card">
                            <div class="card-header">
                                <label for="" class="fw-bolder text-primary h4">{{ $course->course_name }}</label>
                            </div>
                            <div class="card-body">
                                @if ($course->comprehensive_examination)
                                    <table class="table">
                                        <thead>
                                            <tr>
                                                <th>COMPETENCE CODE</th>
                                                <th>COMPETENCE NAME</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($course->comprehensive_examination as $compentence)
                                                <tr>
                                                    <td>{{ $compentence->competence_code }}</td>
                                                    <td>{{ $compentence->competence_name }}</td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                @endif
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <div class="data-list">
                    @forelse ($examinees as $item)
                        <a
                            href="{{ route('admin.comprhensive-examinee') }}?student={{ base64_encode($item->student->id) }}">
                            <div class="card">
                                <div class="row no-gutters">
                                    <div class="col-md-3">
                                        <img src="{{ $item->student ? $item->student->profile_picture() : 'http://bma.edu.ph/img/student-picture/midship-man.jpg' }}"
                                            class="card-img" alt="#">
                                    </div>
                                    <div class="col-md">
                                        <div class="card-body p-3 me-2">

                                            <label for=""
                                                class="fw-bolder text-primary h4">{{ $item->student ? $item->student->complete_name() : 'MIDSHIPMAN NAME' }}</label>
                                            <p class="mb-0">
                                                <small class="fw-bolder badge bg-secondary">
                                                    {{ $item->student ? ($item->student->account ? $item->student->account->student_number : 'STUDENT NO.') : 'NEW STUDENT' }}
                                                </small> |
                                                <small class="fw-bolder badge bg-secondary">
                                                    {{ $item->student ? ($item->student->enrollment_status ? $item->student->enrollment_status->course->course_name : 'COURSE') : 'COURSE' }}
                                                </small>
                                            </p>
                                            @if ($item->examination_scheduled)
                                                <lable class="fw-bolder text-primary">READY FOR EXAMINATION</lable>
                                            @else
                                                <div class="form-scheduled">
                                                    <form
                                                        action="{{ route('admin.comprehensive-examination-scheduled') }}"
                                                        method="post">
                                                        @csrf
                                                        <input type="hidden" value="{{ $item->student->id }}"
                                                            name="student">
                                                        <input type="hidden" value="{{ $item->id }}"
                                                            name="examinee">

                                                        <div class="row">
                                                            <div class="col-md">
                                                                <small class="fw-bolder text-muted">SET EXAMINATION
                                                                    DATE</small>
                                                                <input type="date"
                                                                    class="form-control form-control-sm border border-primary"
                                                                    name="date">
                                                            </div>
                                                            <div class="col-md-4">
                                                                <button
                                                                    class="btn btn-primary btn-sm mt-4">SUBMIT</button>
                                                            </div>
                                                        </div>
                                                    </form>
                                                </div>
                                            @endif

                                            {{-- <div class="row mt-0">

                                                <div class="col-md">
                                                    <small class="badge bg-primary">
                                                        {{ $data ? ($data->enrollment_status ? strtoupper($data->enrollment_status->curriculum->curriculum_name) : 'CURRICULUM') : 'CURRICULUM' }}
                                    </small>
                                </div>
                                <div class="col-md">
                                    <small class="badge bg-primary">
                                        {{ $data ? ($data->enrollment_status ? strtoupper($data->enrollment_status->academic->semester . ' | ' . $data->enrollment_status->academic->school_year) : 'SECTION') : 'SECTION' }}
                                    </small>
                                </div>
                            </div> --}}
                                        </div>
                                    </div>
                                </div>
                            </div>

                        </a>

                    @empty
                    @endforelse
                </div>
            @endif

        </div>

        <div class="col-lg-4">
            <div class="card">
                <div class="card-body">
                    @if ($addExaminationContent)
                        <lable class="fw-bolder text-primary">ADD COMPENTENCE</lable>
                        <form action="{{ route('department-head.store-compre') }}" method="post"
                            enctype="multipart/form-data">
                            @csrf
                            <div class="form-group">
                                <small class="text-primary fw-bolder">FUNCTION</small>
                                <input type="text" name="function"
                                    class="form-control form-control-sm border border-primary">
                                </select>
                            </div>
                            <div class="form-group">
                                <small class="text-primary fw-bolder">COURSE</small>
                                <select name="course" id=""
                                    class="form-select form-select-sm border border-primary">
                                    @foreach ($courses as $item)
                                        <option value="{{ $item->id }}">{{ $item->course_name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="form-group">
                                <small class="text-primary fw-bolder">COMPETENCE CODE</small>
                                <input type="text" name="code"
                                    class="form-control form-control-sm border border-primary">
                                </select>
                            </div>
                            <div class="form-group">
                                <small class="text-primary fw-bolder">COMPETENCE NAME</small>
                                <input type="text" name="name"
                                    class="form-control form-control-sm border border-primary">
                                </select>
                            </div>
                            <div class="form-group">
                                <small class="text-primary fw-bolder">ATTACH FILES</small>
                                <input type="file" name="upload-file"
                                    class="form-control form-control-sm border border-primary">
                                </select>
                            </div>
                            <div class="form-group">
                                <button type="submit" class="btn btn-primary">CREATE</button>
                            </div>
                        </form>
                    @else
                        <button class="btn btn-primary btn-sm" wire:click="openAddContent">ADD COMPREHENSIVE
                            EXAM</button>
                    @endif

                </div>
            </div>

        </div>
    </div>
    {{-- Care about people's approval and you will be their prisoner. --}}
</div>
