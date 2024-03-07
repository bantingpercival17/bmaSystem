@php
    $pageTitle = 'Course Syllabus';
@endphp
@section('page-title', $pageTitle)
<div class="content-page row">
    <div class="col-lg-12">
        <p class="display-6 fw-bolder text-primary mb-2">{{ strtoupper($pageTitle) }}</p>
        <div class="table-responsive ">
            <table class="nav nav-underline bg-soft-primary text-center" aria-label="Secondary navigation">
                <thead class="d-flex">
                    <tr>
                        <td class="nav-link {{ $activeCard == 'subject-information' ? 'active' : 'text-muted' }}"
                            wire:click="swtchTab('subject-information')">
                            SUBJECT INFORMATION
                        </td>
                    </tr>
                    <tr>
                        <td class="nav-link {{ $activeCard == 'subject-specification' ? 'active' : 'text-muted' }}"
                            wire:click="swtchTab('subject-specification')">
                            SUBJECT SPECIFICATION
                        </td>
                    </tr>
                    <tr>
                        <td class="nav-link {{ $activeCard == 'subject-topics' ? 'active' : 'text-muted' }}"
                            wire:click="swtchTab('subject-topics')">
                            SUBJECT TOPICS
                        </td>
                    </tr>
                    <tr>
                        <td class="nav-link {{ $activeCard == 'subject-generate-report' ? 'active' : 'text-muted' }}"
                            wire:click="swtchTab('subject-specification')">
                            GENERATE REPORTS
                        </td>
                    </tr>
                </thead>
            </table>
        </div>
        @if ($activeCard == 'subject-information')
            @include('livewire.teacher.course-syllabus.components.subject-information')
        @endif
        @if ($activeCard == 'subject-specification')
            @include('livewire.teacher.course-syllabus.components.subject-specification')
        @endif
        @if ($activeCard == 'subject-topics')
            @include('livewire.teacher.course-syllabus.components.subject-topics')
        @endif


    </div>
</div>
