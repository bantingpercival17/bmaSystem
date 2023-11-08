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
                    <a href="{{ route('applicant-examination-result') }}?_applicant={{ base64_encode($profile->id) }}"
                        class="btn btn-primary btn-sm">Examination Result</a>
                    <a href="{{ route('applicant-examination-log') }}?_applicant={{ base64_encode($profile->id) }}"
                        class="btn btn-secondary btn-sm">Examination Log</a>
                    <div class="row">
                        <div class="col-md">
                            <div class="form-view">
                                <small
                                    class="badge bg-info">{{ $profile->applicant_examination->updated_at->format('F d, Y') }}</small>
                                <div class="row">
                                    <div class="col-md">
                                        <small class="fw-bolder">SCORE</small>
                                        <h3 class="text-primary fw-bolder mt-3">

                                            {{ $profile->applicant_examination->examination_result()[0] }}
                                        </h3>
                                    </div>
                                    <div class="col-md">
                                        <small class="fw-bolder">PERCENTILE</small>
                                        <h3 class="text-primary fw-bolder mt-3">

                                            {{ $profile->applicant_examination->examination_result()[1] }}
                                        </h3>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-md">
                            <p>

                            </p>
                        </div>
                    </div>
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
                                            </div>

                                        </td>
                                        <td>
                                            <a href="{{ route('applicant-examination-result-v2') }}?examination={{ base64_encode($item->id) }}"
                                                class="btn btn-primary btn-sm">Text
                                                Exam</a>
                                            <a href="{{ route('applicant-examination-log-v2') }}?examination={{ base64_encode($item->id) }}"
                                                class="btn btn-outline-primary btn-sm">Logs</a>
                                            @foreach (Auth::user()->roles as $role)
                                                @if ($role->id == 1)
                                                    @foreach ($profile->examination_list as $data)
                                                        @if ($data->is_removed == false)
                                                            <br>
                                                            <a href="{{ route('examination.remove') }}?examination={{ $data->id }}"
                                                                class="btn btn-sm btn-outline-danger">remove</a>
                                                        @else
                                                            <br>
                                                            <span class="badge bg-info">REMOVED</span>
                                                        @endif
                                                    @endforeach
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
