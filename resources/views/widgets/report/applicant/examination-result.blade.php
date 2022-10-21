@extends('widgets.report.app_report_template_v2')
@section('title-report', 'EXAMINATION EXAMINATION - ' . $_data->applicant_number)
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
    <div class="content">
        <h3 style="text-align: center"><b>EXAMINATION EXAMINATION</b></h3>
        <table class="table-content" style="margin-bottom: 20px;">
            <tbody>
                <tr>
                    <th style="width: 25%">APPLICANT NO:</th>
                    <td style="width: 50%">
                        {{ strtoupper($_data->applicant_number) }}
                    </td>
                    <th style="width: 25%"> SCORE:</th>
                    <td style="text-align: left"> {{ $_data->applicant_examination->examination_result()[1] }} / 200 </td>

                </tr>
                <tr>
                    <th style="width: 25%">APPLICANT NAME:</th>
                    <td style="width: 50%">
                        {{ strtoupper($_data->applicant->last_name . ', ' . $_data->applicant->first_name) }}
                    </td>
                    <th style="width: 25%"> REMARK:</th>
                    <td style="width: 28%">

                    </td>

                </tr>
                {{-- <tr>
                    <th style="width: 25%"> EXAMINATION CODE:</th>
                    <td style="text-align: left"> {{ $_data->applicant_examination->examination_code }}</td>
                    <th>EXAMINATION START: {{ $_data->applicant_examination->examination_start }}</th>
                    <th>EXAMINATION END: {{ $_data->applicant_examination->updated_at }}</th>
                </tr> --}}
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
                                <span> {{ $_count += 1 }}. {{ $_question->question }}</span>
                            @else
                                <span> {{ $_count += 1 }}. QUESTION</span>
                                <br>
                                <img src="http://20.0.0.120:90/assets/image/questions/{{ $_question->image_path }}"
                                    alt="" style="width:{{ $_width[$_key] }};margin-top:10px; ">
                            @endif
                            <br>
                            @php
                                
                                $_answer = $_data->applicant_examination->choice_answer($_question->id)->first();
                            @endphp
                        <div style="margin-top: 5px">
                            @foreach ($_question->choices as $_choice)
                                @if ($_answer->choices_id == $_choice->id)
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
                                @endif
                                <br>
                            @endforeach
                        </div>

                        </p>
                    @endforeach
                </div>
            </div>
            {{-- <div class="page-break"></div> --}}
        @endforeach

        {{--  <table class="table-content table-outline" style="margin-top: 20px; ">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>EXAMINATION ID</th>
                    <th>QUESTION ID</th>
                    <th>CHOICES ID</th>
                    <th>IS_REMOVED</th>
                    <th>CREATED_AT</th>
                    <th>UPDATED_AT</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($_data->applicant_examination->examination_questioner as $item)
                    <tr>
                        <td>{{ $item->id }}</td>
                        <td>{{ $item->examination_id }}</td>
                        <td>{{ $item->question_id }}</td>
                        <td>{{ $item->choices_id }}</td>
                        <td>{{ $item->is_removed }}</td>
                        <td>{{ $item->created_at }}</td>
                        <td>{{ $item->updated_at }}</td>
                    </tr>
                @endforeach

            </tbody>
        </table> --}}
    </div>

@endsection
