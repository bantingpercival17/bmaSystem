@php
    $pageTitle = "Applicant's Overview";
@endphp
@section('page-title', $pageTitle)
<div class="row">
    <div class="col-md-12">
        <p class="display-6 fw-bolder text-primary">{{ $pageTitle }}</p>
        <div class="row">
            <div class="col-lg-8">

                <div class="filter-section m-0 p-0">
                    <small class="fw-bolder text-muted">FILTER DETAILS:</small>
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
                <div class="">
                    {{-- {{ $dataLists }} --}}
                </div>
                <div class="data-content">
                    @forelse ($dataLists as $data)
                        <div class="card">
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-lg-8 col-md-12">
                                        <p class="fw-bolder text-muted mb-0">
                                            <span class="badge bg-primary">{{ $data->course->course_name }}</span>
                                            |
                                            {{ $data ? $data->applicant_number : '-' }}
                                        </p>
                                        <a target="_blank"
                                            href="{{ route('applicant.profile-view') }}?_applicant={{ base64_encode($data->id) }}&_academic={{ $this->academic }}"
                                            class="text-primary fw-bolder h2">
                                            {{ $data->applicant ? strtoupper($data->applicant->last_name . ', ' . $data->applicant->first_name) : $data->name }}
                                        </a>
                                        <div class="mt-0">
                                            <span
                                                class="fw-bolder text-muted">{{ $data ? $data->contact_number : '-' }}</span>
                                            <span>{{ $data ? $data->email : '-' }}</span> <br>
                                            @if ($data->applicant)
                                                @if ($data->similar_account())
                                                    <span>SIMILAR APPLICATION</span>
                                                @endif
                                            @endif
                                        </div>
                                    </div>
                                    <div class="col-lg-4 col-md-12">
                                        @if ($selectCategories != 'created_accounts' && $selectCategories != 'registered_applicants' && $selectCategories !='total_registrants')
                                            @if ($selectCategories == 'passed' || $selectCategories == 'failed' || $selectCategories == 'took_the_exam')
                                                <div class="form-view">
                                                    <small class="text-muted fw-bolder">EXAMINATION DATE:</small>
                                                    <small
                                                        class="badge bg-info">{{ $data->applicant_examination->updated_at->format('F d, Y') }}</small>
                                                    <div class="row">
                                                        <div class="col-md">
                                                            <small class="fw-bolder">SCORE</small>
                                                            <h3 class="text-primary fw-bolder ">

                                                                {{ $data->applicant_examination->examination_result()[0] }}
                                                            </h3>
                                                        </div>
                                                        <div class="col-md">
                                                            <small class="fw-bolder">PERCENTILE</small>
                                                            <h3 class="text-primary fw-bolder">

                                                                {{ $data->applicant_examination->examination_result()[1] }}
                                                            </h3>
                                                        </div>
                                                    </div>
                                                    <a target="_blank"
                                                        href="{{ route('applicant.admission-slip') . '?applicant=' . base64_encode($data->id) }}"
                                                        class="btn btn-primary btn-sm">
                                                        ADMISSION SLIP
                                                    </a>
                                                </div>
                                            @elseif($selectCategories == 'expected_attendees')
                                                <small>BRIEFING DATE</small>
                                                <div class="badge bg-info w-100">
                                                    <span>{{ \Carbon\Carbon::parse($data->schedule_orientation->schedule_date . ' ' . $data->schedule_orientation->schedule_time)->format('F d,Y  h:i A') }}
                                                    </span>
                                                </div>
                                                <a href="{{ route('applicant.orientation-attended') }}?applicant={{ $data->id }}"
                                                    class="btn btn-primary btn-sm w-100 mt-2">Present Participant</a>
                                            @elseif($selectCategories == 'examination_payment')
                                                <small>EXAMINATION PAYMENT</small> <br>
                                                @if ($data->payment->is_approved === null)
                                                    @php
                                                        $content =
                                                            'Upload Date: ' .
                                                            $data->payment->created_at->format('F d,Y');
                                                    @endphp

                                                    <label class="badge bg-info" role="button" data-bs-toggle="popover"
                                                        data-trigger="focus"
                                                        title="Upload Date: {{ $data->payment->created_at->format('F d,Y') }}">
                                                        FOR VERIFICATION
                                                    </label>
                                                @else
                                                    <label class="badge bg-danger" role="button"
                                                        data-bs-toggle="popover" data-trigger="focus"
                                                        title="Upload Date: {{ $data->payment->created_at->format('F d, Y') }}, VERIFICATION DATE: {{ $data->payment->updated_at->format('F d, Y') }}">
                                                        DISAPPROVED PAYMENT
                                                    </label>
                                                    {{-- <label for="" class="badge bg-primary">
                                                        {{ $data->payment->is_approved }}</label> --}}
                                                @endif
                                            @else
                                                <small>APPLICATION DATE</small>
                                                <div class="badge bg-primary w-100">
                                                    <span>{{ $data->created_at->format('F d, Y') }}</span>
                                                </div>
                                            @endif
                                        @elseif($selectCategories == 'entrance_examination')
                                            <small>EXAMINATION DATE</small>
                                            <div class="badge bg-primary w-100">
                                                <span>{{ $data->applicant_examination->created_at->format('F d, Y') }}</span>
                                            </div>
                                        @else
                                            <small>APPLICATION DATE</small>
                                            <div class="badge bg-primary w-100">

                                                <span>{{ $data->created_at->format('F d, Y') }}</span>
                                            </div>

                                            <a href="{{ route('applicant-removed') }}?_applicant={{ base64_encode($data->id) }}"
                                                class="btn btn-sm btn-outline-primary w-100 mt-2">REMOVE
                                            </a>
                                        @endif

                                    </div>
                                </div>
                            </div>
                            @if ($selectCategories == 'passed')
                                <div class="card-footer">
                                    @if (!$data->medical_appointment)
                                        @if (!$data->schedule_orientation)
                                            <small class="text-muted fw-bolder">ORIENTATION SCHEDULED</small>
                                            <form action="{{ route('applicant.orientation-scheduled') }}"
                                                method="post">
                                                @csrf
                                                <input type="hidden" name="applicant"
                                                    value="{{ base64_encode($data->id) }}">
                                                <div class="row">
                                                    <div class="col-md">
                                                        <small class="text-muted fw-bolder">CATEGORY</small>
                                                        <select name="category" id=""
                                                            class="form-select form-select-sm">
                                                            <option value="in-person">IN-PERSON ORIENTATION</option>
                                                            <option value="online">ONLINE ORIENTATION</option>
                                                        </select>
                                                    </div>
                                                    <div class="col-md">
                                                        <small class="text-muted fw-bolder">DATE</small>
                                                        <input type="date" class="form-control form-control-sm"
                                                            name="date">
                                                    </div>
                                                    <div class="col-md">
                                                        <small class="text-muted fw-bolder">TIME</small>
                                                        <input type="time" class="form-control form-control-sm"
                                                            name="time">
                                                    </div>
                                                </div>
                                                <div class="float-end mt-2">
                                                    <button type="submit"
                                                        class="btn btn-primary btn-sm">SUBMIT</button>
                                                </div>
                                            </form>
                                        @else
                                            <div class="form-group">
                                                <div class="row">
                                                    <div class="col-md">
                                                        <small class="text-muted fw-bolder">CATEGORY</small> <br>
                                                        <label for=""
                                                            class="text-info fw-bolder">{{ strtoupper($data->schedule_orientation->category) }}
                                                            ORIENTATION</label>
                                                    </div>
                                                    <div class="col-md">
                                                        <small class="text-muted fw-bolder">DATE</small><br>
                                                        <label for=""
                                                            class="text-info fw-bolder">{{ $data->schedule_orientation->schedule_date }}</label>
                                                    </div>
                                                    <div class="col-md">
                                                        <small class="text-muted fw-bolder">TIME</small><br>
                                                        <label for=""
                                                            class="text-info fw-bolder">{{ $data->schedule_orientation->schedule_time }}</label>
                                                    </div>
                                                </div>
                                            </div>
                                            <form action="{{ route('applicant.orientation-scheduled') }}"
                                                method="post">
                                                @csrf
                                                <input type="hidden" name="applicant"
                                                    value="{{ base64_encode($data->id) }}">
                                                <div class="row">
                                                    <div class="col-md">
                                                        <small class="text-muted fw-bolder">CATEGORY</small>
                                                        <select name="category" id=""
                                                            class="form-select form-select-sm">
                                                            <option value="in-person">IN-PERSON ORIENTATION</option>
                                                            <option value="online">ONLINE ORIENTATION</option>
                                                        </select>
                                                    </div>
                                                    <div class="col-md">
                                                        <small class="text-muted fw-bolder">DATE</small>
                                                        <input type="date" class="form-control form-control-sm"
                                                            name="date">
                                                    </div>
                                                    <div class="col-md">
                                                        <small class="text-muted fw-bolder">TIME</small>
                                                        <input type="time" class="form-control form-control-sm"
                                                            name="time">
                                                    </div>
                                                </div>
                                                <div class="float-end mt-2">
                                                    <button type="submit"
                                                        class="btn btn-primary btn-sm">SUBMIT</button>
                                                </div>
                                            </form>
                                        @endif
                                    @endif

                                </div>
                            @endif
                        </div>
                    @empty
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
                    @endforelse
                </div>
            </div>
            <div class="col-lg-4">
                <p class="h4 text-info fw-bolder">FILTER SELECTION {{ $academic }}</p>

                <div class="row">
                    <div class="col-md-12">
                        <small class="text-primary"><b>SEARCH STUDENT NAME</b></small>
                        <div class="form-group">
                            <input type="search" class="form-control border border-primary"
                                placeholder="Search Pattern: Lastname, Firstname" wire:model="searchInput">
                        </div>
                    </div>
                    <div class="col-md-12">
                        <small class="text-primary"><b>CATEGORY</b></small>
                        <div class="form-group">
                            <select class="form-select border border-primary" wire:model="selectCategories"
                                wire:change="">
                                @foreach ($filterContent as $item)
                                    <optgroup label="{{ $item[0] }}">
                                        @foreach ($item[1] as $item)
                                            <option value="{{ $item }}">
                                                {{ ucwords(str_replace('_', ' ', $item)) }}
                                            </option>
                                        @endforeach
                                    </optgroup>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="col-md-12">
                        <small class="text-primary"><b>COURSE</b></small>
                        <div class="form-group  ">
                            <select wire:model="selectCourse" class="form-select border border-primary"
                                wire:change="categoryCourse">
                                <option value="ALL COURSE">{{ ucwords('all courses') }}</option>
                                @foreach ($filterCourses as $course)
                                    <option value="{{ $course->id }}">{{ ucwords($course->course_name) }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    @if ($selectCategories == 'waiting_examination_payment')
                        <div class="col-md-12">
                            <small class="text-primary"><b>EMAIL NOTIFICATION</b></small>
                            {{-- <a href="{{ route('applicant.entrance-examination') . '?_academic=' . $academic }}{{ $selectCourse ? '&_course=' . base64_encode($selectCourse) : '' }}"
                        class="btn btn-primary btn-sm w-100">SEND NOTIFICATION</a> --}}
                            <button onclick="payment_notification({{ $dataLists }})"
                                class="btn btn-primary btn-sm w-100 btn-notification">SEND
                                NOTIFICATION</button>
                        </div>
                    @endif
                    @if ($selectCategories == 'for_medical_schedule')
                        <div class="col-md-12">
                            <small class="text-primary"><b>MEDICAL ORIENTATION NOTIFICATION</b></small>
                            <button onclick="notification_v2({{ $dataLists }})"
                                class="btn btn-primary btn-sm w-100 btn-notification">SEND
                                NOTIFICATION</button>
                        </div>
                    @endif

                </div>
                @if (Auth::user()->email === 'p.banting@bma.edu.ph')
                    @if ($selectCategories == 'registered_applicants_v1' || $selectCategories == 'pending')
                        <div class="col-md-12">
                            <small class="text-primary"><b>DOCUMENTRARY NOTIFICATION</b></small>
                            <button onclick="document_notification({{ $dataLists }})"
                                class="btn btn-primary btn-sm w-100 btn-notification">
                                SEND NOTIFICATION
                            </button>
                        </div>
                    @endif
                    @if ($selectCategories == 'passed')
                        <div class="col-md-12">
                            <small class="text-primary"><b>MEDICAL ORIENTATION NOTIFICATION</b></small>
                            <a href="{{ route('applicant.entrance-examination-result') }}"
                                class="btn btn-sm btn-primary w-100">OPEN RESULTS</a>
                        </div>
                    @endif
                    <div class="debug">
                        {{ json_encode($filterData) }}
                    </div>
                @endif

            </div>
        </div>

    </div>
</div>
</div>
@section('script')
    <script>
        function mail_notification(data, link) {
            data.forEach(async (element) => {
                const data = {
                    applicant: encodeToBase64(element.id),
                }
                try {
                    const queryString = new URLSearchParams(data).toString();
                    const urlWithParams = `${link}?${queryString}`;
                    console.log(urlWithParams)
                    const response = await fetch(urlWithParams, {
                        method: 'GET',
                        headers: {
                            'Content-Type': 'application/json',
                        },
                    });

                    if (!response.ok) {
                        throw new console.log('Network response was not ok');
                    }

                    const responseData = await response.json();
                    console.log('Successful Sent to  ' + element.name);
                } catch (error) {
                    console.error('There was a problem with the fetch operation:', error);
                    console.log('Error: ' + error.message);
                }
            })
        }

        function document_notification(data) {
            const link = "{{ route('applicant.notification-upload-documents') }}";
            mail_notification(data, link)
        }

        function payment_notification(data) {
            const link = "{{ route('applicant.entrance-examination-v2') }}";
            mail_notification(data, link)
        }

        function notification_v2(data) {
            //const dates = getDates();
            const dates = '2024-05-31'
            const link = "{{ route('applicant.orientation-scheduled-v2') }}";
            data.forEach(async (element) => {
                const data = {
                    applicant: encodeToBase64(element.id),
                    date: dates,
                    time: '10:00',
                    category: 'in-person'
                }
                try {
                    const queryString = new URLSearchParams(data).toString();
                    const urlWithParams = `${link}?${queryString}`;
                    const response = await fetch(urlWithParams, {
                        method: 'GET',
                        headers: {
                            'Content-Type': 'application/json',
                        },
                    });

                    if (!response.ok) {
                        throw new console.log('Network response was not ok');
                    }

                    const responseData = await response.json();
                    console.log('Successful Sent to  ' + element.name);
                } catch (error) {
                    console.error('There was a problem with the fetch operation:', error);
                    console.log('Error: ' + error.message);
                }
            });
        }


        function encodeToBase64(str) {
            return btoa(str);
        }

        function getDates() {
            // Get the current date
            const currentDate = new Date();

            // Add 2 days
            currentDate.setDate(currentDate.getDate() + 2);

            // Format the date as YYYY-MM-DD
            const year = currentDate.getFullYear();
            const month = String(currentDate.getMonth() + 1).padStart(2, '0'); // Months are zero-based
            const day = String(currentDate.getDate()).padStart(2, '0');

            const formattedDate = `${year}-${month}-${day}`;
            return formattedDate
        }
    </script>
@endsection
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
