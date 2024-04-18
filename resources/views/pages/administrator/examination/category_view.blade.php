@extends('layouts.app-main')
@php
$_title = 'Examination Category';
@endphp
@section('page-title', $_title)
@section('beardcrumb-content')
    <li class="breadcrumb-item">
        <a href="{{ route('admin.examination') }}">
            <svg width="14" height="14" class="me-2" viewBox="0 0 22 22" fill="none"
                xmlns="http://www.w3.org/2000/svg">
                <path
                    d="M8.15722 19.7714V16.7047C8.1572 15.9246 8.79312 15.2908 9.58101 15.2856H12.4671C13.2587 15.2856 13.9005 15.9209 13.9005 16.7047V16.7047V19.7809C13.9003 20.4432 14.4343 20.9845 15.103 21H17.0271C18.9451 21 20.5 19.4607 20.5 17.5618V17.5618V8.83784C20.4898 8.09083 20.1355 7.38935 19.538 6.93303L12.9577 1.6853C11.8049 0.771566 10.1662 0.771566 9.01342 1.6853L2.46203 6.94256C1.86226 7.39702 1.50739 8.09967 1.5 8.84736V17.5618C1.5 19.4607 3.05488 21 4.97291 21H6.89696C7.58235 21 8.13797 20.4499 8.13797 19.7714V19.7714"
                    stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"></path>
            </svg>{{ $_title }}
        </a>
    </li>
    <li class="breadcrumb-item active" aria-current="page">
        {{ $_examination->examination_name }}
    </li>
@endsection
@section('page-content')
    <div class="row">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <span class="fw-bolder text-primary">{{ $_examination->examination_name }}</span>
                    <br>
                    <small class="fw-bolder">{{ $_examination->department }}</small>
                </div>
                <div class="card-body">
                    <table id="datatable" class="table table-striped" data-toggle="data-table">
                        <thead>
                            <tr>
                                <th>EXAMINATION CATEGORY</th>
                                <th>SUBJECT</th>
                                <th>NO. ITEM/S</th>
                                <th>ACTION</th>
                            </tr>
                        </thead>

                        <tbody>
                            @foreach ($_examination->categories as $exam)
                                <tr>
                                    <td>{{ $exam->category_name }}</td>
                                    <td>{{ $exam->subject_name }}</td>
                                    <td>{{ count($exam->question) }}</td>
                                    <td>
                                        <a href="{{ route('admin.examination-category') }}?_view={{ base64_encode($exam->id) }}"
                                            class="btn btn-info btn-sm text-white">view</a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

            </div>
        </div>
        <div class="col-md-4">
            <div class="card">
                <label class="card-header fw-bolder text-primary">IMPORT QUESTION</label>
                <div class="card-body">
                    <form role="form" action="{{ route('admin.import-examination') }}" method="POST"
                        enctype="multipart/form-data">
                        @csrf
                        <div class="form-group">
                            <label for="" class="form-label">FILE ATTACH</label>
                            <input type="file" name="files" class="form-control" value="{{ old('files') }}"
                                accept="xlsx">
                            <input type="hidden" name="exam" value="{{ request()->input('_view') }}">
                            @error('files')
                                <span class="mt-2 badge bg-danger">{{ $message }}</span>
                            @enderror
                        </div>
                        <div class="form-group">
                            <button type="submit" class="btn btn-sm btn-primary">IMPORT</button>
                        </div>
                    </form>
                </div>
            </div>
            <div class="card">
                <label class="card-header fw-bolder text-primary">IMPORT QUESTION V2</label>
                <div class="card-body">
                    <form role="form" action="{{ route('admin.import-examination-v2') }}" method="POST"
                        enctype="multipart/form-data">
                        @csrf
                        <div class="form-group">
                            <small class="fw-bolder text-muted">FILLE ATTACH</small>
                            <input type="file" name="files" class="form-control form-control-sm border border-primary" value="{{ old('files') }}"
                                accept="xlsx">
                            <input type="hidden" name="exam" value="{{ request()->input('_view') }}">
                            @error('files')
                                <span class="mt-2 badge bg-danger">{{ $message }}</span>
                            @enderror
                        </div>
                        <div class="form-group">
                            <button type="submit" class="btn btn-sm btn-primary">IMPORT</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>


@endsection
