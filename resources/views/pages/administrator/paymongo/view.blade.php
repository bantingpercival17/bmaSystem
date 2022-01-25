@extends('app')
@section('page-title', 'Paymongo')
@section('page-navigation')
    <ol class="breadcrumb ">
        <li class="breadcrumb-item"><a href="/">Dashboard</a></li>
        <li class="breadcrumb-item active">Paymongo</li>

    </ol>
@endsection
@section('page-content')
    <div class="row">
        <div class="col-md">
            <label for="" class="text-muted">CREATE WEBHOOKS</label>
            <form action="/admistrator/paymongo/web-hooks" method="post">
                <div class="form-group">

                </div>
            </form>
        </div>
    </div>
    <div class="row">
        <p class="text-info h3"><b>List all Webhooks</b></p>
        {{ dd($webhook) }}
       {{--  @foreach ($webhook as $item)

            <div class="col-md-12">
                <div class="card card-primary">
                    <div class="card-body box-profile">
                        <span class="text-success"> <small class="text-muted"><b>WEBHOOKS ID:</b></small><br>
                            <b>{{ $item->id }}</b></span>
                        <br>
                        <small class="text-muted "><b>SECRET KEY</b></small> <br>
                        <h4 class="text-info">
                            <b>{{ $item->secret_key }} </b>
                        </h4>
                        <label class="text-muted"></label>
                    </div>
                </div>
            </div>
        @endforeach --}}

    </div>

@endsection
