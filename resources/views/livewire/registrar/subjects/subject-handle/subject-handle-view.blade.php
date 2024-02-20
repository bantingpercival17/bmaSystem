@php
    $pageTitle = 'Subject Handles';
@endphp
@section('page-title', $pageTitle)

<div class="page-content">
    <p class="display-6 fw-bolder text-primary">{{ strtoupper($pageTitle) }}</p>
    <div class="row">
        <div class="col-lg-8 col-md-8">
            <div class="row">
                <div class="col-lg-6 col-md-12">
                    <div class="curriculum-name">
                        <span class="badge bg-secondary">CURRICULUM NAME</span> <br>
                        <label for="" class="fw-bolder text-primary h5">{{ $selectedCurriculum }}</label>
                    </div>
                </div>
                <div class="col-lg-6 col-md-12">
                    <div class="course-name">
                        <span class="badge bg-secondary">COURSE</span> <br>
                        <label for="" class="fw-bolder text-primary h5">{{ $selectedCourse }}</label>
                    </div>
                </div>
            </div>
            <div class="content-page mt-5">
                @forelse ($subjectLists as $subjectDetails)
                    <div class="card shadow">
                        <div class="card-header">
                            <label class="card-title text-primary fw-bolder">
                                {{ $subjectDetails['year_level'] }}
                            </label>
                        </div>
                        <div class="card-body table-responsive p-0">
                            <table class="table table-head-fixed text-nowrap">
                                <thead>
                                    <tr>
                                        <th>SUBJECT CODE / DESCRIPTION</th>
                                        <th>SECTION</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse ($subjectDetails['subject_lists'] as $subject)
                                        <tr>
                                            <td>
                                                <a
                                                    href="{{ route('registrar.course-subject-handle-view') }}?_subject={{ base64_encode($subject->id) }}{{ request()->input('_academic') ? '&_academic=' . request()->input('_academic') : '' }}">
                                                    <span class="text-primary"><b>
                                                            {{ $subject->subject->subject_code }}</b></span>
                                                    <br>
                                                    <small> {{ $subject->subject->subject_name }}</small>
                                                </a>
                                            </td>
                                            <td>
                                                @forelse ($subject->sectionList as $item)
                                                    <small class="mt-2 btn-form-grade badge bg-primary">
                                                        {{ $item->section->section_name }}
                                                        <br>[
                                                        {{ $item->staff->first_name . ' ' . $item->staff->last_name }}]
                                                    </small>
                                                @empty
                                                    <span class="badge badge-secondary">ADD
                                                        SECTION</span>
                                                @endforelse
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="2">NO SUBJECT</td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                @empty
                    <div class="card shadow">
                        <div class="card-header">
                            <label class="h4 text-secondary fw-bolder">
                                NO SUBJECTS CONTENT
                            </label>

                        </div>

                    </div>
                @endforelse
            </div>
        </div>
        <div class="col-lg-4 col-md-4">
            <div class="row">
                <div class="col-12">
                    <small class="text-primary"><b>ACADEMIC SCHOOL</b></small>
                    <div class="form-group">
                        <label for="" class="form-control form-control-sm border border-primary">
                            {{ strtoUpper($academicDetails->semester . ' | ' . $academicDetails->school_year) }}
                        </label>
                    </div>
                </div>
                <div class="col-12">
                    <small class="text-primary"><b>COURSE</b></small>
                    <div class="form-group">
                        <select wire:model="selectCourse" class="form-select form-select-sm border border-primary"
                            wire:change="">
                            @foreach ($courseLists as $course)
                                <option value="{{ $course->id }}">{{ ucwords($course->course_name) }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="col-12">
                    <small class="text-primary"><b>CURRICULUM</b></small>
                    <div class="form-group">
                        <select wire:model="selectCurriculum" class="form-select form-select-sm border border-primary"
                            wire:change="">
                            @foreach ($curriculumLists as $data)
                                <option value="{{ $data->id }}">
                                    {{ ucwords($data->curriculum_name) }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="col-12">
                    <small class="text-primary" data-toggle="tooltip" data-placement="right"
                        title="You can use this Template to Bulk Upload the Teaching Loads and Schedules"><b>TEACHING
                            LOAD TEMPLATE</b> </small>
                    <div class="form-group">
                        <a class="btn btn-primary btn-sm w-100" wire:click="exportTeachingLoadTemplate">DOWNLOAD
                            TEMPLATES</a>
                    </div>
                </div>
                <div class="col-12">
                    <small class="text-primary" data-toggle="tooltip" data-placement="right"
                        title="Upload the Template Excel File"><b>UPLOAD TEACHING LOAD</b> </small>
                    <div class="form-group">
                        <form {{-- wire:submit.prevent="importTeachingLoad" --}} action="{{ route('registrar.subject-schedule-upload') }}"
                            method="post" enctype="multipart/form-data">
                            @csrf
                            <input type="hidden" name="curriculum" value="{{ $course->id }}">
                            <input type="hidden" name="course" value="{{ $course->id }}">
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <input class="form-control form-control-sm  border border-primary"
                                            type="file"name="upload-file">

                                        @error('upload-file')
                                            <span class="badge bg-danger mt-2">{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <button class="btn btn-info text-white btn-sm float-end"
                                        type="submit">UPLOAD</button>
                                </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
