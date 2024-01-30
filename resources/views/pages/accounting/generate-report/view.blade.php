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
                                                    <select name="collection_type"
                                                        class="form-select form-select-sm border border-primary">
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
                                                    <select name="collection_academic"
                                                        class="form-select form-select-sm border border-primary">
                                                        @foreach ($_academic as $academic)
                                                            <option value="{{ base64_encode($academic->id) }}">
                                                                {{ $academic->semester }} | {{ $academic->school_year }}
                                                            </option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label for="">Course</label>
                                                    <select name="balance_course"
                                                        class="form-select form-select-sm border border-primary">
                                                        <option value="1">BSME</option>
                                                        <option value="2">BSMT</option>
                                                        <option value="3">PBM SPECIALIZATION</option>
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label for="">Year Level</label>
                                                    <select name="balance_level" id=""
                                                        class="form-select form-select-sm border border-primary">
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
                            <tr>
                                <td>
                                    <b>STUDENT MONTHLY PAYMENT REPORT</b>
                                    <ul>
                                        <li>You can generate Student Balance Report by Course Category</li>
                                    </ul>
                                </td>
                                <td>
                                    <form action="{{ route('accounting.monthly-payment-report') }}" method="post"
                                        target="_blank">
                                        @csrf
                                        <div class="row">
                                            <div class="col-md-12">
                                                <div class="form-group">
                                                    <label for="">Academic Year</label>
                                                    <select name="_academic"
                                                        class="form-select form-select-sm border border-primary">
                                                        @foreach ($_academic as $academic)
                                                            <option value="{{ base64_encode($academic->id) }}">
                                                                {{ $academic->semester }} | {{ $academic->school_year }}
                                                            </option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label for="">Course</label>
                                                    <select name="balance_course"
                                                        class="form-select form-select-sm border border-primary">
                                                        <option value="1">BSME</option>
                                                        <option value="2">BSMT</option>
                                                        <option value="3">PBM SPECIALIZATION</option>
                                                    </select>
                                                </div>
                                            </div>


                                        </div>
                                        <div class="form-group">
                                            <button class="btn btn-outline-primary btn-sm w-100">GENERATE</button>
                                        </div>
                                    </form>
                                </td>

                            </tr>
                            <tr>
                                <td>
                                    <b>STUDENT TEST PERMIT</b>
                                </td>
                                <td>
                                    <form action="{{ route('accounting.test-permit') }}" method="get">
                                        @csrf
                                        <div class="row">
                                            <div class="col-md-12">
                                                <div class="form-group">
                                                    <label for="" class="fw-bolder text-muted">Examination
                                                        Term</label>
                                                    <select name="term"
                                                        class="form-select form-select-sm border border-primary form-select form-select-sm border border-primary-sm">
                                                        <option value="midterm">Midterm Examination Permit</option>
                                                        <option value="finals">Finals Examination Permit</option>
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="col-md-12">
                                                <div class="form-group">
                                                    <label for="" class="fw-bolder text-muted">Course</label>
                                                    <select name="course"
                                                        class="form-select form-select-sm border border-primary form-select form-select-sm border border-primary-sm">
                                                        <option value="1">BSME</option>
                                                        <option value="2">BSMT</option>
                                                        <option value="3">PBM SPECIALIZATION</option>
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label for="" class="fw-bolder text-muted">Academic Year</label>
                                                <select name="academic"
                                                    class="form-select form-select-sm border border-primary form-select form-select-sm border border-primary-sm">
                                                    @foreach ($_academic as $academic)
                                                        <option value="{{ base64_encode($academic->id) }}">
                                                            {{ $academic->semester }} | {{ $academic->school_year }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                            </div>

                                        </div>
                                        <div class="form-group">
                                            <button class="btn btn-outline-primary btn-sm w-100">GENERATE</button>
                                        </div>
                                    </form>
                                </td>

                            </tr>
                            <tr>
                                <td>
                                    <b>STUDENT ACCOUNT CARD</b>
                                    <ul>
                                        <li>You can generate Student Account Card by Section</li>
                                    </ul>
                                </td>
                                <td>
                                    <form action="{{ route('accounting.student-account-card-section') }}" method="post">
                                        @csrf
                                        <div class="row">
                                            <div class="col-md-12">
                                                <div class="form-group">
                                                    <label for="">Academic Year</label>
                                                    <select name="academic"
                                                        class="form-select form-select-sm border border-primary">
                                                        @foreach ($_academic as $academic)
                                                            <option value="{{ base64_encode($academic->id) }}">
                                                                {{ $academic->semester }} | {{ $academic->school_year }}
                                                            </option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label for="">Course</label>
                                                    <select name="course"
                                                        class="form-select form-select-sm border border-primary">
                                                        <option value="1">BSME</option>
                                                        <option value="2">BSMT</option>
                                                        <option value="3">PBM SPECIALIZATION</option>
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label for="">Year Level</label>
                                                    <select name="level" id=""
                                                        class="form-select form-select-sm border border-primary">
                                                        <option value="all">All</option>
                                                        <option value="GRADE 11">Grade 11</option>
                                                        <option value="GRADE 12">Grade 12</option>
                                                        <option value="1/C"> 1ST CLASS</option>
                                                        <option value="2/C"> 2ND CLASS</option>
                                                        <option value="3/C"> 3RD CLASS</option>
                                                        <option value="4/C"> 4TH CLASS</option>


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
                            <tr>
                                <td>
                                    <b>EMPLOYEE ATTENANCE REPORT</b>
                                    <ul>
                                        <li>You can generate the Employee DTR</li>
                                    </ul>
                                </td>
                                <td>
                                    <form action="{{ route('accounting.employee-attendance') }}" method="get"
                                        target="_blank">

                                        <div class="row">
                                            <div class="form-group col-md-6">
                                                <small class="fw-bolder text-muted">START DATE</small>
                                                <input type="date" class="form-control" name="start_date">
                                                @error('start_date')
                                                    <span class="badge bg-danger">{{ $message }}</span>
                                                @enderror
                                            </div>
                                            <div class="form-group col-md-6">
                                                <small class="fw-bolder text-muted">END DATE</small>
                                                <input type="date" class="form-control" name="end_date">
                                                @error('end_date')
                                                    <span class="badge bg-danger">{{ $message }}</span>
                                                @enderror
                                            </div>
                                            <input type="hidden" name="r_view" value="weekly">
                                        </div>
                                        <div class="form-group">
                                            <button class="btn btn-outline-primary btn-sm w-100">
                                                GENERATE</button>
                                        </div>
                                    </form>
                                </td>
                            </tr>
                            {{--  <tr>
                                <td>Import Transaction</td>
                                <td>
                                    <form action="{{ route('accounting.student-transacion-import') }}" method="post"
                                        enctype="multipart/form-data">
                                        @csrf
                                        <div class="row">
                                            <div class="col-md-12">
                                                <div class="form-group">
                                                    <label for="">Attach File</label>
                                                    <input type="file" name="upload-file" id=""
                                                        class="form-control">
                                                </div>
                                            </div>
                                            <div class="col-md-12">
                                                <div class="form-group">
                                                    <button class="btn btn-info btn-sm text-white w-100">UPLOAD </button>
                                                </div>
                                            </div>
                                        </div>
                                    </form>
                                </td>
                            </tr> --}}
                        </tbody>
                    </table>
                </div>

            </div>
        </div>
    </div>
@endsection
