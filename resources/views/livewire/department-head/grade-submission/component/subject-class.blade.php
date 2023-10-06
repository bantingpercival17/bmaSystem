@if ($subjectView)
    <div class="card shadow">
        <div class="card-header p-4">
            <span class="float-end badge bg-primary"
                wire:click="showDocuments('{{ $subjectView->id }}','finals','ad2')">FORM
                AD-02</span>
            <p class="h5 text-primary fw-bolder">
                {{ $subjectView ? $subjectView->section->section_name : 'Section Name' }} -
                {{ $subjectView ? $subjectView->curriculum_subject->subject->subject_code : 'Subject Name' }}
            </p>
            <small class="h6 text-muted">{{ $subjectView->curriculum_subject->subject->subject_name }}</small>
            <br>
            <small
                class="badge bg-secondary">{{ $subjectView->academic->semester . ' - ' . $subjectView->academic->school_year }}</small>
        </div>
    </div>

    <div class="card shadow">
        <div class="card-header p-4">
            @if ($midtermCard)
                <span class="float-end badge bg-primary"
                    wire:click="showDocuments('{{ $subjectView->id }}','midterm','ad1')">FORM AD-01 MIDTERM</span>
            @else
                <span class="float-end badge bg-secondary">NO GRADE SUBMISSION</span>
            @endif
            <p class="h5 text-info fw-bolder">
                MIDTERM GRADING SHEET
            </p>
        </div>
        @if ($midtermCard)
            <div class="card-body">
                {{--  <span class="text-muted fw-bolder">GRADING SHEET DETAILS</span> --}}
                @forelse ($subjectView->midterm_grade_remarks as $item)
                    <div class="row">
                        <div class="col-md-12">
                            <span
                                class="badge bg-info float-end">{{ $item->created_at->format('F, d Y h:i A ') }}</span>
                        </div>


                        @if ($item->is_approved == 1)
                            <div class="col-md-12">
                                <span class="text-primary fw-bolder h5">APPROVED GRADING SHEET</span>
                            </div>
                            <div class="col-md">
                                <small class="">
                                    VERIFY BY:
                                </small>
                                <span class="badge bg-secondary">{{ $item->approved_by }}</span>
                            </div>
                            <div class="col-md">
                                <small class="f">
                                    DATE VERIFICATION:
                                </small>
                                <span class="badge bg-secondary">{{ $item->updated_at->format('F, d Y h:i A ') }}</span>
                            </div>
                        @elseif ($item->is_approved === 0)
                            <div class="col-md-12">
                                <span class="text-danger fw-bolder h5">DISAPPROVED GRADING SHEET</span> <br>
                                <small class="fw-bolder">REMARKS / COMMENTS</small>
                                <span class="text-info">{{ $item->comments }}</span>
                            </div>
                            <div class="col-md">
                                <small class="">
                                    VERIFY BY:
                                </small>
                                <span class="badge bg-secondary">{{ $item->approved_by }}</span>
                            </div>
                            <div class="col-md">
                                <small class="f">
                                    DATE VERIFICATION:
                                </small>
                                <span
                                    class="badge bg-secondary">{{ $item->updated_at->format('F, d Y h:i A ') }}</span>
                            </div>
                        @elseif ($item->is_approved === null)
                            <h5 class="text-info mb-1">FOR APPROVAL</h5>
                        @endif
                    </div>
                    <br>
                @empty
                @endforelse
            </div>
            <div class="card-footer p-3">
                <form class="mt-3" action="{{ route('department-head.submission-verification') }}" method="POST">
                    @csrf
                    <input type="hidden" name="submission"
                        value="{{ base64_encode($subjectView->midterm_grade_submission->id) }}">
                    <input type="hidden" name="status" value="0">
                    <input type="text" class="form-control border border-primary" placeholder="Remarks"
                        name="comments" required>
                    <div class="">
                        <button class="btn btn-outline-primary btn-sm float-end ms-2 mt-2" type="submit" value="0"
                            name="status">DISAPPROVED</button>
                    </div>
                </form>
                <form action="{{ route('department-head.submission-verification') }}" method="POST">
                    @csrf
                    <input type="hidden" name="submission"
                        value="{{ base64_encode($subjectView->midterm_grade_submission->id) }}">
                    <input type="hidden" name="status" value="1">
                    <button class="btn btn-primary btn-sm float-end mt-2" type="submit">APPROVED
                    </button>
                </form>
            </div>
        @endif

    </div>
    <div class="card shadow">
        <div class="card-header p-4">
            @if ($finalsCard)
                <span class="float-end badge bg-primary" wire:click="showDocuments('{{ $subjectView->id }}','finals','ad1')">FORM AD-01 FINALS</span>
            @else
                <span class="float-end badge bg-secondary">NO GRADE SUBMISSION</span>
            @endif
            <p class="h5 text-info fw-bolder">
                FINALS GRADING SHEET
            </p>
        </div>
        @if ($finalsCard)
            <div class="card-body">
                {{--  <span class="text-muted fw-bolder">GRADING SHEET DETAILS</span> --}}
                @forelse ($subjectView->finals_grade_remarks as $item)
                    <div class="row">
                        <div class="col-md-12">
                            <span
                                class="badge bg-info float-end">{{ $item->created_at->format('F, d Y h:i A ') }}</span>
                        </div>


                        @if ($item->is_approved == 1)
                            <div class="col-md-12">
                                <span class="text-primary fw-bolder h5">APPROVED GRADING SHEET</span>
                            </div>
                            <div class="col-md">
                                <small class="">
                                    VERIFY BY:
                                </small>
                                <span class="badge bg-secondary">{{ $item->approved_by }}</span>
                            </div>
                            <div class="col-md">
                                <small class="f">
                                    DATE VERIFICATION:
                                </small>
                                <span
                                    class="badge bg-secondary">{{ $item->updated_at->format('F, d Y h:i A ') }}</span>
                            </div>
                        @elseif ($item->is_approved === 0)
                            <div class="col-md-12">
                                <span class="text-danger fw-bolder h5">DISAPPROVED GRADING SHEET</span> <br>
                                <small class="fw-bolder">REMARKS / COMMENTS</small>
                                <span class="text-info">{{ $item->comments }}</span>
                            </div>
                            <div class="col-md">
                                <small class="">
                                    VERIFY BY:
                                </small>
                                <span class="badge bg-secondary">{{ $item->approved_by }}</span>
                            </div>
                            <div class="col-md">
                                <small class="f">
                                    DATE VERIFICATION:
                                </small>
                                <span
                                    class="badge bg-secondary">{{ $item->updated_at->format('F, d Y h:i A ') }}</span>
                            </div>
                        @elseif ($item->is_approved === null)
                            <h5 class="text-info mb-1">FOR APPROVAL</h5>
                        @endif
                    </div>
                    <br>
                @empty
                @endforelse
            </div>
            <div class="card-footer p-3">
                <form class="mt-3" action="{{ route('department-head.submission-verification') }}" method="POST">
                    @csrf
                    <input type="hidden" name="submission"
                        value="{{ base64_encode($subjectView->finals_grade_submission->id) }}">
                    <input type="hidden" name="status" value="0">
                    <input type="text" class="form-control border border-primary" placeholder="Remarks"
                        name="comments" required>
                    <div class="">
                        <button class="btn btn-outline-primary btn-sm float-end ms-2 mt-2" type="submit"
                            value="0" name="status">DISAPPROVED</button>
                    </div>
                </form>
                <form action="{{ route('department-head.submission-verification') }}" method="POST">
                    @csrf
                    <input type="hidden" name="submission"
                        value="{{ base64_encode($subjectView->finals_grade_submission->id) }}">
                    <input type="hidden" name="status" value="1">
                    <button class="btn btn-primary btn-sm float-end mt-2" type="submit">APPROVED
                    </button>
                </form>
            </div>
        @endif
    </div>
    @if ($showModal)
        <div class="fixed inset-0 flex items-center justify-center z-50">
            <div class="modal fade show" style="display: block">
                <div class="modal-dialog modal-xl">
                    <div class="modal-content">
                        <div class="modal-header p-2">
                            <h5 class="modal-title" id="exampleModalLabel1">GRADE PREVIEW</h5>
                            <button type="button" class="btn-close" wire:click="hideDocuments">
                            </button>
                        </div>
                        <div class="modal-body p-0">
                            {{--   <img src="{{ $documentLink }}" style=" width: 100%; " alt=""> --}}
                           {{--  <iframe src="{{ $documentLink }}" class="i" style=" width: 100%; height:100vh;">
                            </iframe> --}}
                            <iframe class="form-view iframe-placeholder"src="{{ $documentLink }}" width="100%"
                        height="600px">
                    </iframe>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endif
@else
    <div class="card shadow">
        <div class="card-header p-4">
            <p class="h4 fw-bolder text-warning">BACK TO THE OVERVIEW SELECT A SUBJECT CLASS</p>
        </div>
    </div>
@endif
