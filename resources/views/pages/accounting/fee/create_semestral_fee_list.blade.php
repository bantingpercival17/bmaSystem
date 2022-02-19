@extends('layouts.app-main')
@php
$_title = 'Tuition Fee Amount';
@endphp
@section('page-title', $_title)
@section('beardcrumb-content')
    <li class="breadcrumb-item active" aria-current="page">
        <a href="{{ route('accounting.fees') }}">
            <svg width="14" height="14" class="me-2" viewBox="0 0 22 22" fill="none"
                xmlns="http://www.w3.org/2000/svg">
                <path
                    d="M8.15722 19.7714V16.7047C8.1572 15.9246 8.79312 15.2908 9.58101 15.2856H12.4671C13.2587 15.2856 13.9005 15.9209 13.9005 16.7047V16.7047V19.7809C13.9003 20.4432 14.4343 20.9845 15.103 21H17.0271C18.9451 21 20.5 19.4607 20.5 17.5618V17.5618V8.83784C20.4898 8.09083 20.1355 7.38935 19.538 6.93303L12.9577 1.6853C11.8049 0.771566 10.1662 0.771566 9.01342 1.6853L2.46203 6.94256C1.86226 7.39702 1.50739 8.09967 1.5 8.84736V17.5618C1.5 19.4607 3.05488 21 4.97291 21H6.89696C7.58235 21 8.13797 20.4499 8.13797 19.7714V19.7714"
                    stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"></path>
            </svg>Course Fee
        </a>

    </li>
@endsection
@section('page-content')
    <div class="row">

        <div class="card">
            <div class="card-header d-flex justify-content-between">
                <div class="header-title">
                    <h4 class="card-title">{{ $_course_fee->year_level }} / C</h4>
                    <small class="fw-bolder">Create Semestral Fees</small>
                </div>
                <div class="card-tool">
                    <small class="text-info fw-bolder">TOTAL TUITION FEES</small>
                    <br>
                    <label for=""
                        class="h4 fw-bolder text-primary">{{ $_course_fee->total_tuition_fee($_course_fee) ? number_format($_course_fee->total_tuition_fee($_course_fee),2) : '0.00'}}</label>
                </div>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-striped" {{-- id="datatable" data-toggle="data-table" --}}>
                        <thead>
                            <tr>
                                <th>Particular Name</th>
                                <th>Amount</th>
                                <th>Change Fees</th>
                            </tr>
                        </thead>
                        <tbody>
                            @if (count($_course_fee->semestral_fee_list) > 0)
                                @foreach ($_course_fee->semestral_fee_list as $_key => $item)
                                    <tr>
                                        <td>{{ $item->particular_fee->particular->particular_name }}</td>
                                        <td>{{ number_format($item->particular_fee->particular_amount, 2) }}
                                            @if ($_course_fee->course_id != 3)
                                                @if ($item->particular_fee->particular->particular_name == 'Tuition Fee')
                                                    * {{ $_course_fee->course->units($_course_fee)->units }} units =
                                                    {{ number_format($item->particular_fee->particular_amount * $_course_fee->course->units($_course_fee)->units,2) }}
                                                @endif
                                            @endif
                                        </td>
                                        <td>
                                            <form action="{{ route('accounting.course-change-fee') }}" method="post">
                                                @csrf
                                                <div class="row">
                                                    <div class="col-md-7">
                                                        <input type="hidden" name="_semestral_fee"
                                                            value="{{ base64_encode($item->id) }}">
                                                        <select name="_amount" id="" class="form-select">
                                                            @if (count($item->particular_fee->particular->particular_fee) > 0)
                                                                @foreach ($item->particular_fee->particular->particular_fee as $_fee)
                                                                    <option value="{{ $_fee->id }}">
                                                                        {{ $_fee->particular_amount }}
                                                                    </option>
                                                                @endforeach

                                                            @else
                                                                <option value="">No Fees</option>
                                                            @endif
                                                        </select>
                                                    </div>
                                                    <div class="col-md-2">
                                                        <button type="submit" class="btn btn-primary btn-sm">Change</button>
                                                    </div>
                                                </div>
                                            </form>
                                        </td>
                                    </tr>
                                @endforeach
                            @endif
                        </tbody>
                    </table>
                </div>

            </div>
        </div>

    </div>
@endsection
