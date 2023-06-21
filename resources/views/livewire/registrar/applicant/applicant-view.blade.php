@php
    $pageTitle = "Applicant's Overview";
@endphp
@section('page-title', $pageTitle)
<div class="row">
    <div class="col-md-12">
        <p class="display-6 fw-bolder text-primary">{{ $pageTitle }}</p>
        <div class="row">
            <div class="col-lg-4">
                <p class="h4 text-info fw-bolder">FILTER SELECTION</p>
                <div class="row">
                    <div class="col-12">
                        <small class="text-primary"><b>SEARCH STUDENT NAME</b></small>
                        <div class="form-group search-input">
                            <input type="search" class="form-control" placeholder="Search Pattern: Lastname, Firstname"
                                wire:model="searchInput">
                        </div>
                    </div>
                    <div class="col-12">
                        <small class="text-primary"><b>CATEGORY</b></small>
                        <div class="form-group search-input">
                            <select class="form-select" wire:model="selectCategories">
                                @foreach ($filterContent as $item)
                                    <option value="{{ $item }}">{{ ucwords(str_replace('_', ' ', $item)) }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="col-12">
                        <small class="text-primary"><b>COURSE</b></small>
                        <div class="form-group search-input">
                            <select wire:model="selectCourse" class="form-select" wire:click="categoryCourse">
                                <option value="ALL COURSE">{{ ucwords('all courses') }}</option>
                                @foreach ($filterCourses as $course)
                                    <option value="{{ $course->id }}">{{ ucwords($course->course_name) }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-8">
                <div class="filter-section m-0 p-0">
                    <small class="fw-bolder text-info">FILTER DETAILS:</small>
                    {{base64_decode($academic)}}
                    <div class="row">
                        <div class="col-md-6">
                            <small class="fw-bolder text-muted">CATEGORY : </small> <br>
                            <label for=""
                                class="fw-bolder text-primary">{{ str_replace('_', ' ', strtoupper($selectCategories)) }}</label>
                        </div>
                        <div class="col-md-6">
                            <small class="fw-bolder text-muted">COURSE : </small> <br>
                            <label for="" class="fw-bolder text-primary">{{ $selectedCourse }}</label>
                        </div>
                    </div>
                </div>
                <div class="d-flex justify-content-between mb-3">
                    @if ($searchInput != '')
                        <div>
                            <p for="" class="h5">
                                <small class="text-muted"> Search Result:</small>
                                <span class="fw-bolder h6 text-primary"> {{ strtoupper($searchInput) }}</span>
                            </p>
                        </div>
                        <div>
                            No. Result: <b>{{ count($dataLists) }}</b>
                        </div>
                    @else
                        <div>
                            <span class="fw-bolder">
                                RECENT DATA
                            </span>
                        </div>
                        <div>
                            No. Result: <b>{{ count($dataLists) }}</b>
                        </div>
                    @endif
                </div>
                <div class="data-content">
                    @if (count($dataLists) > 0)
                        {{--  @foreach ($dataLists as $item)
                            <p> {{ $item }}</p>
                        @endforeach --}}
                        @foreach ($dataLists as $data)
                            <div class="card">
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-md-9">
                                            <p class="fw-bolder text-muted mb-0">
                                                <span class="badge bg-primary">{{ $data->course->course_name }}</span>
                                                |
                                                {{ $data->applicant ? $data->applicant_number : '-' }}
                                            </p>
                                            <a href="{{ route('applicant.profile-view') }}?_applicant={{ base64_encode($data->id) }}&_academic={{ $this->academic }}"
                                                class="text-primary fw-bolder h2">
                                                {{ strtoupper($data->applicant->last_name . ', ' . $data->applicant->first_name) }}
                                            </a>


                                            <div class="mt-0">
                                                <span>{{ $data->applicant ? $data->email : '-' }}</span> <br>
                                               {{--  <span class="badge bg-secondary">
                                                    @php
                                                        echo $data->applicant->check_duplicate();
                                                    @endphp
                                                </span> --}}
                                            </div>


                                        </div>
                                        <div class="col-md">
                                            <small>APPLICATION DATE</small>
                                            <div class="badge bg-primary w-100">

                                                <span>{{ $data->created_at->format('F d, Y') }}</span>
                                            </div>

                                            <a href="{{ route('applicant-removed') }}?_applicant={{ base64_encode($data->id) }}"
                                                class="badge bg-danger text-white w-100">REMOVE
                                            </a>
                                        </div>
                                    </div>
                                </div>
                                {{-- <div class="card-footer">
                                    @if ($data->applicant_documents->count() > 0)
                                        <div class="badge bg-info">DOCUMENTS- </div>
                                        @if ($data->applicant_documents_status())
                                            <div class="badge bg-info">VERIFIED-</div>
                                            @if ($data->payment)
                                                <div class="badge bg-info">PAYMENT-</div>
                                                @if ($data->payment->is_approved == true)
                                                    <div class="badge bg-info">APPROVED-</div>
                                                @else
                                                    <div class="badge bg-secondary">NOT-APPROVED </div>
                                                    {{ $data->payment->is_approved }}
                                                @endif
                                            @else
                                                <div class="badge bg-secondary">NO-PAYMENT </div>
                                            @endif
                                        @else
                                            <div class="badge bg-secondary">NOT-VERIFIED </div>
                                        @endif
                                        {{ $data->applicant_document_status }}
                                    @else
                                        <div class="badge bg-secondary">NO-DOCUMENTS</div>
                                    @endif
                                </div> --}}
                            </div>
                        @endforeach
                    @else
                        <div class="card">
                            <div class="card-body">
                                <div class="d-flex justify-content-between">
                                    <div>
                                        <div class="mt-2">
                                            <h2 class="counter" style="visibility: visible;">
                                                NO DATA
                                            </h2>
                                        </div>


                                    </div>
                                </div>
                            </div>
                        </div>
                    @endif
                </div>
            </div>

        </div>
    </div>
</div>
@push('scripts')
    <script src="{{ asset('assets\plugins\sweetalert2\sweetalert2.all.min.js') }}"></script>
    <link rel="stylesheet" href="{{ asset('assets\plugins\sweetalert2\sweetalert2.min.css') }}">
    <script>
        window.addEventListener('swal:alert', event => {
            Swal.fire({
                title: event.detail.title,
                text: event.detail.text,
                icon: event.detail.type,
            });
        })
        document.addEventListener('DOMContentLoaded', function() {
            Livewire.on('swal:confirm', function(options) {
                Swal.fire({
                    title: options.title,
                    text: options.text,
                    icon: options.type,
                    showCancelButton: true,
                    confirmButtonText: options.confirmButtonText,
                    cancelButtonText: options.cancelButtonText,
                }).then((result) => {
                    if (result.isConfirmed && options.method) {
                        Livewire.emit(options.method);
                    }
                });
            });

            Livewire.on('swal:alert', function(options) {
                Swal.fire({
                    title: options.title,
                    text: options.text,
                    icon: options.type,
                });
            });
        });
    </script>
@endpush
