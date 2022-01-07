@extends('layouts.app-main')
@section('page-title', 'Midshipman')
@section('page-navigation')
    <ol class="breadcrumb ">
        <li class="breadcrumb-item"><a href="/">Dashboard</a></li>
        <li class="breadcrumb-item active"> Midshipman</li>

    </ol>
@endsection
@section('page-content')
    <div class="row">
        <div class="col-md-7">
            <div class="callout callout-success">
                @php
                    # $_course = $_cadet ? $_cadet->current_enrolled_status_obto->course : '';
                    $_student_name = $_cadet ? strtoupper($_cadet->last_name . ', ' . $_cadet->first_name . ' ' . $_cadet->middle_name) : 'COMPLETE NAME';
                    $_student_no = $_cadet ? $_cadet->account->student_name /*  . ' | ' . $_course->course_code */ : 'STUDENT NUMBER';
                    #$_profile = $_cadet ? ($_course->department === 'COLLEGE' ? '/img/1x1 COLLEGE/' . $_cadet->user->client_code . '.png' : '/img/1x1 SHS/' . $_cadet->user->client_code . '.jpg') : '/img/midship-man.jpg';
                @endphp
                <div class="container">
                    <div class="row">
                        <div class="col-md-9">
                            <h5><b class="text-muted">CADET'S INFORMATION</b></h5>
                            <h4 class="{{ $_cadet ? 'text-success' : 'text-muted' }}"><b>{{ $_student_name }}</b></h4>
                            <h5 class="{{ $_cadet ? 'text-success' : 'text-muted' }}"><b>{{ $_student_no }}</b></h5>
                        </div>
                        <div class="col-md-3">
                            <img class="img-circle elevation-2" src="{{ url('$_profile') }}" alt="User Avatar"
                                height="120px">
                        </div>
                    </div>
                </div>
            </div>
            @if ($_cadet)
                {{-- Shipboard Information --}}
                <div class="card card-prirary ">
                    <div class="card-header">
                        <h3 class="card-title"><b class="text-success">SHIPBOARD TRAINING</b></h3>
                        <div class="card-tools">
                            <button type="button" class="btn btn-tool" data-card-widget="collapse"><i
                                    class="fas fa-plus"></i></button>
                        </div>
                    </div>
                    <div class="card-body">
                        @if ($_cadet->shipboard)
                            <label for="" class="text-info">| SHIPBOARD DETAILS</label>
                            <div class="form-group">
                                <div class="row">

                                    <div class="col-md">
                                        <label for="" class="text-muted text-xs">COMPANY NAME</label>
                                        <span class="form-control">{{ $_cadet->shipboard->company_name }}</span>
                                    </div>
                                    <div class="col-md">
                                        <label for="" class="text-muted text-xs">NAME OF SHIP</label>
                                        <span class="form-control">{{ $_cadet->shipboard->vessel_name }}</span>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md">
                                        <label for="" class="text-muted text-xs">VESSEL TYPE</label>
                                        <span class="form-control">{{ $_cadet->shipboard->vessel_type }}</span>
                                    </div>
                                    <div class="col-md-4">
                                        <label for="" class="text-muted text-xs">FOR/DOM.</label>
                                        <span
                                            class="form-control">{{ strtoupper($_cadet->shipboard->shipping_company) }}</span>

                                    </div>
                                    <div class="col-md-4">
                                        <label for="" class="text-muted text-xs">COMPANY GROUP</label>
                                        <span
                                            class="form-control">{{ strtoupper($_cadet->shipboard->company_group) }}</span>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-3">
                                        <label for="" class="text-muted text-xs">OBT BATCH</label>
                                        <span
                                            class="form-control">{{ strtoupper($_cadet->shipboard->sbt_batch) }}</span>
                                    </div>
                                    <div class="col-md">
                                        <label for="" class="text-muted text-xs">DATE OF EMBARKED</label>
                                        <span
                                            class="form-control">{{ strtoupper($_cadet->shipboard->embarked) }}</span>
                                    </div>
                                    <div class="col-md">
                                        <label for="" class="text-muted text-xs">DATE OF DISEMBARKED</label>
                                        <input class="form-control" type="date" name="_disemabarke"
                                            value="{{ old('_disemabarke') }}" max="2021-12-31" min="2000-12-21">
                                        @error('_disemabarke')
                                            <div class="text-danger">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                        @else
                            <form action="/onboard/cadets/shipboard-training" method="post">
                                @csrf
                                <input type="hidden" name="_student_id" value="{{ $_cadet->id }}">
                                <label for="" class="text-info">| SHIPBOARD DETAILS</label>
                                <div class="form-group">
                                    <div class="row">

                                        <div class="col-md">
                                            <label for="" class="text-muted text-xs">COMPANY NAME</label>
                                            <input type="text" class="form-control" name="company_name">

                                            @error('company_name')
                                                <span class="invalid-feedback text-danger" role="alert">
                                                    <small> <b>{{ $message }}</b> </small>
                                                </span>
                                            @enderror
                                        </div>
                                        <div class="col-md">
                                            <label for="" class="text-muted text-xs">NAME OF SHIP</label>
                                            <input type="text" class="form-control" name="_ship_name">
                                            @error('_ship_name')
                                                <span class="invalid-feedback text-danger" role="alert">
                                                    <small> <b>{{ $message }}</b> </small>
                                                </span>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md">
                                            <label for="" class="text-muted text-xs">VESSEL TYPE</label>
                                            <select name="_type_vessel" class="form-control"
                                                value="{{ old('_type_vessel') }}">
                                                <option value="CONTAINER VESSEL">CONTAINER VESSEL</option>
                                                <option value="GENERAL CARGO">GENERAL CARGO</option>
                                                <option value="TANKER">TANKER</option>
                                            </select>
                                            @error('_type_vessel')
                                                <div class="text-danger">{{ $message }}</div>
                                            @enderror
                                        </div>
                                        <div class="col-md-4">
                                            <label for="" class="text-muted text-xs">FOR/DOM.</label>
                                            <select name="_ship" id="" class="form-control">
                                                <option value="foreign">Foreign Ship</option>
                                                <option value="domestic">Domestic Ship</option>
                                            </select>
                                        </div>
                                        <div class="col-md-4">
                                            <label for="" class="text-muted text-xs">COMPANY GROUP</label>
                                            <select name="_company_group" id="" class="form-control">
                                                <option value="com">COM</option>
                                                <option value="inc">INC</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-3">
                                            <label for="" class="text-muted text-xs">OBT BATCH</label>
                                            <input type="text" class="form-control" name="_sbt_batch" value="SBT">
                                            @error('_sbt_batch')
                                                <span class="invalid-feedback text-danger" role="alert">
                                                    <small> <b>{{ $message }}</b> </small>
                                                </span>
                                            @enderror
                                        </div>
                                        <div class="col-md">
                                            <label for="" class="text-muted text-xs">DATE OF EMBARKED</label>
                                            <input class="form-control" type="date" name="_embarked"
                                                value="{{ old('_embarked') }}" max="2021-12-31" min="2000-12-21">
                                            @error('_embarked')
                                                <div class="text-danger">{{ $message }}</div>
                                            @enderror
                                        </div>
                                        <div class="col-md">
                                            <label for="" class="text-muted text-xs">DATE OF DISEMBARKED</label>
                                            <input class="form-control" type="date" name="_disemabarke"
                                                value="{{ old('_disemabarke') }}" max="2021-12-31" min="2000-12-21">
                                            @error('_disemabarke')
                                                <div class="text-danger">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                </div>
                                <button class="btn btn-success float-right">SUBMIT</button>
                            </form>
                        @endif

                    </div>
                </div>
                {{-- DEPLOYMENT PANEL --}}
                @if ($_cadet->onboard_training_deployment)
                    <div class="card card-prirary ">
                        <div class="card-header">
                            <h3 class="card-title"><b class="text-success">DEPLOYMENT APPLICATION</b></h3>
                            <div class="card-tools">
                                <button type="button" class="btn btn-tool" data-card-widget="collapse"><i
                                        class="fas fa-minus"></i></button>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="form-group">
                                <label for="" class="text-muted">COMPANY NAME</label>
                                <p class="text-success h5">
                                    <b>{{ $_cadet->onboard_training_deployment->agency->agency_name }}</b>

                            </div>
                            <div class="form-group">
                                <label for="" class="text-muted s">PRE-DEPLOYMENT DOCUMENTS</label>
                            </div>
                            @if ($_pre_documents)
                                <div class="row">
                                    @foreach ($_pre_documents as $_key => $_docu)
                                        @php
                                            $_cadet_docs = $_docu->one_document($_cadet->id, $_docu->id);
                                            $_status = $_cadet_docs->document_status == 3 ? ['primary', ''] : ['primary', ''];
                                            $_status = $_cadet_docs->document_status == 0 ? ['danger', 'DISAPPROVED'] : $_status;
                                            $_status = $_cadet_docs->document_status == 1 ? ['success', 'APPROVED'] : $_status;
                                            $_status = $_cadet_docs->document_status == 2 ? ['warning', 'RESUBMIT'] : $_status;
                                        @endphp
                                        <div class="col-md-6">
                                            <div class="callout callout-{{ $_status[0] }}">
                                                <div class="container row">
                                                    <div class="col">
                                                        <p
                                                            class="text-{{ $_status[0] == 'primary' ? 'muted' : $_status[0] }} h5">
                                                            <b>{{ $_docu->document_name }} </b>
                                                        </p>
                                                    </div>
                                                    <div class="col-md-2">
                                                        @if ($_cadet_docs->document_status != 1)
                                                            <button class="btn btn-outline-success  btn-document btn-sm"
                                                                data-value="{{ $_cadet_docs->id }}"
                                                                data-url="/onboard/documents/view/" data-modal="documents"
                                                                data-view-tab="#document-review">
                                                                <i class="fas fa-eye"></i></button>
                                                        @endif

                                                    </div>

                                                </div>
                                                @if ($_cadet_docs->admin)
                                                    <div class="row">
                                                        <div class="col">
                                                            <label class="text-muted small">PROCESS BY:
                                                                <br><b
                                                                    class="text-muted">{{ $_cadet_docs->admin->name }}</b>
                                                            </label>
                                                        </div>
                                                        <div class="col">
                                                            <label class="text-muted small">PROCESS DATE:
                                                                <br><b
                                                                    class="text-muted">{{ $_cadet_docs->updated_at->format('F d,Y') }}</b>
                                                            </label>
                                                        </div>
                                                    </div>
                                                @endif
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            @endif
                        </div>
                    </div>
                @endif
                {{-- CERTIFICATEA AND TRAINING PANEL --}}
                <div class="card card-prirary collapsed-card">
                    <div class="card-header">
                        <h3 class="card-title"><b class="text-success">CERTIFICATES AND TRAININGS</b></h3>
                        <div class="card-tools">
                            <button type="button" class="btn btn-tool" data-card-widget="collapse"><i
                                    class="fas fa-plus"></i></button>
                        </div>
                    </div>
                    <div class="card-body">
                        @if ($_certificates)
                            <ul class="nav flex-column">
                                @foreach ($_certificates as $_certificate)
                                    @php
                                        $_cstatus = $_certificate->student_training([$_cadet->id, $_certificate->id]) ? $_certificate->student_training([$_cadet->id, $_certificate->id]) : '';
                                    @endphp
                                    <li class="nav-item">
                                        <div class="nav-link text-muted">
                                            @if (!$_cstatus)
                                                <div class="float-right form-group clearfix">
                                                    <form action="/onboard/midship-man/certificates" method="post">
                                                        @csrf
                                                        <div class="input-group input-group-sm">
                                                            <input type="text" class="form-control" name="_cer_code">
                                                            <input type="hidden" name="_cadet"
                                                                value="{{ base64_encode($_cadet->id) }}">
                                                            <input type="hidden" name="_certificate"
                                                                value="{{ base64_encode($_certificate->id) }}">
                                                            <span class="input-group-append">
                                                                <button type="submit"
                                                                    class="btn btn-info btn-flat">Submit</button>
                                                            </span>
                                                        </div>
                                                    </form>
                                                </div>
                                            @endif
                                            <b
                                                class=" {{ $_cstatus ? 'text-success' : '' }}">{{ $_certificate->training_name }}</b>
                                            <br>
                                            <small>{{ ucwords($_certificate->training_details) }}</small>

                                            @if ($_cstatus)
                                                <br>
                                                <small>CERTIFICATE NO. :
                                                    <span class="text-success"><b>
                                                            {{ $_cstatus ? ucwords($_cstatus->certificate_number) : '' }}</b>
                                                    </span>
                                                </small>
                                                <br>
                                                <p class="small">
                                                    <i>APPROVED BY: </i>
                                                    <span class="badge badge-success">
                                                        {{ ucwords(strtolower($_cstatus->staff->user->name)) }}
                                                    </span>
                                                    |
                                                    <i>APPROVED DATE: </i>
                                                    <span class="badge badge-success">
                                                        {{ ucwords($_cstatus->updated_at) }}
                                                    </span>
                                                </p>
                                            @endif
                                        </div>
                                    </li>
                                @endforeach

                            </ul>
                        @endif
                    </div>
                </div>

            @else
                {{-- DEFAULT PANEL --}}
                <div class="card card-prirary collapsed-card">
                    <div class="card-header">
                        <h3 class="card-title"><b class="text-success">CERTIFICATES AND TRAININGS</b></h3>
                    </div>
                </div>

                <div class="card card-prirary collapsed-card">
                    <div class="card-header">
                        <h3 class="card-title"><b class="text-success">SHIPBOARD INFORMATION</b></h3>
                    </div>
                </div>
            @endif
        </div>
        <div class="col-md-5">
            <div class="card">
                <div class="card-body">
                    <form role="form" action="/onboard/midship-man">
                        <input type="text" class="form-control text-code input-search"
                            placeholder="Search e.i Dela Cruz, Juan" data-container="search-container"
                            data-component="panel" data-url="/onboard/student/search/"
                            data-link="/onboard/cadets?search_data=" name="_cadet">
                    </form>
                </div>
            </div>
            {{-- {{ $_data ? $_data->links() : '' }} --}}
            <div class="search-container">

                @if ($_students)
                    @foreach ($_students as $data)
                        <a href="?search_data={{ base64_encode($data->id) }}" class="btn btn-outline-success btn-block"
                            style="text-decoration: none">{{ strtoupper($data->last_name . ', ' . $data->first_name) . ' | ' . $data->account->student_number }}</a>
                    @endforeach
                @else
                    <a href="" class="btn btn-outline-success btn-block" style="text-decoration: none">NO STUDENT</a>
                @endif

            </div>
        </div>
    </div>
@endsection
