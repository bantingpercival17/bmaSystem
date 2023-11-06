@php
    $pageTitle = 'Grade Verification';
@endphp
@section('page-title', $pageTitle)

<div class="row">
    <div class="col-lg-8">
        <p class="display-6 fw-bolder text-primary">{{ strtoupper($pageTitle) }}</p>

        <div class="form-search">
            <small class="fw-bolder text-primary">SECTION LIST</small>
        </div>
        <div class="data-content mt-4">
            @forelse ($sectionList as $section)
                <a href="">
                    <div class="card mb-2 shadow mb-3">
                        <div class="card-body p-3 me-2">
                            <label for="" class="fw-bolder text-primary h4">{{ $section->section_name }}</label>
                            <div class="row">
                                <div class="col-md">
                                    <small for="" class="fw-bolder text-muted">NUMBER OF STUDENT</small> <br>
                                    <label for=""
                                        class="text-primary fw-bolder h4">{{ count($section->student_sections) }}</label>
                                </div>
                            </div>
                        </div>
                    </div>
                </a>
            @empty
            @endforelse
        </div>
    </div>
    <div class="col-lg-4">
        <p class="h4 text-info fw-bolder">FILTER SELECTION</p>
        <div class="col-12">
            <small class="text-primary"><b>COURSE</b></small>
            <div class="form-group search-input">
                <select wire:model="selectCourse" class="form-select form-select-sm border border-primary"
                    wire:click="chooseCourse">
                    <option value="ALL COURSE">{{ ucwords('all courses') }}</option>
                    @foreach ($courses as $course)
                        <option value="{{ $course->id }}">{{ ucwords($course->course_name) }}</option>
                    @endforeach
                </select>
            </div>
        </div>
        <div class="col-12">
            <small class="text-primary"><b>YEAR LEVEL</b></small>
            <div class="form-group search-input">
                <select wire:model="selectLevel" class="form-select form-select-sm border border-primary">
                    <option value="ALL LEVELS">{{ ucwords('all levels') }}</option>
                    @foreach ($levels as $level)
                        <option value="{{ $level }}">
                            {{ ucwords(Auth::user()->staff->convert_year_level($level)) }}
                        </option>
                    @endforeach
                </select>
            </div>
        </div>
        <div class="col-md-12">
            <small class="text-primary"><b>GENERATE FORMS</b></small>
            <div class="form-group search-input">
                <button class="btn btn-primary btn-sm w-100" wire:click="generateReport">EXPORT AD 01</button>
            </div>
            <div class="form-group search-input">
                <button class="btn btn-primary btn-sm w-100" wire:click="generateReport">EXPORT AD 02</button>
            </div>
        </div>
    </div>
</div>
