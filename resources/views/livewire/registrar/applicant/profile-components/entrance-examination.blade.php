<div class="card">
    <div class="card-body">
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

                                {{ $profile->applicant_examination->examination_result()[0] }}</h3>
                        </div>
                        <div class="col-md">
                            <small class="fw-bolder">PERCENTILE</small>
                            <h3 class="text-primary fw-bolder mt-3">

                                {{ $profile->applicant_examination->examination_result()[1] }}</h3>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md">
                <p>
                    <a href="{{ route('applicant-examination-reset') }}?_applicant={{ base64_encode($profile->id) }}"
                        class="">Reset Examination</a>
                </p>
            </div>
        </div>
        @foreach (Auth::user()->roles as $role)
            @if ($role->id == 1)
                @foreach ($profile->examination_list as $data)
                    <div class="row">
                        <div class="col-md">
                            <label for="" class="text-info">EXAMINATION STATUS</label>
                            {{ $data->is_finish }}
                            @if ($data->is_finish === 1)
                                <span class="fw-bolder">Examination Done</span>
                            @elseif($data->is_finish === 0)
                                <span class="fw-bolder">Examination Ongoing</span>
                            @else
                                <span class="fw-bolder">Ready for Examination</span>
                            @endif
                        </div>
                        <div class="col-md">
                            <label for="" class="text-info">EXAMINATION CODE</label>
                            <span class="fw-bolder">{{ $data->examination_code }}</span>
                        </div>
                        <div class="col-md">
                            <label for="" class="text-info">IS REMOVE</label>
                            @if ($data->is_removed == false)
                                <br>
                                <a href="{{ route('examination.remove') }}?examination={{ $data->id }}"
                                    class="btn btn-sm btn-danger">remove</a>
                            @else
                                <br>
                                <span class="badge bg-info">REMOVED</span>
                            @endif
                        </div>
                    </div>
                @endforeach
            @endif
        @endforeach
    </div>
</div>
