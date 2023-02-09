@extends('layouts.app-main')
@section('page-title', 'Request Task & System Debug')
@section('page-content')
    <div class="card">
        <div class="card-header d-flex justify-content-between">
            <div class="header-title">
                <h4 class="card-title fw-bolder text-muted">Request Task & System Debug</h4>
            </div>
            <div class="card-tools">
                <button type="button" class="btn btn-primary btn-sm mt-2" data-bs-toggle="modal"
                    data-bs-target="#revision-request">
                    Add Revision Request
                </button>
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
                                    @if (count($tasks) > 0)
                                        @foreach ($tasks as $item)
                                            <tr>
                                                <td>
                                                    @if ($item->status == 1)
                                                        <span class="badge bg-primary">DONE</span>
                                                    @elseif ($item->status == 2)
                                                        <span class="badge bg-info">WORKING</span>
                                                    @else
                                                        <span class="badge bg-danger">PENDING</span>
                                                    @endif
                                                </td>
                                                <td>{{ $item->staff->first_name . ' ' . $item->staff->last_name }}</td>
                                                <td>{{ $item->created_at->format('d F ,Y') }}</td>
                                                <td>{{ $item->task }}</td>
                                                <td>
                                                    @if ($item->task_approved)
                                                        <a href="{{ route('admin.revision-approved') }}?task={{ base64_encode($item->id) }}"
                                                            class="btn btn-primary btn-sm">FOR REVIEW</a>
                                                    @else
                                                        <a href="{{ route('admin.revision-approved') }}?task={{ base64_encode($item->id) }}"
                                                            class="btn btn-primary btn-sm">ACCEPT TASK</a>
                                                    @endif

                                                </td>
                                            </tr>
                                        @endforeach
                                    @endif
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
    <div class="modal fade" id="revision-request">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <p class="modal-title fw-bolder text-primary">CREATE REVISION REQUEST</p>
                    <button type="button" class="close btn" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form action="{{ route('admin.revision-task') }}" method="post">
                        @csrf
                        <div class="row">
                            <div class="form-group col">
                                <label for="" class="fw-bolder">TASKS</label>
                                <textarea class="form-control" name="task" id="" cols="50" rows="10"></textarea>
                                @error('task')
                                    <p class="badge bg-danger">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>
                        <button class="btn btn-success fw-bolder text-white">Submit</button>
                    </form>
                </div>
                <div class="modal-footer justify-content-between">
                </div>
            </div>
        </div>
    </div>
@endsection
