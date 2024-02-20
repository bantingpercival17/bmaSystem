@php
    $pageTitle = 'Program of Studies';
@endphp
@section('page-title', $pageTitle)

<div class="page-content">
    <p class="display-6 fw-bolder text-primary">{{ strtoupper($pageTitle) }}</p>
    <div class="row">
        <div class="col-lg-12 col-md-12">
            <div class="row">
                <div class="col-md">
                    <small class="text-primary"><b>COURSE</b></small>
                    <div class="form-group">
                        <select wire:model="selectCourse" class="form-select form-select-sm border border-primary"
                            wire:change="categoryCourse">
                            @foreach ($courseLists as $course)
                                <option value="{{ $course->id }}">{{ ucwords($course->course_name) }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="col-md">
                    <small class="text-primary"><b>CURRICULUM</b></small>
                    <div class="form-group">
                        <select wire:model="selectCurriculum" class="form-select form-select-sm border border-primary"
                            wire:change="categoryCurriculum">
                            @foreach ($curriculumLists as $data)
                                <option value="{{ $data->id }}">
                                    {{ ucwords($data->curriculum_name) }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="col-md">
                    <small class="text-primary"><b>EXPORT PROGRAM OF STUDIES</b></small>
                    <div class="form-group">
                        <button class="btn btn-primary btn-sm" wire:click="downloadFiles">DOWNLOAD FILE</button>
                    </div>
                </div>
            </div>
            <div class="content-page mt-5">
                @foreach ($subjectLists as $item)
                    <div class="card">
                        <div class="card-header">
                            <div class="card-tools float-end">
                                <button class="btn btn-primary btn-sm btn-modal-subject" data-bs-toggle="modal"
                                    data-year="{{ $item['level_name'] }}" data-tab="nav-link-{{ $item['level'] }}"
                                    data-bs-target=".model-add-subject">ADD</button>
                            </div>
                            <small
                                class="fw-bolder text-muted">{{ strtoupper($selectedCourse . ' - ' . $selectedCurriculum) }}</small>
                            <h3 class="card-title text-primary">
                                <b>{{ $item['level_name'] }}</b>
                            </h3>
                        </div>
                        <div class="card-header">
                            <ul class="nav nav-tabs nav-fill" id="myTab-three" role="tablist">
                                @foreach ($item['semester'] as $key => $tab)
                                    <li class="nav-item nav-sm">
                                        <a class="nav-link nav-link-{{ $item['level'] }} {{ $key == 0 ? 'active' : '' }}"
                                            id="{{ str_replace(' ', '-', strtolower($tab)) . '-' . $item['level'] }}"
                                            data-bs-toggle="tab" data-semester="{{ $tab }}"
                                            href="#{{ str_replace(' ', '-', strtolower($tab)) . '-' . $item['level'] }}-content"
                                            role="tab" aria-controls="home"
                                            aria-selected="{{ $key == 0 ? true : false }}">
                                            {{ strtoupper($tab) }}
                                        </a>
                                    </li>
                                @endforeach
                            </ul>
                            <div class="tab-content" id="myTabContent-4">
                                @foreach ($item['semester'] as $key => $sem)
                                    <div class="tab-pane fade show {{ $key == 0 ? 'active' : '' }}"
                                        id="{{ str_replace(' ', '-', strtolower($sem)) . '-' . $item['level'] }}-content"
                                        role="tabpanel"
                                        aria-labelledby="{{ str_replace(' ', '-', strtolower($sem)) . '-' . $item['level'] }}">

                                        <div class="content">
                                            @php
                                                $_tUnits = 0;
                                                $_tLechr = 0;
                                                $_tLabhr = 0;
                                            @endphp
                                            <div class="table-responsive">
                                                <table class="table table-strip">
                                                    <thead class="text-center">
                                                        <tr class="text-center">
                                                            <th>SUBJECT CODE</th>
                                                            <th>SUBJECT DESCRIPTION</th>
                                                            <th style="width: 5px;">LEC. HOURS</th>
                                                            <th style="width: 5px;">LAB. HOURS</th>
                                                            <th style="width: 5px;">UNITS</th>
                                                            <th>ACTION</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        @php
                                                            $data = str_replace(' ', '_', strtolower($sem));
                                                            $subjects = $item['subject_lists'];
                                                            $dataLists = $subjects[$data];
                                                        @endphp
                                                        {{$dataLists}}
                                                        @if ($dataLists)
                                                            @foreach ($dataLists as $item)
                                                                <tr>
                                                                    <td>{{ $item }}</td>
                                                                </tr>
                                                            @endforeach
                                                        @else
                                                            <tr>
                                                                <td colspan="6">NO SUBJECT</td>
                                                            </tr>
                                                        @endif
                                                        {{--  @if ($_subject = $curriculum->subject([$courseDetails->id, $item, $sem])->count() > 0)
                                                            @foreach ($curriculum->subject([$courseDetails->id, $item, $sem])->get() as $_subject)
                                                                <tr>
                                                                    <td>{{ $_subject->subject->subject_code }}</td>
                                                                    <td width="30px;">
                                                                        {{ $_subject->subject->subject_name }}</td>
                                                                    <td style="width: 5px;">
                                                                        {{ $_subject->subject->lecture_hours }}
                                                                    </td>
                                                                    <td style="width: 5px;">
                                                                        {{ $_subject->subject->laboratory_hours }}
                                                                    </td>
                                                                    <td style="width: 5px;">
                                                                        {{ $_subject->subject->units }}</td>
                                                                    <td>
                                                                        <button
                                                                            class="btn btn-success  btn-sm btn-modal-subject"
                                                                            data-bs-toggle="modal"
                                                                            data-year="{{ $item['year_level'] }}"
                                                                            data-tab="nav-link-{{ $item }}"
                                                                            data-curriculum="{{ base64_encode($_subject->id) }}"
                                                                            data-bs-target=".model-update-subject">EDIT</button>
                                                                        <button class="btn btn-danger btn-sm btn-remove"
                                                                            data-url="{{ route('registrar.remove-curriculum-subject') . '?_subject=' . base64_encode($_subject->id) }}">REMOVE</button>
                                                                    </td>
                                                                </tr>
                                                                @php
                                                                    $_tLechr += $_subject->subject->lecture_hours;
                                                                    $_tLabhr += $_subject->subject->laboratory_hours;
                                                                    $_tUnits += $_subject->subject->units;
                                                                @endphp
                                                            @endforeach
                                                        @else
                                                            <tr>
                                                                <td colspan="6">NO SUBJECT</td>
                                                            </tr>
                                                        @endif --}}
                                                    </tbody>
                                                    <thead>
                                                        <tr>
                                                            <th colspan="2">TOTAL</th>
                                                            <td>{{ $_tLechr }}</td>
                                                            <td>{{ $_tLabhr }}</td>
                                                            <td>{{ $_tUnits }}</td>
                                                            <td></td>
                                                        </tr>
                                                    </thead>
                                                </table>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                @endforeach

            </div>
        </div>
    </div>
</div>
<div class="modal fade model-add-subject" tabindex="-1" role="dialog" aria-labelledby="model-add-subjectTitle"
    aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-scrollable modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title modal-title fw-bolder text-primary" id="model-add-subjectTitle">ADD SUBJECT
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">
                </button>
            </div>
            <div class="modal-body">
                <form role="form" action="{{ route('registrar.curriculum-store') }}" method="POST"
                    id="modal-form-add">
                    @csrf
                    <small for="" class="h5 text-primary fw-bolder">SUBJECT DETAILS</small>
                    <div class="form-group">
                        <div class="row">
                            <div class="col">
                                <input type="hidden" name="curriculum" value="{{ base64_encode($curriculum->id) }}">
                                <input type="hidden" name="department"
                                    value="{{ base64_encode($courseDetails->id) }}">
                                <input type="text" class="form-control course-code" placeholder="Course Code"
                                    name="course_code">
                            </div>
                            <div class="col-5">
                                <input type="text" class="form-control" placeholder="Subject Unit"
                                    name="_units">
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <input type="text" class="form-control" placeholder="Subject Description"
                            name="_subject_name">
                    </div>
                    <div class="form-group">
                        <div class="row">
                            <div class="col">
                                <input type="text" class="form-control" placeholder="Lecture Hours"
                                    name="_hours">
                            </div>
                            <div class="col">
                                <input type="text" class="form-control" placeholder="Laboratory Hours"
                                    name="_lab_hour">
                            </div>

                        </div>
                    </div>
                    <small for="" class="text-primary h5 fw-bolder">COURSE DETAILS</small>
                    <div class="row">
                        <div class="col-md-5">
                            <div class="form-group">
                                <small class="fw-bolder text-muted">
                                    COURSE
                                </small>
                                <label for="" class="form-control">{{ $courseDetails->course_name }}</label>
                                <input type="hidden" value="{{ $courseDetails->id }}" name="course">
                            </div>
                        </div>
                        <div class="col-md">
                            <div class="form-group">
                                <small class="fw-bolder text-muted">
                                    YEAR LEVEL
                                </small>
                                <input type="text" class="form-control year-level" name="_input_7">
                            </div>
                        </div>
                        <div class="col-md">
                            <div class="form-group">
                                <small class="fw-bolder text-muted">
                                    SEMESTER
                                </small>
                                <input type="text" class="form-control semester" name="_input_8">
                            </div>
                        </div>
                    </div>


                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-primary btn-sm btn-modal-form" data-form="modal-form-add">SAVE
                    CONTENT</button>
            </div>
        </div>
    </div>
</div>
<div class="modal fade model-update-subject" tabindex="-1" role="dialog"
    aria-labelledby="model-update-subjectTitle" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-scrollable modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title modal-title fw-bolder text-primary" id="model-update-subjectTitle">UPDATE
                    SUBJECT
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">
                </button>
            </div>
            <div class="modal-body">
                <form role="form" action="{{ route('registrar.update-curriculum-subject') }}" method="POST"
                    id="modal-form-update">
                    @csrf
                    <input type="hidden" name="curriculum_subject" class="curriculum">
                    <small for="" class="h5 text-primary fw-bolder">SUBJECT DETAILS</small>
                    <div class="form-group">
                        <div class="row">
                            <div class="col">
                                <input type="hidden" name="curriculum"
                                    value="{{ base64_encode($curriculum->id) }}">
                                <input type="hidden" name="department"
                                    value="{{ base64_encode($courseDetails->id) }}">
                                <input type="text" class="form-control course-code" placeholder="Course Code"
                                    name="course_code">
                            </div>
                            <div class="col-5">
                                <input type="text" class="form-control units" placeholder="Subject Unit"
                                    name="_units">
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <input type="text" class="form-control subject-name" placeholder="Subject Description"
                            name="_subject_name">
                    </div>
                    <div class="form-group">
                        <div class="row">
                            <div class="col">
                                <input type="text" class="form-control lec-hours" placeholder="Lecture Hours"
                                    name="_hours">
                            </div>
                            <div class="col">
                                <input type="text" class="form-control lab-hours" placeholder="Laboratory Hours"
                                    name="_lab_hour">
                            </div>

                        </div>
                    </div>
                    <small for="" class="text-primary h5 fw-bolder">COURSE DETAILS</small>
                    <div class="row">
                        <div class="col-md-5">
                            <div class="form-group">
                                <small class="fw-bolder text-muted">
                                    COURSE
                                </small>

                                <label for="" class="form-control">{{ $courseDetails->course_name }}</label>
                                <input type="hidden" value="{{ $courseDetails->id }}" name="course">
                            </div>
                        </div>
                        <div class="col-md">
                            <div class="form-group">
                                <small class="fw-bolder text-muted">
                                    YEAR LEVEL
                                </small>
                                <input type="text" class="form-control year-level" name="_input_7">
                            </div>
                        </div>
                        <div class="col-md">
                            <div class="form-group">
                                <small class="fw-bolder text-muted">
                                    SEMESTER
                                </small>
                                <input type="text" class="form-control semester" name="_input_8">
                            </div>
                        </div>
                    </div>


                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-primary btn-sm btn-modal-form"
                    data-form="modal-form-update">UPDATE
                    CONTENT</button>
            </div>
        </div>
    </div>
</div>
@section('script')
    <script>
        // Remove
        $('.btn-remove').click(function(event) {
            Swal.fire({
                title: 'Subject Course',
                text: "Do you want to remove?",
                icon: 'info',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes'
            }).then((result) => {
                var _url = $(this).data('url');
                if (result.isConfirmed) {
                    window.location.href = _url
                }
            })
            event.preventDefault();
        })
        $('.btn-modal-subject').click(function(event) {
            var tab = $(this).data('tab')
            var level_name = $(this).data('year');
            var semester = $("." + tab + '.active').data('semester');
            if ($(this).data('curriculum')) {
                console.log($(this).data('curriculum'))
                $.get("{{ route('registrar.view-curriculum-subject') }}?curriculum=" + $(this).data('curriculum'),
                    function(respond) {
                        console.log(respond._curriculum_subject)
                        $('.course-code').val(respond._subject.subject_code)
                        $('.units').val(respond._subject.units)
                        $('.subject-name').val(respond._subject.subject_name)
                        $('.lab-hours').val(respond._subject.laboratory_hours)
                        $('.lec-hours').val(respond._subject.lecture_hours)
                        $('.curriculum').val(respond._curriculum_subject.id)
                    })
            }
            $('.semester').val(semester)
            $('.year-level').val(year_level)
            event.preventDefault();
        })
        $('.btn-modal-form').click(function(event) {
            Swal.fire({
                title: 'Course Subject',
                text: "Do you want to add?",
                icon: 'info',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes'
            }).then((result) => {
                var form = $(this).data('form');
                if (result.isConfirmed) {

                    console.log(form)
                    document.getElementById(form).submit()
                }
            })
            event.preventDefault();
        })
    </script>
@endsection
