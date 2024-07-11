<div class="card">
    <div class="card-header pb-0 p-3">
        <h5 class="mb-1 text-primary"><b>ASSESSMENT FOR INCOMMING FIRST CLASS</b></h5>
    </div>
    <div class="card-body p-3">
        @if ($profile->onboard_examination)
        @if ($profile->assessment_details)
        <form>
            <div class="row">
                <div class="col-md">
                    <small for="" class="text-muted fw-bolder">WRITEN EXAM SCORE</small>
                    <label for="" class="form-control form-control-sm border border-primary">{{ $profile->onboard_examination->result->count() }}</label>
                </div>
                <div class="col-md">
                    <div class="form-group">
                        <small class="fw-bolder text-muted">PRACTICAL ASSESSMENT</small>
                        <label for="" class="form-control form-control-sm border border-primary">{{ $profile->assessment_details->practical_score }}</label>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md">
                    <div class="form-group">
                        <small>ORAL ASSESSMENT</small>
                        <label for="" class="form-control form-control-sm border border-primary">
                            {{ $profile->assessment_details->oral_score }}
                        </label>

                    </div>
                </div>
                <div class="col-md">
                    <div class="form-group">
                        <small>ASSESSOR</small>
                        <label for="" class="form-control form-control-sm border border-primary">{{ $profile->assessment_details->staff->full_name() }}</label>
                    </div>
                </div>
            </div>
            <div class="float-end">
                <a class="btn btn-outline-primary btn-sm">
                    UPDATE RESULT
                </a>
                <a href="{{ route('onboard.assessment-report-v2') }}?_midshipman={{ base64_encode($profile->id) }}" target="_blank" class="btn btn-primary btn-sm">
                    GENERATE REPORT OBT-12
                </a>
            </div>
        </form>
        @else
        <form action="{{ route('onboard.assessment-report') }}" method="post">
            @csrf
            <input type="hidden" name="_midshipman" value="{{ base64_encode($profile->id) }}">
            <div class="row">
                <div class="col-md">
                    <small for="" class="text-muted fw-bolder">WRITEN EXAM SCORE</small>
                    @if ($profile->onboard_examination->is_finish)
                    <label type="text" class="form-control form-control-sm border border-primary">
                        {{ $profile->onboard_examination->result->count() }}
                    </label>
                    <input type="hidden" name="_assessment_score" value="{{ $profile->onboard_examination->result->count() }}">
                    @else
                    <div class="form-group">
                        <small for="" class="form-label fw-bolder">EXAMINATION
                            CODE</small> <br>
                        <span class="text-primary h6 fw-bolder">{{ $profile->onboard_examination->examination_code }}</span>
                    </div>
                    @endif
                </div>
                <div class="col-md">
                    <div class="form-group">
                        <small class="fw-bolder text-muted">PRACTICAL ASSESSMENT</small>
                        @if ($profile->assessment_details)
                        <input type="text" class="form-control form-control-sm border border-primary" name="_practical_score" value="{{ $profile->assessment_details->practical_score }}">
                        @else
                        <input type="text" class="form-control  form-control-sm border border-primary" name="_practical_score" value="{{ old('_practical_score') }}">
                        @error('_practical_score')
                        <span class="badge bg-danger mt-2">{{ $message }}</span>
                        @enderror
                        @endif
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md">
                    <div class="form-group">
                        <small>ORAL ASSESSMENT</small>
                        @if ($profile->assessment_details)
                        <input type="text" class="form-control form-control-sm border border-primary " name="_oral_score" value="{{ $profile->assessment_details->oral_score }}">
                        @else
                        <input type="text" class="form-control form-control-sm border border-primary " name="_oral_score" value="{{ old('_oral_score') }}">
                        @error('_oral_score')
                        <span class="badge bg-danger mt-2">{{ $message }}</span>
                        @enderror
                        @endif

                    </div>
                </div>
                <div class="col-md">
                    <div class="form-group">
                        <small>ASSESSOR</small>
                        <select name="_assessor" class="form-select form-select-sm border border-primary">
                            @foreach ($assessors as $item)
                            <option value="{{ $item->id }}">
                                {{ strtoupper($item->first_name . ' ' . $item->last_name) }}
                            </option>
                            @endforeach

                        </select>
                    </div>
                </div>
            </div>
            <button class="btn btn-primary btn-sm float-end generate-report-button" type="submit">GENERATE
                REPORT
                OBT-12</button>
        </form>
        @endif
        @else
        <a href="{{ route('onboard.examination') . '?_midshipman=' . base64_encode($profile->id) }}" class="btn btn-primary btn-sm w-100">
            APPROVE FOR EXAMINATION
        </a>
        <!-- <button class="btn btn-primary btn-sm btn-onboard-examination w-100"
                data-url="{{ route('onboard.examination') . '?_midshipman=' . base64_encode($profile->id) }}">APPROVE
                FOR
                EXAMINATION</button> -->
        @endif

    </div>
</div>
<div class="card mt-4">
    <div class="card-header pb-0 p-3">
        <h5 class="mb-1 text-primary"><b>COMPREHENSIVE EXAMINATION</b></h5>
    </div>
    <div class="card-body p-3">
        @if ($profile->comprehensive_examination)
        <p class="text-info">WAITING FOR EXAMINATION SCHEDULED</p>
        @else
        <a href="{{ route('onboard.comprehensive-examination-examinee', base64_encode($profile->id)) }}" class="btn btn-primary btn-sm w-100">
            APPROVE COMPREHENSIVE EXAMINATION
        </a>
        @endif
    </div>
</div>

@section('script')
<script>
    $('.btn-onboard-examination').click(function(event) {
        var _url = $(this).data('url');
        console.log(_url)
        Swal.fire({
            title: 'Shipboard Examination',
            text: "are you sure do you want to submit?",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Yes'
        }).then((result) => {
            if (result.isConfirmed) {
                window.location.href = _url
            }
        })
        event.preventDefault();
    })
    $('.generate-report-button').click(function(event) {
        var form = $(this).data('url');
        Swal.fire({
            title: 'Generate Report',
            text: "Do you want to Generate a Report?",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Yes'
        }).then((result) => {
            if (result.isConfirmed) {
                document.getElementById(form).submit()
            }
        })
        event.preventDefault();
    })
</script>
@endsection