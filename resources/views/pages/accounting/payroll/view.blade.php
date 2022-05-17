@extends('layouts.app-main')
@section('page-title', 'Employees Payroll')
@section('beardcrumb-content')
    <li class="breadcrumb-item active" aria-current="page">
        <svg width="14" height="14" class="me-2" viewBox="0 0 22 22" fill="none"
            xmlns="http://www.w3.org/2000/svg">
            <path
                d="M8.15722 19.7714V16.7047C8.1572 15.9246 8.79312 15.2908 9.58101 15.2856H12.4671C13.2587 15.2856 13.9005 15.9209 13.9005 16.7047V16.7047V19.7809C13.9003 20.4432 14.4343 20.9845 15.103 21H17.0271C18.9451 21 20.5 19.4607 20.5 17.5618V17.5618V8.83784C20.4898 8.09083 20.1355 7.38935 19.538 6.93303L12.9577 1.6853C11.8049 0.771566 10.1662 0.771566 9.01342 1.6853L2.46203 6.94256C1.86226 7.39702 1.50739 8.09967 1.5 8.84736V17.5618C1.5 19.4607 3.05488 21 4.97291 21H6.89696C7.58235 21 8.13797 20.4499 8.13797 19.7714V19.7714"
                stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"></path>
        </svg>Employees Payroll
    </li>
@endsection
@section('page-content')
    <div class="col-xl-4 col-lg-6">
        <a href="{{ route('accounting.staff-salary') }}">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class=" bg-soft-info rounded p-3">
                            <svg xmlns="http://www.w3.org/2000/svg" width="40px" fill="none" viewBox="0 0 24 24"
                                stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M14.828 14.828a4 4 0 01-5.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z">
                                </path>
                            </svg>
                        </div>
                        <div class="ms-5">
                            <h5 class="mb-1">Employees Salary</h5>

                        </div>
                    </div>
                </div>
            </div>
        </a>

    </div>
    <div class="row">
        <div class="card">
            <div class="card-header d-flex justify-content-between">
                <div class="header-title">
                    <h4 class="card-title">Payroll View</h4>
                </div>
                <div class="float-end">
                    <button type="button" class="btn btn-primary btn-sm btn-form-document" data-bs-toggle="modal"
                        data-bs-target=".document-view-modal">
                        Create Payroll</button>
                </div>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive mt-4">
                    <table id="basic-table" class="table table-striped mb-0" role="grid">
                        <thead>
                            <tr class="text-center">
                                <th>MONTH</th>
                                <th>PERIOD</th>
                                <th>ACTION</th>

                            </tr>
                        </thead>
                        <tbody>
                            @if (count($_payroll) > 0)
                                @foreach ($_payroll as $_data)
                                    <tr>
                                        <td>{{ date('F Y', strtotime($_data->cut_off)) }}</td>
                                        <td>{{ ucwords(str_replace('-', ' ', $_data->period)) }}</td>
                                        <td>
                                            <a href="{{ route('accounting.generate-payroll') . '?_payroll=' . base64_encode($_data->id) }}"
                                                class="btn btn-primary btn-sm">EDIT</a>
                                            {{-- <a href="" class="btn btn-primary btn-sm">EDIT</a>
                                            <a href="" class="btn btn-primary btn-sm">EDIT</a> --}}
                                        </td>
                                    </tr>
                                @endforeach
                            @else
                                <tr>
                                    <td colspan="3">NO DATA</td>
                                </tr>
                            @endif

                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade document-view-modal" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-xs">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel1">Create Cut-Off Payroll</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">
                    </button>
                </div>
                <div class="modal-body">
                    <form action="{{ route('accounting.employees-create-payroll') }}" method="post">
                        @csrf
                        <div class="form-group">
                            <label for="" class="form-label">Month</label>
                            <input type="month" name="month" class="form-control" required>
                        </div>
                        <div class="form-group">
                            <label for="" class="form-label">Cut-off Range</label>
                            <select name="cutoff_range" id="" class="form-select" required>
                                <option value="first-cut-off">First Cut-Off</option>
                                <option value="second-cut-off">Second Cut-Off</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <button type="submit" class="btn btn-sm btn-primary">Create</button>
                        </div>
                    </form>
                </div>
            </div>

        </div>
    </div>
@endsection
