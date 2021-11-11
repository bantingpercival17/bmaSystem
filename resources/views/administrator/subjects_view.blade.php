@extends('app')
@section('page-title', 'Curriculum')
@section('page-navigation')
    <ol class="breadcrumb ">
        <li class="breadcrumb-item"><a href="/">Dashboard</a></li>
        <li class="breadcrumb-item active">Curriculum</li>

    </ol>
@endsection
@section('page-content')
    <div class="row">
        <div class="col-8">
            <div class="row">
                @if ($_curriculum->count() > 0)
                    @foreach ($_curriculum as $data)
                        <div class="col-md-4">
                            <a href="/administrator/subjects/curriculum?_c={{ Crypt::encrypt($data->id) }}">
                                <div class="card card-primary ">
                                    <div class="card-body box-profile">
                                        <div class="text-center">
                                            <h4 class="text-info">{{ $data->curriculum_name }}</h4>
                                        </div>
                                        <p class="text-muted text-center">{{ $data->curriculum_year }}</p>

                                    </div>
                                </div>
                            </a>
                        </div>
                    @endforeach
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
        <div class="col-4">
            <div class="card">
                <div class="card-header">
                    <h4>ADD CURRICULUM</h4>
                </div>
                <div class="card-body">
                    <form action="/administrator/curriculum" method="post">
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
                        <button class="btn btn-success btn-block">Create Curriculum</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <div class="">
        <label for="" class="   text-secondary h4">| SUBJECT CLASSES</label>
        <div class="row">
            @if ($_academic->count() > 0)
                @foreach ($_academic as $data)
                    <div class="col-md-3">
                        <a href="/administrator/subjects/class?_c={{ Crypt::encrypt($data->id) }}">
                            <div class="card card-primary ">
                                <div class="card-body box-profile">
                                    <div class="text-center">
                                        <h4 class="text-info">{{ $data->school_year . ' | ' . $data->semester }}
                                        </h4>
                                    </div>
                                    <p class="text-muted text-center"> <span
                                            class="badge badge-{{ $data->is_active == 1 ? 'success' : '' }}">{{ $data->is_active == 1 ? 'Current Year' : 'Old Academic Year' }}
                                        </span></p>

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
                    <form action="/administrator/curriculum" method="post">
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
