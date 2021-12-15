@extends('app')
@section('page-title', 'Dashboard')
@section('page-content')
    <div class="row">
        <div class="col-md-4">
            <a href="/onboard/cadets">
                <div class="card card-primary ">
                    <div class="card-body box-profile">
                        <div class="text-center">
                            <h4 class="text-info">0</h4>
                        </div>
                        <p class="text-muted text-center">INCOMING 2ND CLASS </p>
                    </div>
                </div>
            </a>

        </div>
        <div class="col-md-4">
            <div class="card card-primary ">
                <div class="card-body box-profile">
                    <div class="text-center">
                        <h4 class="text-info">0</h4>
                    </div>
                    <p class="text-muted text-center">ENROLLED 2ND CLASS </p>
                </div>
            </div>
        </div>
    </div>
@endsection
