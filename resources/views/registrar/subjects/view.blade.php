@extends('app')
@section('page-title', 'Subjects')
@section('page-navigation')
    <ol class="breadcrumb ">
        <li class="breadcrumb-item"><a href="/">Dashboard</a></li>
        <li class="breadcrumb-item active">Subjects</li>

    </ol>
@endsection
@section('page-content')
    <div class="">
        <label for="" class="   text-secondary h4">| SUBJECT CLASSES</label>
        <div class="row">
            @if ($_academic->count() > 0)
                @foreach ($_academic as $data)
                    <div class=" col-lg-3 col-md-4">
                        <a href="/registrar/subjects/classes?_view={{ base64_encode($data->id) }}">
                            <div class="card card-primary ">
                                <div class="card-body box-profile">

                                    <small class="h5 text-muted"><b> {{ $data->school_year }}</b></small>
                                    <br>
                                    <small class="h5 text-muted"><b> {{ $data->semester }}</b></small>
                                    <br>
                                    @if ($data->is_active == 1)
                                        <span class="h6 text-info">
                                            <b>
                                                CURRENT ACADEMIC YEAR
                                            </b>
                                        </span>
                                    @else
                                        <span class="h6 text-secondary">
                                            <b>
                                                PREVIOUS ACADEMIC YEAR
                                            </b>
                                        </span>
                                    @endif
                                </div>
                            </div>
                        </a>
                    </div>
                @endforeach
            @else
                <div class="col-md-3">
                    <div class="card card-primary " data-toggle="modal" data-target="#modal-lg">
                        <div class="card-body box-profile">
                            <div class="text-center">
                                <h4 class="text-info"><i class="fa fa-plus"></i></h4>
                            </div>
                            <p class="text-muted text-center">ADD ACADEMIC</p>
                        </div>
                    </div>
                </div>
            @endif
        </div>

    </div>
    <label for="" class="   text-secondary h4">| SUBJECT CURRICULUM</label>
    <div class="row">

        <div class="col-12">
            <div class="row">
                @if ($_curriculum->count() > 0)
                    @foreach ($_curriculum as $data)
                        <div class=" col-lg-3 col-md-4">
                            <a href="/registrar/subjects/curriculum?view={{ base64_encode($data->id) }}">
                                <div class="card card-primary ">
                                    <div class="card-body box-profile">

                                        <small class="h4 text-info"><b> {{ $data->curriculum_name }}</b></small>
                                        <br>
                                        <small class="h5 text-muted"><b> {{ $data->curriculum_year }}</b></small>
                                        <br>

                                    </div>
                                </div>
                            </a>
                        </div>
                    @endforeach
                    <div class="col-md-4">
                        <div class="card card-primary " data-toggle="modal" data-target="#modal-lg">
                            <div class="card-body box-profile">
                                <div class="text-center">
                                    <h4 class="text-info"><i class="fa fa-plus"></i></h4>
                                </div>
                                <p class="text-muted text-center">ADD CURRICULUM</p>
                            </div>
                        </div>
                    </div>
                @else
                    <div class="col-md-4">
                        <div class="card card-primary " data-toggle="modal" data-target="#modal-lg">
                            <div class="card-body box-profile">
                                <div class="text-center">
                                    <h4 class="text-info"><i class="fa fa-plus"></i></h4>
                                </div>
                                <p class="text-muted text-center">ADD CURRICULUM</p>
                            </div>
                        </div>
                    </div>
                @endif
            </div>
        </div>

    </div>


    <div class="modal fade" id="modal-lg">
        <div class="modal-dialog modal-md">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">ADD CURRICULUM</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form action="/registrar/subjects/curriculum" method="post">
                        @csrf
                        <div class="row">
                            <div class="form-group col-12">
                                <label for="">Curriculum Name</label>
                                <input type="text" class="form-control" name="curriculum_name">
                            </div>
                            <div class="form-group col-12">
                                <label for="">Effective Year</label>
                                <input type="text" class="form-control" name="effective_year">
                            </div>
                        </div>
                        <button class="btn btn-success">Create Curriculum</button>
                    </form>
                </div>
            </div>
            <!-- /.modal-content -->
        </div>
        <!-- /.modal-dialog -->
    </div>
@endsection
