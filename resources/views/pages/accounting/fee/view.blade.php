@extends('layouts.app-main')
@php
    $_title = 'Fees';
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
        @foreach ($_courses as $item)
            <div class="col-lg-4 col-md-6">
                <a
                    href="{{ route('accounting.course-fee-view') }}?_course={{ base64_encode($item->id) }}&_academic={{ request()->input('_academic') }}">
                    <div class="card">
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-center">

                                <div class="">
                                    <h2 class="text-primary">{{ $item->course_code }}</h2>
                                    {{ $item->course_name }}

                                </div>
                            </div>
                        </div>
                    </div>
                </a>

            </div>
        @endforeach
    </div>
    <div class="card shadow">
        <div class="card-header p-3">
            <div class="card-tool float-end">
                <button class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target=".void-view-modal">Create
                    Additional Fees</button>
            </div>
            <h4 class="card-title text-primary fw-bolder">ADDITIONAL FEES</h4>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-striped" id="datatable" data-toggle="data-table">
                    <thead>
                        <tr>
                            <th>Fee Name</th>
                            <th>Amount</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($fees as $fee)
                            <tr>
                                <td>{{ $fee->particular->particular_name }}</td>
                                <td>{{ number_format($fee->amount) }}</td>
                                <td></td>
                            </tr>
                        @empty
                            {{--  <tr>
                                <td colspan="3">No Data</td>
                            </tr> --}}
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <div class="modal fade void-view-modal" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-md">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title fw-bolder">CREATE ADDITIONAL FEE</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">
                    </button>
                </div>
                <div class="modal-body">
                    <form action="{{ route('acounting.addtional-fee') }}" method="post">
                        @csrf
                        <input type="hidden" name="payment" class="modal-payment">
                        <div class="form-group">
                            <small for="" class="form-label fw-bolder">PARTICULAR NAME</small>
                            <select name="particular" class="form-select form-select-sm border border-primary">
                                <option>Select Particular</option>
                                @forelse ($particulars as $particular)
                                    <option value="{{ $particular->id }}">{{ $particular->particular_name }}</option>
                                @empty
                                @endforelse
                            </select>
                        </div>
                        <div class="form-group">
                            <small for="" class="form-label fw-bolder">AMOUNT FEE</small>
                            <input type="text" class="form-control form-control-sm border border-primary"
                                name="fee_amount">
                        </div>
                        <button class="btn btn-primary btn-sm w-100">SUBMIT</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
