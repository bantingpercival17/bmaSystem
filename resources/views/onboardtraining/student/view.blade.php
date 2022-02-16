@extends('layouts.app-main')
@php
$_title = 'Midshipman';
@endphp
@section('page-title', 'Midshipman')
@section('beardcrumb-content')
    <li class="breadcrumb-item active" aria-current="page">
        <svg width="14" height="14" class="me-2" viewBox="0 0 22 22" fill="none"
            xmlns="http://www.w3.org/2000/svg">
            <path
                d="M8.15722 19.7714V16.7047C8.1572 15.9246 8.79312 15.2908 9.58101 15.2856H12.4671C13.2587 15.2856 13.9005 15.9209 13.9005 16.7047V16.7047V19.7809C13.9003 20.4432 14.4343 20.9845 15.103 21H17.0271C18.9451 21 20.5 19.4607 20.5 17.5618V17.5618V8.83784C20.4898 8.09083 20.1355 7.38935 19.538 6.93303L12.9577 1.6853C11.8049 0.771566 10.1662 0.771566 9.01342 1.6853L2.46203 6.94256C1.86226 7.39702 1.50739 8.09967 1.5 8.84736V17.5618C1.5 19.4607 3.05488 21 4.97291 21H6.89696C7.58235 21 8.13797 20.4499 8.13797 19.7714V19.7714"
                stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"></path>
        </svg>{{ $_title }}
    </li>
@endsection
@section('page-content')
    <div class="row">
        <div class="col-md-8">
            <div class="card mb-2">
                <div class="row no-gutters">
                    <div class="col-md-6 col-lg-4">

                        <img src="{{ $_midshipman ? $_midshipman->profile_pic($_midshipman->account) : 'http://bma.edu.ph/img/student-picture/midship-man.jpg' }}"
                            class="card-img" alt="#">
                    </div>
                    <div class="col-md-6 col-lg-8">
                        <div class="card-body">
                            <h4 class="card-title text-primary">
                                <b>{{ $_midshipman ? strtoupper($_midshipman->last_name . ', ' . $_midshipman->first_name) : 'MIDSHIPMAN NAME' }}</b>
                            </h4>
                            <p class="card-text">
                                <span>STUDENT NUMBER: <b>
                                        {{ $_midshipman ? ($_midshipman->account ? $_midshipman->account->student_number : '-') : '-' }}</b></span>
                                <br>
                                <span>COURE: <b>
                                        {{ $_midshipman ? $_midshipman->enrollment_assessment->course->course_name : '-' }}</b></span>

                            </p>
                        </div>
                    </div>
                </div>
            </div>

            @if ($_midshipman)
                <div class="mt-6">
                    <div class="card">
                        <div class="card-header">
                            <p class="card-title text-primary"><b>SHIPBOARD TRAINING</b></p>
                        </div>
                        <div class="card-body">
                            @if ($_midshipman->shipboard_training)
                                @if (request()->input('edit') == true)
                                    <form action="{{ route('onboard.onboard-info-update') }}" method="post">
                                        @csrf
                                        <input type="hidden" name="_student_id" value="{{ $_midshipman->id }}">
                                        <div class="row">
                                            <div class="form-group col-md">
                                                <label class="form-label-sm"><small>COMPANY NAME</small></label>
                                                <input type="text" class="form-control" name="company_name"
                                                    value="{{ old('company_name') ?: $_midshipman->shipboard_training->company_name }}">
                                                @error('company_name')
                                                    <span class="invalid-feedback text-danger" role="alert">
                                                        <small> <b>{{ $message }}</b> </small>
                                                    </span>
                                                @enderror
                                            </div>
                                            <div class="form-group col-md">
                                                <label class="form-label-sm"><small>VESSEL NAME</small></label>
                                                <input type="text" class="form-control" name="_ship_name"
                                                    value="{{ old('_ship_name') ?: $_midshipman->shipboard_training->vessel_name }}">
                                                @error('_ship_name')
                                                    <span class="invalid-feedback text-danger" role="alert">
                                                        <small> <b>{{ $message }}</b> </small>
                                                    </span>
                                                @enderror
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="form-group col-md-5">
                                                <label class="form-label-sm"><small>VESSEL TYPE</small></label>
                                                @php
                                                    $_select = ['CONTAINER VESSEL', 'GENERAL CARGO', 'TANKER'];
                                                @endphp
                                                <select name="_type_vessel" class="form-select"
                                                    value="{{ old('_type_vessel') }}">
                                                    @foreach ($_select as $item)
                                                        <option value="{{ $item }}"
                                                            {{ old('_type_vessel') ? (old('_type_vessel') == $item ? 'selected' : '') : ($_midshipman->shipboard_training->vessel_type == $item ? 'selected' : '') }}>
                                                            {{ $item }}</option>
                                                    @endforeach
                                                </select>
                                                @error('_type_vessel')
                                                    <div class="text-danger">{{ $message }}</div>
                                                @enderror
                                            </div>
                                            <div class="form-group col-md">
                                                <label class="form-label-sm"><small>SEA EXPERIENCE</small></label>
                                                <select name="_ship" id="" class="form-select">
                                                    <option value="foreign"
                                                        {{ old('_ship') ? (old('_ship') == 'foreign' ? 'selected' : '') : ($_midshipman->shipboard_training->shipping_company == 'foreign' ? 'selected' : '') }}>
                                                        Foreign Ship</option>
                                                    <option value="domestic"
                                                        {{ old('_ship') ? (old('_ship') == 'domestic' ? 'selected' : '') : ($_midshipman->shipboard_training->shipping_company == 'domestic' ? 'selected' : '') }}>
                                                        Domestic Ship</option>
                                                </select>
                                            </div>
                                            <div class="form-group col-md">
                                                <label class="form-label-sm"><small>ONBOARD STATUS</small></label>
                                                {{$_midshipman->shipboard_training->shipping_status }}
                                                <select name="_shipboard_status" id="" class="form-select">
                                                    <option value="complete"
                                                        {{ old('_shipboard_status') ? (old('_shipboard_status') == 'complete' ? 'selected' : '') : ($_midshipman->shipboard_training->shipboard_status == 'complete' ? 'selected' : '') }}>
                                                        COMPLETE</option>
                                                    <option value="incomplete"
                                                        {{ old('_shipboard_status') ? (old('_shipboard_status') == 'incomplete' ? 'selected' : '') : ($_midshipman->shipboard_training->shipboard_status == 'incomplete' ? 'selected' : '') }}>
                                                        INCOMPLETE</option>
                                                    <option value="on going"
                                                        {{ old('_shipboard_status') ? (old('_shipboard_status') == 'on going' ? 'selected' : '') : ($_midshipman->shipboard_training->shipboard_status == 'on going' ? 'selected' : '') }}>
                                                        ON GOING</option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="form-group col-md">
                                                <label class="form-label-sm"><small>OBT BATCH</small></label>
                                                <input type="text" class="form-control" name="_sbt_batch" 
                                                    value="{{ old('_sbt_batch') ? old('_sbt_batch') : $_midshipman->shipboard_training->sbt_batch }}">
                                                @error('_sbt_batch')
                                                    <span class="invalid-feedback text-danger" role="alert">
                                                        <small> <b>{{ $message }}</b> </small>
                                                    </span>
                                                @enderror
                                            </div>
                                            <div class="form-group col-md">
                                                <label class="form-label-sm"><small>DATE OF EMBARKED</small></label>
                                                <input class="form-control" type="date" name="_embarked" max="2029-12-31"
                                                    min="2000-12-21"
                                                    value="{{ $_midshipman->shipboard_training->embarked }}">
                                                @error('_embarked')
                                                    <div class="text-danger">{{ $message }}</div>
                                                @enderror
                                            </div>
                                            <div class="form-group col-md">
                                                <label class="form-label-sm"><small>DATE OF DISEMBARKED</small></label>
                                                <input class="form-control" type="date" name="_disemabarke"
                                                    value="{{ old('_disemabarke') ? old('_disemabarke') : $_midshipman->shipboard_training->disembarked }}" max="2029-12-31" min="2000-12-21">
                                                @error('_disemabarke')
                                                    <div class="text-danger">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>

                                        <button class="btn btn-primary w-100">SUBMIT</button>
                                    </form>
                                @else
                                    <form>
                                        <div class="row">
                                            <div class="form-group col-md">
                                                <label class="form-label-sm"><small>OBT BATCH</small></label>
                                                <br>
                                                <label
                                                    class=" text-primary"><b>{{ $_midshipman->shipboard_training->sbt_batch }}</b></label>
                                            </div>
                                            <div class="form-group col-md">
                                                <label class="form-label-sm"><small>SEA EXPERIENCE</small></label>
                                                <br>
                                                <label
                                                    class="text-primary"><b>{{ strtoupper($_midshipman->shipboard_training->shipping_company) }}</b></label>
                                            </div>
                                            <div class="form-group col-md">
                                                <label class="form-label-sm"><small>STATUS</small></label>
                                                <br>
                                                <label class="text-primary">
                                                    <b> {{ strtoupper($_midshipman->shipboard_training->shipboard_status) }}
                                                    </b>
                                                </label>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="form-group col-md">
                                                <label class="form-label-sm"><small>COMPANY NAME</small></label>
                                                <br>
                                                <label
                                                    class="text-primary"><b>{{ $_midshipman->shipboard_training->company_name }}</b></label>
                                            </div>
                                            <div class="form-group col-md">
                                                <label class="form-label-sm"><small>VESSEL NAME</small></label>
                                                <br>
                                                <label class="text-primary">
                                                    <b>{{ $_midshipman->shipboard_training->vessel_name }}</b>
                                                </label>
                                            </div>
                                        </div>

                                        <div class="row">

                                            <div class="form-group col-md">
                                                <label class="form-label-sm"><small>VESSEL TYPE</small></label>
                                                <br>
                                                <label
                                                    class="text-primary"><b>{{ $_midshipman->shipboard_training->vessel_type }}</b></label>
                                            </div>
                                            <div class="form-group col-md">
                                                <label class="form-label-sm"><small>DATE OF EMBARKED</small></label>
                                                <br>
                                                <label
                                                    class="text-primary"><b>{{ $_midshipman->shipboard_training->embarked }}</b></label>
                                            </div>
                                        </div>
                                        <div class="row">
                                            @if ($_midshipman->shipboard_training->shipboard_status != 'on going')
                                                <div class="form-group col-md">
                                                    <label class="form-label-sm"><small>DATE OF
                                                            DISEMBARKED</small></label>
                                                    <br>
                                                    <label
                                                        class="text-primary"><b>{{ $_midshipman->shipboard_training->disembarked }}</b></label>
                                                </div>
                                            @endif

                                        </div>
                                        <a href="{{ route('onboard.midshipman') }}?_midshipman={{ request()->input('_midshipman') }}&edit=true"
                                            class="btn btn-info text-white w-100">UPDATE DETAILS</a>
                                    </form>
                                @endif

                            @else
                                <form action="{{ route('onboard.onboard-info-store') }}" method="post">
                                    @csrf
                                    <input type="hidden" name="_student_id" value="{{ $_midshipman->id }}">
                                    <div class="row">
                                        <div class="form-group col-md">
                                            <label class="form-label-sm"><small>COMPANY NAME</small></label>
                                            <input type="text" class="form-control" name="company_name"
                                                value="{{ old('company_name') }}">
                                            @error('company_name')
                                                <span class="invalid-feedback text-danger" role="alert">
                                                    <small> <b>{{ $message }}</b> </small>
                                                </span>
                                            @enderror
                                        </div>
                                        <div class="form-group col-md">
                                            <label class="form-label-sm"><small>VESSEL NAME</small></label>
                                            <input type="text" class="form-control" name="_ship_name"
                                                value="{{ old('_ship_name') }}">
                                            @error('_ship_name')
                                                <span class="invalid-feedback text-danger" role="alert">
                                                    <small> <b>{{ $message }}</b> </small>
                                                </span>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="form-group col-md-5">
                                            <label class="form-label-sm"><small>VESSEL TYPE</small></label>
                                            @php
                                                $_select = ['CONTAINER VESSEL', 'GENERAL CARGO', 'TANKER'];
                                            @endphp
                                            <select name="_type_vessel" class="form-select"
                                                value="{{ old('_type_vessel') }}">
                                                @foreach ($_select as $item)
                                                    <option value="{{ $item }}"
                                                        {{ old('_type_vessel') ? (old('_type_vessel') == $item ? 'selected' : '') : '' }}>
                                                        {{ $item }}</option>
                                                @endforeach
                                            </select>
                                            @error('_type_vessel')
                                                <div class="text-danger">{{ $message }}</div>
                                            @enderror
                                        </div>
                                        <div class="form-group col-md">
                                            <label class="form-label-sm"><small>SEA EXPERIENCE</small></label>
                                            <select name="_ship" id="" class="form-select">
                                                <option value="foreign"
                                                    {{ old('_ship') ? (old('_ship') == 'foreign' ? 'selected' : '') : '' }}>
                                                    Foreign Ship</option>
                                                <option value="domestic"
                                                    {{ old('_ship') ? (old('_ship') == 'domestic' ? 'selected' : '') : '' }}>
                                                    Domestic Ship</option>
                                            </select>
                                        </div>
                                        <div class="form-group col-md">
                                            <label class="form-label-sm"><small>ONBOARD STATUS</small></label>
                                            <select name="_shipboard_status" id="" class="form-select">
                                                <option value="complete">
                                                    COMPLETE</option>
                                                <option value="incomplete">
                                                    INCOMPLETE</option>
                                                <option value="on going">
                                                    ON GOING</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="form-group col-md">
                                            <label class="form-label-sm"><small>OBT BATCH</small></label>
                                            <input type="text" class="form-control" name="_sbt_batch" value="SBT"
                                                value="{{ old('_sbt_batch') }}">
                                            @error('_sbt_batch')
                                                <span class="invalid-feedback text-danger" role="alert">
                                                    <small> <b>{{ $message }}</b> </small>
                                                </span>
                                            @enderror
                                        </div>
                                        <div class="form-group col-md">
                                            <label class="form-label-sm"><small>DATE OF EMBARKED</small></label>
                                            <input class="form-control" type="date" name="_embarked" max="2029-12-31"
                                                min="2000-12-21" value="{{ old('_embarked') }}">
                                            @error('_embarked')
                                                <div class="text-danger">{{ $message }}</div>
                                            @enderror
                                        </div>
                                        <div class="form-group col-md">
                                            <label class="form-label-sm"><small>DATE OF DISEMBARKED</small></label>
                                            <input class="form-control" type="date" name="_disemabarke"
                                                value="{{ old('_disemabarke') }}" max="2029-12-31" min="2000-12-21">
                                            @error('_disemabarke')
                                                <div class="text-danger">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>

                                    <button class="btn btn-primary w-100">SUBMIT</button>
                                </form>
                            @endif

                        </div>
                    </div>
                </div>

            @endif
        </div>
        <div class="col-md-4">
            <form action="?" method="get">
                <div class="form-group search-input">
                    <input type="search" class="form-control" name="_cadet" placeholder="Search...">
                </div>
            </form>

            @if ($_students)
                @foreach ($_students as $item)
                    <div class="card border-bottom border-4 border-0 border-primary">
                        <a href="?_midshipman={{ base64_encode($item->id) }}">
                            <div class="card-body">
                                <div class="d-flex justify-content-between align-items-center">
                                    <div>
                                        <span
                                            class="text-primary"><b>{{ strtoupper($item->last_name . ', ' . $item->first_name) }}</b></span>
                                    </div>
                                    <div>
                                        <span>{{ $item->account ? $item->account->student_number : '' }}</span>
                                    </div>
                                </div>
                            </div>
                        </a>

                    </div>
                @endforeach

            @else
                <div class="card border-bottom border-4 border-0 border-primary">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <span>NO DATA</span>
                            </div>
                        </div>
                    </div>
                </div>
            @endif

        </div>
    </div>
@endsection
