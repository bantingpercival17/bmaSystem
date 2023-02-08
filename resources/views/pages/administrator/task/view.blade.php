@extends('layouts.app-main')
@section('page-title', 'Request Task & System Debug')
@section('page-content')
    <div class="card">
        <div class="card-header d-flex justify-content-between">
            <div class="header-title">
                <h4 class="card-title fw-bolder text-muted">Request Task & System Debug</h4>
            </div>
        </div>
        <div class="card-body ">
            <ul class="nav nav-tabs nav-fill" id="myTab-three" role="tablist">
                <li class="nav-item">
                    <a class="nav-link nav-link active" id="task-tab-1" data-bs-toggle="tab" href="#task-tab-1-content"
                        role="tab" aria-controls="home" aria-selected="true">
                        Report Task
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link nav-link" id="task-tab-2" data-bs-toggle="tab" href="#task-tab-2-content"
                        role="tab" aria-controls="home" aria-selected="false">
                        System Debug
                    </a>
                </li>
            </ul>
            <div class="tab-content" id="myTabContent-4">
                <div class="tab-pane fade show active" id="task-tab-1-content" role="tabpanel" aria-labelledby="task-tab-1">
                    <div class="content">
                        <div class="table-responsive mt-4">
                            <table id="datatable" class="table table-striped" data-toggle="data-table">
                                <thead>
                                    <tr>
                                        <th>Task Status</th>
                                        <th>User Report</th>
                                        <th>Task Date</th>
                                        <th>Task Report</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>

                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
                <div class="tab-pane fade show" id="task-tab-2-content" role="tabpanel" aria-labelledby="task-tab-2">
                    <div class="content">
                        <div class="table-responsive mt-4">
                            <table id="datatable" class="table table-striped" data-toggle="data-table">
                                <thead>
                                    <tr>
                                        <th>Message Error</th>
                                        <th>User</th>
                                        <th>Report URL</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @if (count($debug_tacker) > 0)
                                        @foreach ($debug_tacker as $bug)
                                            <tr>
                                                <td>{{ $bug->error_message }}</td>
                                                <td>{{ $bug->user_name }}</td>
                                                <td>{{ $bug->user_ip_address }}</td>
                                                <td></td>
                                            </tr>
                                        @endforeach
                                    @endif
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>
@endsection
