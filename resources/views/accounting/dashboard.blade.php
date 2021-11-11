@extends('app')
@section('page-title', 'Dashboard')
@section('page-content')
    <div class="row">
        <div class="col-md-4">
            <div class="card card-primary ">
                <div class="card-body box-profile">
                    <div class="text-center">
                        <h4 class="text-primary">{{ number_format($_transaction, 0) }}</h4>

                        <span class="text-secondary">
                            {{ date_format(date_create(date('Y-m-d')), 'M d, Y') }}
                        </span>

                    </div>
                    <p class="text-center text-primary">TOTAL TRANSACTION</p>

                    <ul class="list-group list-group-unbordered mb-3">
                        @foreach ($_prev_transaction as $_key => $collection)
                            <li class="list-group-item">
                                <b>{{ date_format(date_create($_key), 'M d, Y') }}</b> <a class="float-right">
                                    {{ number_format($collection, 0) }}</a>
                                
                            </li>
                        @endforeach
                    </ul>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card card-primary ">
                <div class="card-body box-profile">
                    <div class="text-center">
                        <a href="/accounting/report/collection?generate={{ date('Y-m-d') }}&collection=daily"
                            class="btn btn-sm btn-tool text-secondary float-right">
                            <i class="fas fa-download"></i>
                        </a>
                        <h4 class="text-info">PHP {{ number_format($_daily_collection, 2) }}</h4>

                        <span class="text-secondary">
                            {{ date_format(date_create(date('Y-m-d')), 'M d, Y') }}
                        </span>

                    </div>
                    <p class="text-muted text-center">DAILY COLLECTION </p>

                    <ul class="list-group list-group-unbordered mb-3">
                        @foreach ($_prev_daily_collection as $_key => $collection)
                            <li class="list-group-item">
                                <b>{{ date_format(date_create($_key), 'M d, Y') }}</b> <a class="float-right">Php
                                    {{ number_format($collection, 2) }}</a>
                                <a href="/accounting/report/collection?generate={{ $_key }}&collection=daily"
                                    class="btn btn-sm btn-tool text-secondary">
                                    <i class="fas fa-download"></i>
                                </a>
                            </li>
                        @endforeach
                    </ul>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card card-primary ">
                <div class="card-body box-profile">
                    <div class="text-center">
                        <a href="/accounting/report/collection?generate={{ date('Y-m') }}&collection=monthly"
                            class="btn btn-sm btn-tool text-secondary float-right">
                            <i class="fas fa-download"></i>
                        </a>
                        <h4 class="text-success">PHP {{ number_format($_monthly_collection, 2) }}</h4>

                        <span class="text-secondary">
                            {{ date_format(date_create(date('Y-m-d')), 'M Y') }}
                        </span>

                    </div>
                    <p class="text-muted text-center">MONTHLY COLLECTION </p>

                    <ul class="list-group list-group-unbordered mb-3">
                        @foreach ($_prev_monthly_collection as $_key => $collection)
                            <li class="list-group-item">
                                <b>{{ date_format(date_create($_key), 'M Y') }}</b> <a class="float-right">Php
                                    {{ number_format($collection, 2) }}</a>
                                <a href="/accounting/report/collection?generate={{ $_key }}&collection=monthly"
                                    class="btn btn-sm btn-tool text-secondary">
                                    <i class="fas fa-download"></i>
                                </a>
                            </li>
                        @endforeach
                    </ul>
                </div>
            </div>
        </div>
    </div>
@endsection
