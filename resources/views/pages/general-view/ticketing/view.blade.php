@extends('layouts.app-main')
@php
$_title = 'Ticketing';
@endphp
@section('page-title', $_title)
@section('beardcrumb-content')
    <li class="breadcrumb-item ">
        <a href="/">
            <svg width="14" height="14" class="me-2" viewBox="0 0 22 22" fill="none"
                xmlns="http://www.w3.org/2000/svg">
                <path
                    d="M8.15722 19.7714V16.7047C8.1572 15.9246 8.79312 15.2908 9.58101 15.2856H12.4671C13.2587 15.2856 13.9005 15.9209 13.9005 16.7047V16.7047V19.7809C13.9003 20.4432 14.4343 20.9845 15.103 21H17.0271C18.9451 21 20.5 19.4607 20.5 17.5618V17.5618V8.83784C20.4898 8.09083 20.1355 7.38935 19.538 6.93303L12.9577 1.6853C11.8049 0.771566 10.1662 0.771566 9.01342 1.6853L2.46203 6.94256C1.86226 7.39702 1.50739 8.09967 1.5 8.84736V17.5618C1.5 19.4607 3.05488 21 4.97291 21H6.89696C7.58235 21 8.13797 20.4499 8.13797 19.7714V19.7714"
                    stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"></path>
            </svg>Dashboard
        </a>

    </li>
    <li class="breadcrumb-item active" aria-current="page">
        {{ $_title }}
    </li>
@endsection
@section('page-content')
    <div class="row">
        <div class="col-md-7">
            <div class="card">
                <div class="card-header">Create Ticket Concern</div>
                <div class="card-body">
                    <form action="{{ route('concern-store') }}" method="post">
                        @csrf
                        <div class="row">
                            <div class="form-group col">
                                <label for="" class="form-label">Concern Name</label>
                                <input type="text" class="form-control" name="concern_name">
                                @error('concern_name')
                                    <span class="badge bg-danger">{{ $message }}</span>
                                @enderror
                            </div>
                            <div class="form-group col">
                                <label for="" class="form-label">Department Concern</label>
                                <select name="department" class="form-select">
                                    @foreach ($_department as $dept)
                                        <option value="{{ $dept->id }}">{{ $dept->code }} DEPARTMENT</option>
                                    @endforeach
                                </select>
                                @error('department')
                                    <span class="badge bg-danger">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>
                        <button type="submit" class="btn btn-primary btn-sm w-100">Create Concern</button>
                    </form>
                </div>
            </div>

        </div>
    </div>
    <div class="card">
        <div class="card-header">
            Concern List
        </div>
        <div class="card-body">
            @if (count($_concern_list) > 0)
                <table id="basic-table" class="table table-striped mb-0" role="grid">
                    <thead>
                        <tr>
                            <th>Concern Name</th>
                            <th>Concern Department</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($_concern_list as $concern)
                            <tr>
                                <td>
                                    {{ $concern->issue_name }}
                                </td>
                                <td>
                                    {{ $concern->department->name }}
                                </td>
                                <td>
                                    <a href="{{ route('concern-removed') }}?concern={{ base64_encode($concern->id) }}"
                                        class="btn-btn-primary">Remove</a>
                                </td>

                            </tr>
                        @endforeach

                    </tbody>
                </table>
            @else
            @endif
        </div>
    </div>
    <div class="card">
        <div class="card-header">
            Concern List
        </div>
        <div class="card-body">
            @if (count($_concern_list) > 0)
                <table id="basic-table" class="table table-striped mb-0" role="grid">
                    <thead>
                        <tr>
                            <th>Ticket Number</th>
                            <th>Concern Issue</th>
                            <th>Concern Status</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($_ticket_list as $data)
                            <tr>
                                <td>
                                    {{ $data->ticket_number }}
                                </td>
                                <td>
                                    {{ $data->ticket ? $data->ticket_concern->ticket_issue->issue_name : null }}
                                </td>
                                <td>
                                    {{ $data->ticket ? $data->ticket_concern->is_resolved : '' }}
                                </td>
                                <td>
                                    {{-- <a href="{{ route('concern-removed') }}?concern={{ base64_encode($concern->id) }}"
                                        class="btn-btn-primary">Remove</a> --}}
                                </td>

                            </tr>
                        @endforeach

                    </tbody>
                </table>
            @else
            @endif
        </div>
    </div>
@endsection
