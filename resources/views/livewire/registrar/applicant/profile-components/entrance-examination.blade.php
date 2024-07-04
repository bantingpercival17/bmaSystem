@if ($profile->payment)
    @if ($profile->payment->is_approved)
        <div class="card">
            <div class="card-header p-3">
                <label for="" class="fw-bolder text-primary">ENTRANCE EXAMINATION OVERVIEW</label>
                <div class="float-end">
                    <a href="{{ route('applicant-examination-reset') }}?_applicant={{ base64_encode($profile->id) }}"
                        class="badge bg-info">Reset Examination</a>
                </div>
            </div>
            <div class="card-body">
                {{-- Examination Scheduled --}}
                @if ($profile->examination_schedule)
                    <div class="table-responsive">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>EXAMINATION CODE</th>
                                    <th>EXAMINATION STATUS</th>
                                    <th>EXAMINATION RESULT</th>
                                    <th>ACTION</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($profile->examination_list as $item)
                                    <tr>
                                        <td>{{ $item->examination_code }}</td>
                                        <td>
                                            @if ($item->is_finish === 1)
                                                <span>Examination Date: {{ $item->updated_at->format('F d,Y') }}</span>
                                                <br>
                                                <span class="fw-bolder">Examination Done</span>
                                            @elseif($item->is_finish === 0)
                                                <span class="fw-bolder">Examination Ongoing</span>
                                            @else
                                                <span class="fw-bolder">Ready for Examination</span>
                                            @endif
                                        </td>
                                        <td>
                                            <div class="d-flex justify-content-center">
                                                @if ($item->is_finish === 1)
                                                    <span class="score">
                                                        <small class="fw-bolder text-muted">
                                                            SCORE:
                                                        </small> <br>
                                                        <span class="text-primary fw-bolder">
                                                            {{ $item->examination_result()[0] }}
                                                        </span>
                                                    </span>
                                                    <span class="PERCENTILE">
                                                        <small class="fw-bolder text-muted">
                                                            PERCENTILE:
                                                        </small><br>
                                                        <span class="text-primary fw-bolder">
                                                            {{ $item->examination_result()[1] }}
                                                        </span>
                                                    </span>
                                                    <br>
                                                @else
                                                    <span class="fw-bolder"></span>
                                                @endif

                                            </div>

                                        </td>
                                        <td>
                                            <a href="{{ route('applicant-examination-result-v2') }}?examination={{ base64_encode($item->id) }}"
                                                class="btn btn-primary btn-sm w-100 mt-2">Test Questioner</a>
                                            <br>


                                            <a href="{{ route('applicant.examination-reconsideration') }}?applicant={{ $profile->id }}"
                                                class="btn btn-primary btn-sm w-100 mt-2">
                                                PASSED BY INTERVIEW
                                            </a>
                                            <br>
                                            @foreach (Auth::user()->roles as $role)
                                                @if ($role->id == 1)
                                                    <button class="btn btn-info btn-sm w-100  mt-2 text-white"
                                                        data-bs-toggle="modal" data-bs-target=".examination-view-modal"
                                                        data-bs-toggle="tooltip" title=""
                                                        data-bs-original-title="Change Examination Date">CHANGE
                                                        EXAMINATION
                                                        DATE</button>
                                                    <br>
                                                    <a href="{{ route('applicant-examination-log-v2') }}?examination={{ base64_encode($item->id) }}"
                                                        class="btn btn-outline-primary btn-sm w-100 mt-2 ">Logs</a>
                                                    <br>
                                                    @if ($item->is_removed == false)
                                                        <a href="{{ route('examination.remove') }}?examination={{ $item->id }}"
                                                            class="btn btn-sm btn-outline-danger mt-2">remove</a>
                                                    @else
                                                        <span class="badge bg-info mt-2">REMOVED</span>
                                                    @endif
                                                @endif
                                            @endforeach
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <p>For Examination Scheduled</p>
                @endif


            </div>
        </div>
    @endif
@endif


<div class="card">
    <div class="card-header p-3">
        <label for="" class="text-primary fw-bolder">EXAMINATION PAYMENT OVERVIEW</label>
    </div>
    <div class="card-body">
        @if ($profile->payment)
            @if ($profile->payment->is_approved)
                <p>Payment Approved, Student can now proceed to the Examination</p>
            @else
                <p>Payment is under verification</p>
            @endif
        @else
            @if ($profile->applicant_documents_status())
                <p class="text-muted">
                    For Payment of Entrance Examination Fees
                </p>
            @else
                <p class="text-muted">Documents Requirments is not all Approved</p>
            @endif
        @endif
    </div>
</div>
<div class="modal fade examination-view-modal" id="examination-view-modal" tabindex="-1" role="dialog"
    aria-hidden="true">
    <div class="modal-dialog modal-md">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title text-primary fw-bolder" id="exampleModalLabel1">EXAMINATION DATE</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">
                </button>
            </div>
            <div class="modal-body">
                <form action="{{ route('applicant.examination-rescheduled-date') }}" method="post">
                    @csrf

                    <div class="form-group">
                        <small class="fw-bolder text-muted">EXAMINATION DATE</small>
                        <lable class="form-control form-control-sm border border-primary">
                            @if ($profile->examination_schedule)
                                {{ $profile->examination_schedule->schedule_date }}
                            @endif
                        </lable>
                    </div>
                    <div class="form-group">
                        <small class="fw-bolder text-muted">CHOICE DATE</small>
                        <input type="date" class="form-control form-control-sm border border-primary" name="date">
                        <input type="time" class="form-control form-control-sm border border-primary" name="time">
                    </div>
                    @if ($profile)
                        <input type="hidden" name="applicant" value="{{ $profile->id }}">
                        <button type="submit" class="btn btn-primary float-end">SUBMIT</button>
                    @endif
                </form>
            </div>
        </div>
    </div>
</div>
