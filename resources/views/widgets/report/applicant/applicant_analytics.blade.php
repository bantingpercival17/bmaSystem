@extends('widgets.report.app_report_template_v2')
@section('title-report', 'ENTRANCE EXAMINATION ANALYTICS')
@section('style')
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/charts.css/dist/charts.min.css">
@endsection
@section('form-code', '')


@section('content')
    <div class="page-content">
        <h3 class="text-center"><b>ENTRANCE EXAMINATION ANALYTICS</b></h3>

        @foreach ($courses as $item)
            <h4> {{ $item->course_name }}</h4>
            <table id="basic-table" class="table table-striped mb-0" role="grid">
                <thead>
                    <tr class="text-center">
                        <th></th>
                        @foreach ($data as $item)
                            <th> {{ $item->school_year }}</th>
                        @endforeach
                    </tr>
                </thead>
                <tbody>
                    @foreach ($tableHeader as $index => $headers)
                        <tr>
                            <th colspan="4" class="text-center fw-bolder text-primary">
                                <a target="_blank"
                                    href="{{ route($headers[2]) }}?category={{ str_replace(' ', '-', strtolower($headers[0])) }}{{ request()->input('_academic') ? '&_academic=' . request()->input('_academic') : '' }}">
                                    {{ strtoupper($headers[0]) }}
                                </a>

                            </th>
                        </tr>
                        @foreach ($headers[1] as $item)
                            <tr>
                                <th>{{ strtoupper(str_replace('_', ' ', $item)) }}</th>
                                @foreach ($courses as $course)
                                    <td class="text-center">
                                        <a
                                            href="{{ route('applicant.overview') . '?_course=' . base64_encode($course->id) . '&_category=' . $item }}{{ request()->input('_academic') ? '&_academic=' . request()->input('_academic') : '' }}">
                                            {{ count($course->applicant_count_per_category($item)) }}
                                        </a>
                                    </td>
                                @endforeach
                            </tr>
                        @endforeach
                    @endforeach
                </tbody>
            </table>
            <br>
        @endforeach
    </div>
@endsection
