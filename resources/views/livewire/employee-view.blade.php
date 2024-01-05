@php
    $pageTitle = 'Employee Information';
@endphp
@section('page-title', $pageTitle)
<div>
    <div class="row">
        <div class="col-lg-8">
            <p class="display-6 fw-bolder text-primary">{{ strtoupper($pageTitle) }}</p>
            <div class="card mb-2">
                <div class="row no-gutters">
                    <div class="col-md-3">
                        <img src="{{ $employee ? asset($employee->profile_picture()) : asset('/assets/img/staff/avatar.png') }}"
                            class="card-img" alt="#">
                    </div>
                    <div class="col-md ps-0">
                        <div class="card-body p-3 me-2">
                            <label for=""
                                class="fw-bolder text-primary h4">{{ $employee ? strtoupper($employee->last_name . ', ' . $employee->first_name) : 'EMPLOYEE NAME' }}</label>
                            <p class="mb-0">
                                <small class="fw-bolder badge bg-secondary">
                                    {{ $employee ? $employee->department . ' DEPARTMENT' : 'DEPARTMENT' }}
                                </small> -
                                <small class="badge bg-secondary">
                                    {{ $employee ? $employee->user->email : 'EMAIL' }}
                                </small>
                                @if ($employee)
                                    <div class="mt-2">
                                        <a href="{{ route('admin.staff-qrcode') }}?employee={{ base64_encode($employee->id) }}"
                                            class="badge bg-primary">GENERATE QR-CODE</a>
                                        <span wire:click="uploadPicture" class="badge bg-info">UPLOAD PICTURE</span>
                                    </div>
                                    @if ($uploadPictureForm)
                                        <form wire:submit.prevent="imageUpload" enctype="multipart/form-data">
                                            <div class="form-group">
                                                <small class="text-muted fw-bolder">Upload Image</small>
                                                <input type="file" name="file" wire:model="image"
                                                    class="form-control form-control-sm border border-primary">
                                            </div>
                                            <button type="submit"
                                                class="btn btn-primary btn-sm float-end">UPLOAD</button>
                                            @error('image')
                                                <small class="badge bg-danger">{{ $message }}</small>
                                            @enderror
                                        </form>
                                    @endif
                                @endif
                            </p>
                        </div>
                    </div>
                </div>
            </div>
            @if ($employee)
                <nav class="nav nav-underline bg-soft-primary pb-0 text-center" aria-label="Secondary navigation">
                    <div class="d-flex" id="head-check">
                        <a class="nav-link {{ $activeCard == 'profile' || !$activeCard ? 'active' : 'text-muted' }}"
                            wire:click="switchCard('profile')">PROFILE</a>
                        <a class="nav-link  {{ $activeCard == 'role' ? 'active' : 'text-muted' }}"
                            wire:click="switchCard('role')">ROLE</a>
                        <a class="nav-link   {{ $activeCard == 'attendance' ? 'active' : 'text-muted' }}"
                            wire:click="switchCard('attendance')">ATTENDANCE</a>
                    </div>
                </nav>

                <div class="mt-4">
                    @if ($activeCard == 'profile')
                        @include('livewire.employee-components.profile')
                    @elseif ($activeCard == 'role')
                        @include('livewire.employee-components.role-view')
                    @endif
                </div>
            @endif

        </div>
        <div class="col-lg-4">
            <form action="" method="get" class="form-group">
                <label for="" class="text-primary fw-bolder">SEARCH STUDENT</label>
                <input type="text" class="form-control border border-primary" wire:model="searchInput">
                <div class=" d-flex justify-content-between mt-2">
                    <h6 class=" fw-bolder text-muted">
                        @if ($searchInput != '')
                            Search Result: <span class="text-primary">{{ $searchInput }}</span>
                        @else
                            {{ strtoupper('Recent Search') }}
                        @endif
                    </h6>
                    <span class="text-muted h6">
                        No. Result: <b>{{ count($employeeList) }}</b>
                    </span>

                </div>
            </form>
            <div class="employee-list">
                @forelse ($employeeList as $item)
                    <a href="{{ route('employee.view') }}?employee={{ base64_encode($item->id) }}">
                        <div class="mb-2 ">
                            <div class="row no-gutters">
                                <div class="col-md-4 text-center">
                                    <img src="{{ $item ? $item->profile_picture() : 'http://bma.edu.ph/img/student-picture/midship-man.jpg' }}"
                                        class="avatar-100 rounded card-img" alt="student-image">
                                </div>
                                <div class="col-md-8">
                                    <div class="card shadow shadow-info">
                                        <div class="card-body">
                                            <small
                                                class="text-primary fw-bolder">{{ strtoupper($item->last_name . ', ' . $item->first_name) }}</small>
                                            <br>
                                            <span class="badge bg-secondary">{{ $item->department }}</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </a>
                @empty
                    <div class="card  mb-2">
                        <div class="row no-gutters">
                            <div class="col-md">
                                <div class="card-body">
                                    <span class="fw-bolder">NOT FOUND</span>

                                </div>
                            </div>
                        </div>
                    </div>
                @endforelse
            </div>

        </div>

    </div>
</div>
