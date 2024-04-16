@php
    $pageTitle = 'Mobile Application Deployment';
@endphp
@section('page-title', $pageTitle)
<div>
    <div class="row">
        <div class="col-md-8">
            <p class="display-6 fw-bolder text-primary">{{ strtoupper($pageTitle) }}</p>
            <div class="content-page">
                @forelse ($mobile_application_lists as $app_data)
                    <a href="{{ route('admin.application-version') . '?app=' . base64_encode($app_data->id) }}">
                        <div class="card mb-2 shadow mb-3">
                            <div class="row no-gutters">
                                <div class="col-md-3">
                                    <img src="{{ $app_data->app_logo_path }}" class="card-img" alt="#">
                                </div>
                                <div class="col-md ps-0">
                                    <div class="card-body p-3 me-2">
                                        <label for=""
                                            class="fw-bolder text-primary h4">{{ $app_data->app_name }}</label>
                                        <p class="mb-0">
                                            {{ $app_data->description }}
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </a>
                @empty
                    <div class="card">
                        <div class="card-body">
                            <div class="d-flex justify-content-between">
                                <div>
                                    <div class="mt-2">
                                        <h2 class="counter" style="visibility: visible;">
                                            NO APP CREATED
                                        </h2>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforelse
            </div>
        </div>
        <div class="col-md-4">
            <div class="card">
                <div class="card-header m-2 p-2">
                    <label for="" class="fw-bolder text-primary">CREATE DEPLOYMENT APPLICATION</label>
                </div>
                <div class="card-body m-2 p-2">
                    <form action="{{ route('admin.upload-mobile-application') }}" method="post"
                        enctype="multipart/form-data">
                        @csrf
                        <div class="form-group">
                            <small class="fw-bolder text-muted">APP NAME</small>
                            <input type="text" class="form-control form-control-sm border border-primary"
                                name="app_name">
                            @error('app_name')
                                <span class="badge bg-danger">{{ $message }}</span>
                            @enderror
                        </div>
                        <div class="form-group">
                            <small class="fw-bolder text-muted">APP DESCRIPTION</small>
                            <input type="text" class="form-control form-control-sm border border-primary"
                                name="app_description">
                            @error('app_description')
                                <span class="badge bg-danger">{{ $message }}</span>
                            @enderror
                        </div>
                        <div class="form-group">
                            <small class="fw-bolder text-muted">APP LOGO</small>
                            <input type="file" class="form-control form-control-sm border border-primary"
                                name="app_file">
                            @error('app_file')
                                <span class="badge bg-danger">{{ $message }}</span>
                            @enderror
                        </div>
                        <div class="form-group">
                            <button type="submit" class="btn btn btn-primary btn-sm w-100">CREATE APPLICATION</button>
                        </div>
                    </form>
                </div>
            </div>


        </div>
    </div>
</div>
