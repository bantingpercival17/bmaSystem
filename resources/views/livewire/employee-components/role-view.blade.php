<div class="card">
    <div class="card-header  pb-0 p-3">
        <span class="fw-bolder h5 text-primary">EMPLOYEE MULTI-ROLES</span>
        <a wire:click="addRole" class="badge bg-primary float-end">ADD ROLE</a>
    </div>
    <div class="card-body">
        @if ($formRole)
            <form wire:submit.prevent="storeRole" method="post" class="row">
                <div class="form-group col-lg-6">
                    <small class="fw-bolder text-primary">DEPARTMENT</small>
                    <select wire:model="department" name="department" id=""
                        class="form-select form-select-sm border border-primary">
                        <option disabled>SELECT DEPARTMENT</option>
                        @forelse ($departmentList as $item)
                            <option value="{{ $item->id }}">{{ $item->name }}</option>
                        @empty
                        @endforelse
                    </select>
                </div>
                <div class="form-group col-lg-6">
                    <small class="fw-bolder text-primary">ROLE MODULE</small>
                    <select wire:model="department" name="department" id=""
                        class="form-select form-select-sm border border-primary">
                        <option disabled>SELECT ROLE</option>
                        @forelse ($roles as $item)
                            <option value="{{ $item->id }}">{{ ucwords($item->name) }}</option>
                        @empty
                        @endforelse
                    </select>
                </div>
                <div class="form-group col-lg-6">
                    <small class="fw-bolder text-primary">POSITION</small>
                    <input wire:model="position" name="position" type="text"
                        class="form-control form-control-sm border border-primary">
                </div>
                <div class="form-group col-lg-6">
                    <small class="fw-bolder text-primary">STATUS ROLE</small>
                    <select wire:model="status" name="status" id=""
                        class="form-select form-select-sm border border-primary">
                        <option disabled>SELECT STATUS</option>
                        <option value="1">MAIN ROLE</option>
                        <option value="0">SUB ROLE</option>
                    </select>
                </div>
                <div class="form-group">
                    <button type="submit" class="btn btn-sm btn-primary">SAVE</button>
                </div>

            </form>
        @endif
        <div class="table-responsive">
            <table class="table">
                <thead>
                    <tr>
                        <th>DEPARTMENT</th>
                        <th>POSITION</th>
                        <th>ROLE MODULE</th>
                        <th>STATUS ROLE</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($employee->roles as $item)
                        <tr>
                            <td>{{ $item->department->name }}</td>
                            <td>{{ $item->poistion }}</td>
                            <td>{{ $item->role->role_name }}</td>
                            <td>{{ $item->is_active }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4">No Roles</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>

        </div>
    </div>
</div>
