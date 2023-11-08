@extends('widgets.report.main-report-template')
@section('title-report', 'EXAMINATION EXAMINATION - ' . $data->applicant->applicant_number)
@section('form-code', '')
@section('content')
    <style>
        .label-choice {
            padding: 5px;
            font-family: corbel, sans-serif;
            font-size: 7px;
            margin: 10px;
            vertical-align: middle;
        }
    </style>
    <div class="page-content">
        <h3 style="text-align: center"><b>EXAMINATION EXAMINATION</b></h3>
        <table class="table-content">
            <tbody>
                <tr>
                    <td>APPLICANT NO: </td>
                    <th>
                        {{ strtoupper($data->applicant->applicant_number) }}
                    </th>
                    <td> SCORE:</td>
                    <th> {{ $data->applicant->applicant_examination->examination_result()[1] }} /
                        {{ $data->applicant->course_id != 3 ? '200' : '100' }} </th>
                </tr>
                <tr>
                    <td>APPLICANT NAME:</td>
                    <th>
                        {{ strtoupper($data->applicant->applicant->last_name . ', ' . $data->applicant->applicant->first_name) }}
                    </th>
                    <td> REMARK:</td>
                    <th>
                        {{ $data->examination_result()[1] ? 'PASSED' : 'FAILED' }}
                    </th>

                </tr>
                {{--   <tr>
                    <td colspan="1"> EXAMINATION CODE:</th>
                    <th colspan="2"> {{ $data->applicant->applicant_examination->examination_code }}</th>
                </tr> --}}
                <tr>

                    <td>EXAMINATION START: </td>
                    <th>{{ $data->applicant->applicant_examination->examination_start }}</th>
                    <td>EXAMINATION END: </td>
                    <th>
                        {{ $data->applicant->applicant_examination->updated_at }}
                    </th>
                </tr>
            </tbody>
        </table>
        @php
            $_count = 0;
            $_width = [0, '50%', 0, '100px', 0];

        @endphp
        @foreach ($_examination_categories as $_key => $_category)
            <div class="category-{{ str_replace(' ', '_', strtolower($_category->category_name)) }}">
                <h4 style="margin: 0px;">{{ $_category->category_name }}</h4>
                <p style="font-size: 10px;margin:0px;">{{ $_category->instruction }}</p>
                <div class="questiones-{{ str_replace(' ', '_', strtolower($_category->category_name)) }}">
                    @foreach ($_category->question as $_question)
                        <p style="font-size: 10px;">
                            @if ($_question->question != 'none')
                                <span> {{ $_count += 1 }}. @php
                                    echo $_question->question;
                                @endphp</span>
                            @else
                                <span> {{ $_count += 1 }}. QUESTION</span>
                                <br>
                                <img src="http://bma.edu.ph/assets/image/questions/{{ $_question->image_path }}"
                                    alt="" style="width:{{ $_width[$_key] }};margin-top:10px; ">
                            @endif
                            <br>
                            @php

                                $_answer = $data->choice_answer($_question->id)->first();
                            @endphp
                        <div style="margin-top: 5px">
                            @foreach ($_question->choices as $_choice)
                                {{--  @if ($_answer->choices_id == $_choice->id)
                                    <label for="" class="label-choice"
                                        style="color:{{ $_choice->is_answer == 1 ? 'green' : 'red' }}">
                                        <input type="radio" {{ $_answer->choices_id == $_choice->id ? 'checked' : '' }}>
                                        @php
                                            echo $_choice->choice_name;
                                        @endphp
                                    </label>
                                @else
                                    <label class="label-choice">
                                        <input type="radio">
                                        @php
                                            echo $_choice->choice_name;
                                        @endphp
                                    </label>
                                @endif --}}
                                <br>
                            @endforeach
                        </div>

                        </p>
                    @endforeach
                </div>
            </div>
            {{-- <div class="page-break"></div> --}}
        @endforeach
    </div>

@endsection
