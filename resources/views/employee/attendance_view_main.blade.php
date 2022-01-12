@extends('layouts.app-main')
@section('page-title', 'Attendances')
@section('beardcrumb-content')
    <li class="breadcrumb-item active" aria-current="page">
        <svg width="14" height="14" class="me-2" viewBox="0 0 22 22" fill="none"
            xmlns="http://www.w3.org/2000/svg">
            <path
                d="M8.15722 19.7714V16.7047C8.1572 15.9246 8.79312 15.2908 9.58101 15.2856H12.4671C13.2587 15.2856 13.9005 15.9209 13.9005 16.7047V16.7047V19.7809C13.9003 20.4432 14.4343 20.9845 15.103 21H17.0271C18.9451 21 20.5 19.4607 20.5 17.5618V17.5618V8.83784C20.4898 8.09083 20.1355 7.38935 19.538 6.93303L12.9577 1.6853C11.8049 0.771566 10.1662 0.771566 9.01342 1.6853L2.46203 6.94256C1.86226 7.39702 1.50739 8.09967 1.5 8.84736V17.5618C1.5 19.4607 3.05488 21 4.97291 21H6.89696C7.58235 21 8.13797 20.4499 8.13797 19.7714V19.7714"
                stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"></path>
        </svg>Employee Attendance
    </li>
    {{-- <li class="breadcrumb-item">
        <a href="#">
            <svg width="14" height="14" class="me-2" viewBox="0 0 22 22" fill="none"
                xmlns="http://www.w3.org/2000/svg">
                <path
                    d="M8.15722 19.7714V16.7047C8.1572 15.9246 8.79312 15.2908 9.58101 15.2856H12.4671C13.2587 15.2856 13.9005 15.9209 13.9005 16.7047V16.7047V19.7809C13.9003 20.4432 14.4343 20.9845 15.103 21H17.0271C18.9451 21 20.5 19.4607 20.5 17.5618V17.5618V8.83784C20.4898 8.09083 20.1355 7.38935 19.538 6.93303L12.9577 1.6853C11.8049 0.771566 10.1662 0.771566 9.01342 1.6853L2.46203 6.94256C1.86226 7.39702 1.50739 8.09967 1.5 8.84736V17.5618C1.5 19.4607 3.05488 21 4.97291 21H6.89696C7.58235 21 8.13797 20.4499 8.13797 19.7714V19.7714"
                    stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"></path>
            </svg>Home</a>
    </li>
    <li class="breadcrumb-item"><a href="#">Library</a></li>
    <li class="breadcrumb-item active" aria-current="page">Data</li> --}}
@endsection
@section('page-content')
    <div class="card">
        <div class="card-body">
            <form id="form-wizard1" class="text-center mt-3" action="/employee/attendance" method="POST">
                @csrf
                <ul id="top-tab-list" class="p-0 row list-inline">
                    <li class="col-lg-3 col-md-6 text-start mb-2 active" id="account">
                        <a href="javascript:void();">
                            <div class="iq-icon me-3">
                                <svg width="32" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                    <path fill-rule="evenodd" clip-rule="evenodd"
                                        d="M17.294 7.29105C17.294 10.2281 14.9391 12.5831 12 12.5831C9.0619 12.5831 6.70601 10.2281 6.70601 7.29105C6.70601 4.35402 9.0619 2 12 2C14.9391 2 17.294 4.35402 17.294 7.29105ZM12 22C7.66237 22 4 21.295 4 18.575C4 15.8539 7.68538 15.1739 12 15.1739C16.3386 15.1739 20 15.8789 20 18.599C20 21.32 16.3146 22 12 22Z"
                                        fill="currentColor"></path>
                                </svg>
                            </div>
                            <span>STEP 1</span>
                        </a>
                    </li>
                    <li id="personal" class="col-lg-3 col-md-6 mb-2 text-start">
                        <a href="javascript:void();">
                            <div class="iq-icon me-3">
                                <svg width="32" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                    <path fill-rule="evenodd" clip-rule="evenodd"
                                        d="M17.294 7.29105C17.294 10.2281 14.9391 12.5831 12 12.5831C9.0619 12.5831 6.70601 10.2281 6.70601 7.29105C6.70601 4.35402 9.0619 2 12 2C14.9391 2 17.294 4.35402 17.294 7.29105ZM12 22C7.66237 22 4 21.295 4 18.575C4 15.8539 7.68538 15.1739 12 15.1739C16.3386 15.1739 20 15.8789 20 18.599C20 21.32 16.3146 22 12 22Z"
                                        fill="currentColor"></path>
                                </svg>
                            </div>
                            <span>STEP 2</span>
                        </a>
                    </li>
                    <li id="payment" class="col-lg-3 col-md-6 mb-2 text-start">
                        <a href="javascript:void();">
                            <div class="iq-icon me-3">
                                <svg width="32" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                    <path fill-rule="evenodd" clip-rule="evenodd"
                                        d="M17.294 7.29105C17.294 10.2281 14.9391 12.5831 12 12.5831C9.0619 12.5831 6.70601 10.2281 6.70601 7.29105C6.70601 4.35402 9.0619 2 12 2C14.9391 2 17.294 4.35402 17.294 7.29105ZM12 22C7.66237 22 4 21.295 4 18.575C4 15.8539 7.68538 15.1739 12 15.1739C16.3386 15.1739 20 15.8789 20 18.599C20 21.32 16.3146 22 12 22Z"
                                        fill="currentColor"></path>
                                </svg>
                            </div>
                            <span>STEP 3</span>
                        </a>
                    </li>
                    <li id="confirm" class="col-lg-3 col-md-6 mb-2 text-start">
                        <a href="javascript:void();">
                            <div class="iq-icon me-3">
                                <svg width="32" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                    <path fill-rule="evenodd" clip-rule="evenodd"
                                        d="M17.294 7.29105C17.294 10.2281 14.9391 12.5831 12 12.5831C9.0619 12.5831 6.70601 10.2281 6.70601 7.29105C6.70601 4.35402 9.0619 2 12 2C14.9391 2 17.294 4.35402 17.294 7.29105ZM12 22C7.66237 22 4 21.295 4 18.575C4 15.8539 7.68538 15.1739 12 15.1739C16.3386 15.1739 20 15.8789 20 18.599C20 21.32 16.3146 22 12 22Z"
                                        fill="currentColor"></path>
                                </svg>
                            </div>
                            <span>STEP 4</span>
                        </a>
                    </li>
                </ul>
                <!-- fieldsets -->
                <fieldset>
                    <div class="form-card text-start">
                        <div class="row">
                            <div class="col-12">
                                <h3 class="">TEMPERATURE</h3>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="form-label"><small><b>FULL NAME</b></small></label>
                                    <span class="form-control full-name">{{ Auth::user()->name }}</span>
                                    <input type="hidden" name="employee" class="employee"
                                        value="{{ Auth::user()->email }}">
                                    @error('employee')
                                        <small class="text-danger h6"><b>
                                                {{ $message }}</b></small>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="form-label">BODY TEMPERATURE</label>
                                    <input type="text" class="form-control" name="body_temp"
                                        value="{{ old('body_temp') }}">
                                    @error('body_temp')
                                        <small class="text-danger h6"><b>
                                                {{ $message }}</b></small>
                                    @enderror
                                </div>
                            </div>
                        </div>
                    </div>
                    <button type="button" name="next" class="btn btn-primary next action-button float-end"
                        value="Next">Next</button>
                </fieldset>
                <fieldset>
                    <div class="form-card text-start">
                        <div class="row">
                            <div class="col-12">
                                <h3 class="mb-4">SYMPTOMS</h3>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="form-label"><small><b>DO YOU HAVE ANY OF THE
                                                FOLLOWING?</b></small></label>
                                    @php
                                        $_datas = ['Cough', 'Cold', 'Diarrhea', 'Sore Throat', 'Body Aches', 'Headaches', 'Loss of Smell', 'Difficulty in Breathing', 'None of the Above'];
                                    @endphp
                                    <div class="form-group clearfix">
                                        @foreach ($_datas as $_key => $_data)
                                            @php
                                                $_status = '';
                                                if (old('question1')) {
                                                    foreach (old('question1') as $ans) {
                                                        if ($_data == $ans) {
                                                            $_status = 'checked';
                                                        }
                                                    }
                                                }
                                                
                                            @endphp
                                            <div class="form-check d-block">
                                                <input class="form-check-input" type="checkbox"
                                                    id="flexCheckChecked-{{ $_key }}" name="question1[]"
                                                    value="{{ $_data }}" {{ $_status }}>
                                                <label class="form-check-label" for="flexCheckChecked-{{ $_key }}">
                                                    {{ $_data }}
                                                </label>
                                            </div>
                                        @endforeach

                                    </div>
                                    @error('question1')
                                        <small class="text-danger h6"><b>
                                                {{ $message }}</b></small>
                                    @enderror
                                </div>
                            </div>
                        </div>
                    </div>
                    <button type="button" name="next" class="btn btn-primary next action-button float-end"
                        value="Next">Next</button>
                    <button type="button" name="previous"
                        class="btn btn-dark previous action-button-previous float-end me-1"
                        value="Previous">Previous</button>
                </fieldset>
                <fieldset>
                    <div class="form-card text-start">
                        <div class="row">
                            <div class="col-12">
                                <h3 class="mb-4">EXPOSURE</h3>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="form-label">
                                <small><b>DO YOU EXPERIENCE ANY OF THE
                                        FOLLOWING?</b></small>
                            </label>
                            @php
                                $_datas = ['Travelled during the last 15 days', 'Direct Contact with a COVID-19 Patient', 'Direct Contact with PUI / PUM', 'None of the Above'];
                            @endphp
                            <div class="form-group clearfix">
                                @foreach ($_datas as $_key => $_data)
                                    @php
                                        $_status = '';
                                        if (old('question2')) {
                                            foreach (old('question2') as $ans) {
                                                if ($_data == $ans) {
                                                    $_status = 'checked';
                                                }
                                            }
                                        }
                                        
                                    @endphp
                                    <div class="form-check d-block">
                                        <input class="form-check-input" type="checkbox"
                                            id="flexCheckChecked-2-{{ $_key }}" name="question2[]"
                                            value="{{ $_data }}" {{ $_status }}>
                                        <label class="form-check-label" for="flexCheckChecked-2-{{ $_key }}">
                                            {{ $_data }}
                                        </label>
                                    </div>
                                @endforeach

                            </div>
                            @error('question2')
                                <small class="text-danger h6"> <b>{{ $message }}</b> </small>
                            @enderror
                        </div>
                    </div>
                    <button type="button" name="next" class="btn btn-primary next action-button float-end"
                        value="Next">Next</button>
                    <button type="button" name="previous"
                        class="btn btn-dark previous action-button-previous float-end me-1"
                        value="Previous">Previous</button>
                </fieldset>
                <fieldset>
                    <div class="form-card text-start">
                        <div class="row">
                            <div class="col-12">
                                <h3 class="mb-4">COVID</h3>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="form-label">
                                <small><b>Have you tested
                                        COVID-19
                                        positive in the last
                                        15 days?</b></small>
                            </label>
                            @php
                                $_datas = ['YES', 'NO'];
                            @endphp
                            <div class="form-group clearfix">
                                @foreach ($_datas as $_key => $_data)
                                    @php
                                        $_status = '';
                                        if (old('question3')) {
                                            foreach (old('question3') as $ans) {
                                                if ($_data == $ans) {
                                                    $_status = 'checked';
                                                }
                                            }
                                        }
                                        
                                    @endphp
                                    <div class="form-check d-block">
                                        <input class="form-check-input" type="checkbox"
                                            id="flexCheckChecked-3-{{ $_key }}" name="question3[]"
                                            value="{{ $_data }}" {{ $_status }}>
                                        <label class="form-check-label" for="flexCheckChecked-3-{{ $_key }}">
                                            {{ $_data }}
                                        </label>
                                    </div>
                                @endforeach

                            </div>
                            @error('question2')
                                <small class="text-danger h6"> <b>{{ $message }}</b> </small>
                            @enderror
                        </div>
                    </div>
                    <button type="submit" name="next" class="btn btn-primary next action-button float-end"
                        value="Submit">Submit</button>
                    <button type="button" name="previous"
                        class="btn btn-dark previous action-button-previous float-end me-1"
                        value="Previous">Previous</button>
                </fieldset>
            </form>
        </div>
    </div>
@endsection
