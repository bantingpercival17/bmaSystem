@php
    $pageTitle = 'Fees';
@endphp
@section('page-title', $pageTitle)
<div>
    <div class="row">
        <div class="col-md-8">
            <p class="display-6 fw-bolder text-primary">{{ strtoupper($pageTitle) }}</p>
            @foreach ($courses as $course)
                <div class="card shadow">
                    <div class="card-header p-3">
                        <h5 class="header-text"><b class="text-primary">{{ $course->course_name }}</b>
                        </h5>
                    </div>
                    <div class="card-body p-5">
                        <div class="table-responsive">
                            <table class="table table-striped" id="datatable" data-toggle="data-table">
                                <thead>
                                    <tr>
                                        <th>Year Level</th>
                                        <th>Full Payment</th>
                                        <th>Installment</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @if (count($course->course_semestral_fees) > 0)
                                        @foreach ($course->course_semestral_fees as $item)
                                            <tr>
                                                <td>
                                                    @if (base64_decode(request()->input('_course')) == 3)
                                                        Grade {{ $item->year_level }}
                                                    @else
                                                        {{ $item->year_level }} / C
                                                    @endif
                                                    - {{ $item->curriculum->curriculum_name }}
                                                </td>
                                                <td>{{-- {{ $item->semestral_fees() }} --}}
                                                    {{ $item->total_tuition_fee($item) ? number_format($item->total_tuition_fee($item), 2) : '0.00' }}
                                                </td>
                                                <td>
                                                    {{ number_format($item->installment_fee($item, $item->total_tuition_fee($item)), 2) }}
                                                </td>
                                                <td>
                                                    <a href="{{ route('accounting.course-fee-view-list') . '?_course_fee=' . base64_encode($item->id) }}"
                                                        class="btn btn-primary btn-sm">View</a>
                                                    <a href="{{ route('accounting.course-fee-remove') . '?_course_fee=' . base64_encode($item->id) }}"
                                                        class="btn btn-danger btn-sm">Remove</a>
                                                </td>
                                            </tr>
                                        @endforeach
                                    @endif
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
        <div class="col-md-4">

        </div>
    </div>
</div>
