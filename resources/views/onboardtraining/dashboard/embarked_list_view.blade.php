@extends('layouts.app-main')
@php
$_title = 'Embarked List';
@endphp
@section('page-title', $_title)
@section('beardcrumb-content')

    <li class="breadcrumb-item">
        <a href="{{ route('onboard.dashboard') }}">
            <svg width="14" height="14" class="me-2" viewBox="0 0 22 22" fill="none"
                xmlns="http://www.w3.org/2000/svg">
                <path
                    d="M8.15722 19.7714V16.7047C8.1572 15.9246 8.79312 15.2908 9.58101 15.2856H12.4671C13.2587 15.2856 13.9005 15.9209 13.9005 16.7047V16.7047V19.7809C13.9003 20.4432 14.4343 20.9845 15.103 21H17.0271C18.9451 21 20.5 19.4607 20.5 17.5618V17.5618V8.83784C20.4898 8.09083 20.1355 7.38935 19.538 6.93303L12.9577 1.6853C11.8049 0.771566 10.1662 0.771566 9.01342 1.6853L2.46203 6.94256C1.86226 7.39702 1.50739 8.09967 1.5 8.84736V17.5618C1.5 19.4607 3.05488 21 4.97291 21H6.89696C7.58235 21 8.13797 20.4499 8.13797 19.7714V19.7714"
                    stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"></path>
            </svg>Dashboad</a>
    </li>
    <li class="breadcrumb-item active" aria-current="page">{{ $_title }}</li>
@endsection
@section('page-content')
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between">
                    <div class="header-title">
                        <h4 class="card-title">BS Marine Engineering</h4>
                    </div>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table id="datatable" class="table table-striped" data-toggle="data-table">
                            <thead>
                                <tr>
                                    <th>Midshipman Name</th>
                                    <th>SBT Batch</th>
                                    <th>Sea Experience</th>
                                    <th>Date of Embarked</th>
                                    <th>Shipboard Status</th>
                                    <th>Name of Ship</th>
                                    <th>Company Name</th>
                                </tr>
                            </thead>
                            <tbody>
                                @if (count($_shipboard_monitoring_bsme))
                                    @foreach ($_shipboard_monitoring_bsme as $_data)
                                        <tr>
                                            <td>
                                                <a
                                                    href="{{ route('onboard.midshipman') }}?_midshipman={{ base64_encode($_data->student_id) }}">
                                                    {{ strtoupper($_data->student->last_name . ', ' . $_data->student->first_name) }}
                                                </a>
                                            </td>
                                            <td> {{ strtoupper($_data->student->shipboard_training->sbt_batch) }}
                                            </td>
                                            <td>
                                                {{ strtoupper($_data->student->shipboard_training->shipping_company) }}
                                            </td>
                                            <td>
                                                {{ strtoupper($_data->student->shipboard_training->embarked) }}
                                            </td>
                                            <td>
                                                {{ strtoupper($_data->student->shipboard_training->shipboard_status) }}
                                            </td>
                                            <td>
                                                {{ strtoupper($_data->student->shipboard_training->vessel_name) }}
                                            </td>
                                            <td>
                                                {{ strtoupper($_data->student->shipboard_training->company_name) }}
                                            </td>
                                        </tr>
                                    @endforeach
                                @else
                                    <tr>
                                        <th colspan="6">No Data</th>
                                    </tr>
                                @endif
                            </tbody>
                            <tfoot>
                                <tr>
                                    <th>Midshipman Name</th>
                                    <th>SBT Batch</th>
                                    <th>Sea Experience</th>
                                    <th>Date of Embarked</th>
                                    <th>Shipboard Status</th>
                                    <th>Name of Ship</th>
                                    <th>Company Name</th>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between">
                    <div class="header-title">
                        <h4 class="card-title">BS Marine Transportation</h4>
                    </div>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table id="datatable" class="table table-striped" data-toggle="data-table">
                            <thead>
                                <tr>
                                    <th>Midshipman Name</th>
                                    <th>SBT Batch</th>
                                    <th>Sea Experience</th>
                                    <th>Date of Embarked</th>
                                    <th>Shipboard Status</th>
                                    <th>Name of Ship</th>
                                    <th>Company Name</th>
                                </tr>
                            </thead>
                            <tbody>
                                @if (count($_shipboard_monitoring_bsme))
                                    @foreach ($_shipboard_monitoring_bsmt as $_data)
                                        <tr>
                                            <td>
                                                <a
                                                    href="{{ route('onboard.midshipman') }}?_midshipman={{ base64_encode($_data->student_id) }}">
                                                    {{ strtoupper($_data->student->last_name . ', ' . $_data->student->first_name) }}
                                                </a>
                                            </td>
                                            <td> {{ strtoupper($_data->student->shipboard_training->sbt_batch) }}
                                            </td>
                                            <td>
                                                {{ strtoupper($_data->student->shipboard_training->shipping_company) }}
                                            </td>
                                            <td>
                                                {{ strtoupper($_data->student->shipboard_training->embarked) }}
                                            </td>
                                            <td>
                                                {{ strtoupper($_data->student->shipboard_training->shipboard_status) }}
                                            </td>
                                            <td>
                                                {{ strtoupper($_data->student->shipboard_training->vessel_name) }}
                                            </td>
                                            <td>
                                                {{ strtoupper($_data->student->shipboard_training->company_name) }}
                                            </td>
                                        </tr>
                                    @endforeach
                                @else
                                    <tr>
                                        <th colspan="6">No Data</th>
                                    </tr>
                                @endif
                            </tbody>
                            <tfoot>
                                <tr>
                                    <th>Midshipman Name</th>
                                    <th>SBT Batch</th>
                                    <th>Sea Experience</th>
                                    <th>Date of Embarked</th>
                                    <th>Shipboard Status</th>
                                    <th>Name of Ship</th>
                                    <th>Company Name</th>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection
