@extends('layouts.app-main')
@php
$_title = 'Generate Report';
@endphp
@section('page-title', $_title)
@section('beardcrumb-content')
    <li class="breadcrumb-item active" aria-current="page">
        <svg width="14" height="14" class="me-2" viewBox="0 0 22 22" fill="none" xmlns="http://www.w3.org/2000/svg">
            <path
                d="M8.15722 19.7714V16.7047C8.1572 15.9246 8.79312 15.2908 9.58101 15.2856H12.4671C13.2587 15.2856 13.9005 15.9209 13.9005 16.7047V16.7047V19.7809C13.9003 20.4432 14.4343 20.9845 15.103 21H17.0271C18.9451 21 20.5 19.4607 20.5 17.5618V17.5618V8.83784C20.4898 8.09083 20.1355 7.38935 19.538 6.93303L12.9577 1.6853C11.8049 0.771566 10.1662 0.771566 9.01342 1.6853L2.46203 6.94256C1.86226 7.39702 1.50739 8.09967 1.5 8.84736V17.5618C1.5 19.4607 3.05488 21 4.97291 21H6.89696C7.58235 21 8.13797 20.4499 8.13797 19.7714V19.7714"
                stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"></path>
        </svg>{{ $_title }}
    </li>
@endsection
@section('page-content')
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">GENERATE REPORTS</h3>
                </div>
                <div class="card-body ">
                    <table class="table">
                        <tbody>
                            <tr>
                                <td>
                                    <b> COLLECTION REPORT</b>
                                    <ul>
                                        <li> You can generate Monthly and Daily Reports</li>
                                        <li> In Monthly just select the Monthly Category and the Date </li>
                                        <li> In Daily just select the Daily Category and the Complete Date</li>
                                    </ul>
                                </td>
                                <td>
                                    <form action="{{ route('accounting.report-collection') }}" method="post">
                                        @csrf
                                        <div class="row">

                                            <div class="col-md">
                                                <div class="form-group">
                                                    <label class="text-label">Category</label>
                                                    <select name="collection_type" class="form-control">
                                                        <option value="daily">Daily</option>
                                                        <option value="monthly">Monthly</option>
                                                    </select>
                                                    @error('collection_type')
                                                        <span class="mt-2 badge bg-danger">{{ $message }}</span>
                                                    @enderror
                                                </div>
                                            </div>
                                            <div class="col-md">
                                                <div class="form-group">
                                                    <label class="text-label">Year</label>
                                                    <input class="form-control" type="date" name="collection_date"
                                                        id="">
                                                    @error('collection_date')
                                                        <span class="mt-2 badge bg-danger">{{ $message }}</span>
                                                    @enderror
                                                </div>
                                            </div>
                                        </div>
                                        <button class="btn btn-outline-primary btn-block w-100 btn-sm" type="submit">
                                            DOWNLOAD</button>
                                    </form>
                                </td>

                            </tr>
                            <tr>
                                <td>
                                    <b>STUDENT BALANCE REPORT</b>
                                    <ul>
                                        <li>You can generate Student Balance Report by Course Category</li>
                                    </ul>
                                </td>
                                <td>
                                    <form action="{{ route('accounting.report-balance') }}" method="post">
                                        @csrf
                                        <div class="row">
                                            <div class="col-md-12">
                                                <div class="form-group">
                                                    <label for="">Academic Year</label>
                                                    <select name="_academic" class="form-control">
                                                        <option value="{{ base64_encode(4) }}">2nd Semester | 2021 - 2022
                                                        </option>
                                                        <option value="{{ base64_encode(3) }}">1st Semester | 2021 - 2022
                                                        </option>
                                                        <option value="{{ base64_encode(2) }}">2nd Semester | 2020 - 2021
                                                        </option>

                                                    </select>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label for="">Course</label>
                                                    <select name="balance_course" class="form-control">
                                                        <option value="1">BSME</option>
                                                        <option value="2">BSMT</option>
                                                        <option value="3">PBM SPECIALIZATION</option>
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label for="">Year Level</label>
                                                    <select name="balance_level" id="" class="form-control">
                                                        <option value="all">All</option>
                                                        <option value="11">Grade 11</option>
                                                        <option value="12">Grade 12</option>
                                                        <option value="1st"> 1ST CLASS</option>
                                                        <option value="2nd"> 2ND CLASS</option>
                                                        <option value="3rd"> 3RD CLASS</option>
                                                        <option value="4th"> 4TH CLASS</option>


                                                    </select>
                                                </div>
                                            </div>

                                        </div>
                                        <div class="form-group">
                                            <button class="btn btn-outline-primary btn-sm w-100"> DOWNLOAD </button>
                                        </div>
                                    </form>
                                </td>
                            </tr>

                        </tbody>
                    </table>
                </div>

            </div>
        </div>
    </div>
@endsection
