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
        {{-- <div class="col-md-12">
            <small class="text-primary"><b>GENERATE REPORT</b></small>
            <div class="form-group search-input">
                <button class="btn btn-primary w-100" wire:click="generateReport">PDF REPORT</button>
            </div>
        </div> --}}
    </div>
</div>
