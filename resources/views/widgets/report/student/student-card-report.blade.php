{{-- @extends('widgets.report.grade.report_layout_1') --}}
@extends('widgets.report.app_report_template')
@section('form-code', 'ACC-12')
@section('style')
    <style>
        .account-card-title {
            text-align: center;
            font-weight: 700;
            width: 100%;
        }

        .account-table {
            font-family: Arial, Helvetica, sans-serif;
        }

        .account-table th {
            text-align: center;
        }
    </style>
@endsection
@section('content')
    @foreach ($_student->enrollment_history as $enrollment)
        @if ($enrollment->payment_assessments->payment_assessment_paid)
            <div class="content">
                <label for="" class="account-card-title">MIDSHIPMAN'S ACCOUNT CARD</label>
                <table class="table-content account-table">
                    <tr>
                        <td>NAME:</td>
                        <th>
                            {{ strtoupper($_student->last_name . ', ' . $_student->first_name . ' ' . $_student->middle_name) }}
                        </th>
                        <td>SCHOOL YEAR:</td>
                        <th>{{ $enrollment->academic->school_year }}</th>
                    </tr>
                    <tr>
                        <td>STUDENT NUMBER:</td>
                        <th>{{ $_student->account->student_number }}</th>
                        <td>LEVEL/COURSE/SECTION:</td>
                        @php
                            $_section = $_student->section($enrollment->academic_id)->first();
                        @endphp
                        <th>{{ $_section ? $_section->section->section_name : '-' }}</th>
                    </tr>
                </table>
            </div>
            <div class="page-break"></div>
        @endif
    @endforeach

@endsection
