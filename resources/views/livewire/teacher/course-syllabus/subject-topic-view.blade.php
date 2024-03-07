@php
    $pageTitle = 'Subject Topic';
@endphp
@section('page-title', $pageTitle)
<div class="content-page row">

    <div class="col-lg-12">
        <p class="display-6 fw-bolder text-primary mb-2">{{ strtoupper($pageTitle) }}</p>
        <div class="row">
            <div class="col-md-2">
                <small>COURSE CODE</small> <br>
                <label for=""
                    class="fw-bolder text-primary">{{ $subjectTopic->course_syllabus->subject->subject_code }}</label>
            </div>
            <div class="col-md">
                <small>COURSE DESCRIPTIVE TITLE</small> <br>
                <label for=""
                    class="fw-bolder text-primary">{{ $subjectTopic->course_syllabus->subject->subject_name }}</label>
            </div>
        </div>
        <div class="row mt-3">
            <div class="col-md-8">
                <div class="table-responsive ">
                    <table class="nav nav-underline bg-soft-primary text-center" aria-label="Secondary navigation">
                        <thead class="d-flex">
                            <tr>
                                <td class="nav-link {{ $activeCard == 'subject-information' ? 'active' : 'text-muted' }}"
                                    wire:click="swtchTab('subject-information')">
                                    TOPIC DETAILS
                                </td>
                            </tr>
                            <tr>
                                <td class="nav-link {{ $activeCard == 'subject-specification' ? 'active' : 'text-muted' }}"
                                    wire:click="swtchTab('subject-specification')">
                                    LEARNING OUTCOMES
                                </td>
                            </tr>
                            <tr>
                                <td class="nav-link {{ $activeCard == 'subject-topics' ? 'active' : 'text-muted' }}"
                                    wire:click="swtchTab('subject-topics')">
                                    MATERIALS
                                </td>
                            </tr>
                            <tr>
                                <td class="nav-link {{ $activeCard == 'subject-topics' ? 'active' : 'text-muted' }}"
                                    wire:click="swtchTab('subject-topics')">
                                    ASSESSMENTS
                                </td>
                            </tr>
                        </thead>
                    </table>
                </div>
            </div>
            <div class="col-md-4">
                <label for="" class="fw-bolder text-primary h4">LIST OF TOPICS</label>
                @if (count($subjectTopic->course_syllabus->learning_outcomes) > 0)

                    <div class="learning-outline-content mt-3">
                        @foreach ($subjectTopic->course_syllabus->learning_outcomes as $key => $learning_outcome)
                            <a
                                href="{{ route('teacher.course-syllabus-topic-view-v2') . '?topic=' . base64_encode($learning_outcome->id) }}">
                                <div class="card m-2 p-0">
                                    <div class="card-body m-2 p-2">
                                        <div class="col-md-12">
                                            <small class="fw-bolder text-muted">TOPIC {{ $key + 1 }}</small><br>
                                            <label for=""
                                                class="text-primary fw-bolder">{{ strtoupper($learning_outcome->learning_outcomes) }}</label>
                                        </div>
                                    </div>
                                </div>
                            </a>
                        @endforeach
                    </div>
                @else
                    <p>No Topics</p>
                @endif
            </div>
        </div>



    </div>
</div>
