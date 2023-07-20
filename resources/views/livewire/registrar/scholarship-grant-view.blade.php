@section('page-title', 'Scholarship Grant')

<div class="row">
    <div class="col-md-8">

    </div>
    <div class="col-md-4">
        <div class="">
            <small class="fw-bolder text-muted">EXPORT OFFICALLY LIST OF SCHOLAR</small>
            <div class="d-flex justify-content-between">
                <a href="{{ route('enrollment.enrolled-list-report') }}?_report=pdf-report{{ request()->input('_academic') ? '&_academic=' . request()->input('_academic') : '' }}"
                    class="badge bg-danger w-100">PDF</a>
            </div>

        </div>
    </div>
</div>
