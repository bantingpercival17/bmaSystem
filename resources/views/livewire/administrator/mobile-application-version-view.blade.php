@php
    $pageTitle = 'Mobile Application View';
@endphp
@section('page-title', $pageTitle)
<div>
    <div class="row">
        <div class="col-md-8">
            <p class="display-6 fw-bolder text-primary">{{ strtoupper($pageTitle) }}</p>
            <div class="content-page">
                <div class="card">
                    <div class="row no-gutters">
                        <div class="col-md-3">
                            <img src="{{ $application ? $application->app_logo_path : asset('/assets/img/staff/avatar.png') }}"
                                class="card-img" alt="#">
                        </div>
                        <div class="col-md ps-0">
                            <div class="card-body p-3 me-2">
                                <label for=""
                                    class="fw-bolder text-primary h4">{{ $application ? strtoupper($application->app_name) : 'APP NAME' }}</label>
                                <p class="mb-0">
                                    {{ $application ? $application->description : 'Application Description' }}
                                </p>
                            </div>
                        </div>
                    </div>
                </div>

                @if ($application)
                    <div class="card p-2">
                        <div class="card-header p-2">
                            <h4 class="text-primary fw-bolder">
                                APPLICATION VERSIONS
                            </h4>
                        </div>
                        <div class="card-body p-2">
                            <div class="table-responsive">
                                <table class="table">
                                    <thead>
                                        <tr>
                                            <th>VERSION NAME</th>
                                            <th>TOTAL DOWNLOADS</th>
                                            <th>ACTIONS</th>
                                        </tr>

                                    </thead>
                                    <tbody>
                                        @forelse ($application->version_list as $version)
                                            <tr>
                                                <td>{{ $version->version_name }}</td>
                                                <td>{{ count($version->version_downloads) }}</td>
                                                <td></td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <th colspan="3">NO APPLICATION</th>
                                            </tr>
                                        @endforelse
                                    </tbody>

                                </table>
                            </div>

                        </div>
                    </div>
                @endif
            </div>
        </div>
        <div class="col-md-4">
            <div class="card">
                <div class="card-header m-2 p-2">
                    <label for="" class="fw-bolder text-primary">UPLOAD APPLICATION VERSION</label>
                </div>
                <div class="card-body m-2 p-2">
                    <form action="{{ route('admin.upload-mobile-app') }}" method="post" enctype="multipart/form-data">
                        @csrf
                        <input type="hidden" name="app" value="{{ base64_encode($application->id) }}">
                        <div class="form-group">
                            <small class="fw-bolder text-muted">VERSION NAME</small>
                            <input type="text" class="form-control form-control-sm border border-primary"
                                name="app_name">
                            @error('app_name')
                                <span class="badge bg-danger">{{ $message }}</span>
                            @enderror
                        </div>
                        <div class="form-group">
                            <small class="fw-bolder text-muted">VERSION DESCRIPTION</small>
                            <input type="text" class="form-control form-control-sm border border-primary"
                                name="app_description">
                            @error('app_description')
                                <span class="badge bg-danger">{{ $message }}</span>
                            @enderror
                        </div>
                        <div class="form-group">
                            <small class="fw-bolder text-muted">ATTACH FILE</small>
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
