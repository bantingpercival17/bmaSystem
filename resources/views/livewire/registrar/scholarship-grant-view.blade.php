@section('page-title', 'Scholarship Grant')

<div class="row">
    <div class="col-md-8">
        <p class="display-6 fw-bolder text-primary">Scholarship Grant</p>
        <div class="row">
            <div class="col">
                <small class="text-primary"><b>SEARCH STUDENT NAME</b></small>
                <div class="form-group search-input">
                    <input type="search" class="form-control form-control-sm border border-primary"
                        placeholder="Search Pattern: Lastname, Firstname" wire:model="searchInput">
                </div>
            </div>
            <div class="col-md-4">
                <small class="text-primary"><b>SORT BY</b></small>
                <div class="form-group">
                    <select name="" id="" class="form-select form-select-sm border border-primary">
                        <option value="last-name">LAST NAME</option>
                        <option value="student-number">STUDENT NUMBER</option>
                    </select>
                </div>
            </div>
        </div>
        <div class="d-flex justify-content-between mb-3">
            <div>
                @if ($searchInput != null)
                    <p for="" class="h5">
                        <small class="text-muted"> Search Result:</small>
                        <span class="fw-bolder h6 text-primary"> {{ strtoupper($searchInput) }}</span>
                    </p>
                @else
                    <span class="fw-bolder">
                        Recent Enrollee
                    </span>
                @endif
            </div>

            @if ($searchInput == null)
                @if (count($studentsList) > 0)
                    <div class="mb-3 float-end">
                        {{ $studentsList->links() }}
                    </div>
                @endif
            @else
                <span class="text-muted h6">
                    No. Result: <b>{{ count($studentsList) }}</b>
                </span>
            @endif
        </div>
    </div>
    <div class="col-md-4">
        <div class="row">
            <div class="col-12">
                <small class="text-primary"><b>SCHOLARSHIP LIST</b></small>
                <div class="form-group search-input">
                    <select wire:model="selectScholarship" class="form-select form-select-sm border border-primary">
                        <option value="ALL SCHOLARSHIP">{{ ucwords('all SCHOLARSHIP') }}</option>
                        @forelse ($scholarship as $item)
                            <option value="{{ $item->id }}">{{ $item->voucher_name }}</option>
                        @empty
                        @endforelse
                    </select>
                </div>
            </div>

        </div>
        <div class="">
            <small class="fw-bolder text-muted">EXPORT OFFICALLY LIST OF SCHOLAR</small>
            <div class="d-flex justify-content-between">
                <a href="{{ route('enrollment.enrolled-list-report') }}?_report=pdf-report{{ request()->input('_academic') ? '&_academic=' . request()->input('_academic') : '' }}"
                    class="badge bg-danger w-100">PDF</a>
            </div>

        </div>
    </div>
</div>
