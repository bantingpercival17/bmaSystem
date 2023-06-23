<div class="card">
    <div class="card-body">
        <div class="form">
            <label for="" class="fw-bolder h5 text-primary">ORIENTATION SCHEDULE</label>
            @if (!$profile->schedule_orientation)
                <form action="{{ route('applicant.orientation-scheduled') }}" method="post">
                    @csrf
                    <input type="hidden" name="applicant" value="{{ base64_encode($profile->id) }}">
                    <div class="row">
                        <div class="col-md">
                            <small class="text-muted fw-bolder">CATEGORY</small>
                            <select name="category" id="" class="form-select form-select-sm">
                                <option value="in-person">IN-PERSON ORIENTATION</option>
                                <option value="online">ONLINE ORIENTATION</option>
                            </select>
                        </div>
                        <div class="col-md">
                            <small class="text-muted fw-bolder">DATE</small>
                            <input type="date" class="form-control form-control-sm" name="date">
                        </div>
                        <div class="col-md">
                            <small class="text-muted fw-bolder">TIME</small>
                            <input type="time" class="form-control form-control-sm" name="time">
                        </div>
                    </div>
                    <div class="float-end mt-2">
                        <button type="submit" class="btn btn-primary btn-sm">SUBMIT</button>
                    </div>
                </form>
            @else
                <div class="form-group">
                    <div class="row">
                        <div class="col-md">
                            <small class="text-muted fw-bolder">CATEGORY</small> <br>
                            <label for=""
                                class="text-info fw-bolder">{{ strtoupper($profile->schedule_orientation->category) }}
                                ORIENTATION</label>
                        </div>
                        <div class="col-md">
                            <small class="text-muted fw-bolder">DATE</small><br>
                            <label for=""
                                class="text-info fw-bolder">{{ $profile->schedule_orientation->schedule_date }}</label>
                        </div>
                        <div class="col-md">
                            <small class="text-muted fw-bolder">TIME</small><br>
                            <label for=""
                                class="text-info fw-bolder">{{ $profile->schedule_orientation->schedule_time }}</label>
                        </div>
                    </div>
                </div>
            @endif

            @if ($profile->schedule_orientation)
                <small class="fw-bolder text-muted">ATTENDED ON ORIENTATION</small>
                <small
                    class="badge bg-info">{{ $profile->schedule_orientation->schedule_date . ' ' . $profile->schedule_orientation->schedule_time }}</small>
                <a href="{{ route('applicant.orientation-attended') }}?applicant={{ $profile->id }}"
                    class="btn btn-primary btn-sm w-100 mt-2">ATTENDED</a>
            @else
                <small class="badge bg-secondary"></small>
            @endif
        </div>
    </div>
</div>
