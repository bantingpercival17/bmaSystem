@php
    $pageTitle = 'Add Employee';
@endphp
@section('page-title', $pageTitle)
<div>
    <div class="row">
        <div class="col-lg-12">
            <p class="display-6 fw-bolder text-primary">{{ strtoupper($pageTitle) }}</p>
            <div class="card mb-2">
                <div class="card-header pb-0 p-3">
                    <h5 class="mb-1 text-info"><b>EMPLOYEE INFORMATION</b></h5>
                </div>
                <div class="card-body">
                    <form wire:submit.prevent="storeEmployee" method="post">
                        @if ($errorMessage != null)
                            <span class="badge bg-danger">{{ $errorMessage }}</span>
                        @endif
                        <div class="row">
                            <div class="col-md-4 form-group">
                                <small class="text-muted fw-bolder">LAST NAME</small>
                                <input type="text" wire:model="employee.last_name"
                                    class="form-control form-control-sm border border-primary">
                                @error('employee.last_name')
                                    <span class="badge bg-danger mt-2">{{ $message }}</span>
                                @enderror
                            </div>
                            <div class="col-md-4 form-group">
                                <small class="text-muted fw-bolder">FIRST NAME</small>
                                <input type="text" wire:model="employee.first_name"
                                    class="form-control form-control-sm border border-primary">
                                @error('employee.first_name')
                                    <span class="badge bg-danger mt-2">{{ $message }}</span>
                                @enderror
                            </div>
                            <div class="col-md-4 form-group">
                                <small class="text-muted fw-bolder">MIDDLE NAME</small>
                                <input type="text" wire:model="employee.middle_name"
                                    class="form-control form-control-sm border border-primary">
                                @error('employee.middle_name')
                                    <span class="badge bg-danger mt-2">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>
                        <div class="row">
                            <div class="form-group col-lg-6">
                                <small class="fw-bolder text-primary">DEPARTMENT</small>
                                <select wire:model="employee.department" name="department" id=""
                                    class="form-select form-select-sm border border-primary">
                                    <option disabled>SELECT DEPARTMENT</option>
                                    @forelse ($departmentList as $item)
                                        <option value="{{ $item->id }}">{{ $item->name }}</option>
                                    @empty
                                    @endforelse
                                </select>
                                @error('employee.last_name')
                                    <span class="badge bg-danger mt-2">{{ $message }}</span>
                                @enderror
                            </div>
                            <div class="form-group col-lg-6">
                                <small class="fw-bolder text-primary">ROLE MODULE</small>
                                <select wire:model="employee.role" name="role" id=""
                                    class="form-select form-select-sm border border-primary">
                                    <option disabled>SELECT ROLE</option>
                                    @forelse ($roles as $item)
                                        <option value="{{ $item->id }}">{{ ucwords($item->name) }}</option>
                                    @empty
                                    @endforelse
                                </select>
                                @error('employee.role')
                                    <span class="badge bg-danger mt-2">{{ $message }}</span>
                                @enderror
                            </div>
                            <div class="form-group col-lg-6">
                                <small class="fw-bolder text-primary">POSITION</small>
                                <input wire:model="employee.position" name="position" type="text"
                                    class="form-control form-control-sm border border-primary">
                                @error('employee.position')
                                    <span class="badge bg-danger mt-2">{{ $message }}</span>
                                @enderror
                            </div>
                            <div class="form-group col-lg-6">
                                <small class="fw-bolder text-primary">STATUS ROLE</small>
                                <select wire:model="employee.status" name="status" id=""
                                    class="form-select form-select-sm border border-primary">
                                    <option disabled>SELECT STATUS</option>
                                    <option value="1">MAIN ROLE</option>
                                    <option value="0">SUB ROLE</option>
                                </select>
                                @error('employee.status')
                                    <span class="badge bg-danger mt-2">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>
                        <button type="submit" class="btn btn-sm mt-2 btn-primary w-100">SUBMIT</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
