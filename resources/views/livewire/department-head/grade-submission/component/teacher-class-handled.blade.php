<div class="card shadow">
    <div class="card-header p-3">
        <span class="fw-bolder text-primary">
            LIST OF CLASS HANDLED
        </span>
    </div>
    <div class="card-body">
        @forelse ($staffView->subject_handles_v2($academic) as $item)
            <div class="d-flex flex-wrap align-items-center justify-content-between">
                <div class="d-flex flex-wrap align-items-center">
                    <div class="mb-sm-0">
                        <a
                            href="{{ route('department-head.grade-submission-v2') }}{{ '?staff=' . base64_encode($staffView->id) }}{{ $academic ? '&_academic=' . $academic : '' }}{{ '&subject=' . base64_encode($item->id) }}">

                            <div class="d-flex">
                                <h5 class="">
                                    {{ $item->curriculum_subject->subject->subject_code }}
                                </h5>
                            </div>
                            <p class="mb-0">{{ $item->section->section_name }}</p>
                        </a>
                    </div>
                </div>
                <ul class="d-flex mb-0 text-center ">
                    @if ($item->midterm_grade_submission)
                        @if ($item->midterm_grade_submission->is_approved == 1)
                            <li class="badge bg-primary me-2">
                                <small class="mb-1 fw-bolder">MIDTERM</small>
                                <br>
                                <small class="mb-1 fw-normal">APPROVED </small>
                            </li>
                        @endif
                        @if ($item->midterm_grade_submission->is_approved === 0)
                            <li class="badge bg-danger me-2">
                                <small class="mb-1 fw-bolder">MIDTERM</small>
                                <br>
                                <small class="mb-1 fw-normal">DISAPPROVED</small>
                            </li>
                        @endif
                        @if ($item->midterm_grade_submission->is_approved === null)
                            <li class="badge bg-info me-2">
                                <small class="mb-1 fw-bolder">MIDTERM</small>
                                <br>
                                <small class="mb-1 fw-normal">PENDING</small>
                            </li>
                        @endif
                    @else
                        <li class="badge bg-secondary me-2">
                            <small class="mb-1 fw-bolder">MIDTERM</small>
                            <br>
                            <small class="mb-1 fw-normal">-</small>
                        </li>
                    @endif
                    @if ($item->finals_grade_submission)
                        @if ($item->finals_grade_submission->is_approved == 1)
                            <li class="badge bg-primary me-2">
                                <small class="mb-1 fw-bolder">FINALS</small>
                                <br>
                                <small class="mb-1 fw-normal">APPROVED</small>
                            </li>
                        @endif
                        @if ($item->finals_grade_submission->is_approved === 0)
                            <li class="badge bg-danger me-2">
                                <small class="mb-1 fw-bolder">FINALS</small>
                                <br>
                                <small class="mb-1 fw-normal">DISAPPROVED</small>
                            </li>
                        @endif
                        @if ($item->finals_grade_submission->is_approved === null)
                            <li class="badge bg-info me-2">
                                <small class="mb-1 fw-bolder">FINALS</small>
                                <br>
                                <small class="mb-1 fw-normal">PENDING</small>
                            </li>
                        @endif
                    @else
                        <li class="badge bg-secondary me-2">
                            <small class="mb-1 fw-bolder">FINALS</small>
                            <br>
                            <small class="mb-1 fw-normal">-</small>
                        </li>
                    @endif
                </ul>
            </div>
        @empty
            <span class="text-muted h4 fw-bolder">NO SUBJECT HANDLED</span>
        @endforelse
    </div>
</div>
