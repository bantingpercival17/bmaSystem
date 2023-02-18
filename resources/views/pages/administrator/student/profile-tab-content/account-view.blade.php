<div class="card">
    <div class="card-header pb-0 p-3">
        <div class="float-end mb-3">
            <a href="{{ route('admin.student-reset-password') }}?_student={{ base64_encode($_student->id) }}" class="btn btn-primary btn-sm">RESET PASSWORD</a>
        </div>
        <h5 class="mb-1"><b>ACCOUNT SETTING</b></h5>

    </div>
    <div class="card-body">

        <label for="" class="fw-bolder text-muted h6">ACCOUNT LIST</label>
        <button class="btn btn-sm btn-primary float-end" data-bs-toggle="modal" data-bs-target=".model-add-account">ADD ACCOUNT</button>
        <div class="account-content">
            @if ($_student->account)
            @foreach ($_student->account_list as $item)
            <div class="account-list row">
                <div class="col-md-3">
                    <small class="fw-bolder">
                        ACCOUNT STAT.
                    </small> <br>
                    @if ($item->is_actived == 1)
                    <label for="" class="text-primary fw-bolder">
                        ACTIVE
                    </label>
                    @else
                    <label for="" class="text-danger fw-bolder">
                        DEACTIVE
                    </label>
                    @endif
                </div>
                <div class="col-md-3">
                    <small class="fw-bolder">
                        STUDENT NO.
                    </small> <br>
                    <label for="" class="text-primary fw-bolder">
                        {{ $item->student_number }}
                    </label>
                </div>
                <div class="col-md-6">
                    <small class="fw-bolder">
                        CAMPUS EMAIL
                    </small> <br>
                    <label for="" class="text-primary fw-bolder">
                        {{ $item->email }}
                    </label>
                </div>
                <div class="col-md">
                    <small class="fw-bolder">
                        PERSONAL EMAIL
                    </small> <br>
                    <label for="" class="text-primary fw-bolder">
                        {{ $item->personal_email }}
                    </label>
                </div>
            </div>
            <hr>
            @endforeach
            {{-- {{ $_student->account_list }} --}}
            @else
            <div class="account-list row">
                <label for="" class="fw-bolder text-muted">NO STUDENT ACCOUNT</label>
            </div>
            @endif
        </div>
    </div>
</div>
<div class="modal fade model-add-account" tabindex="-1" role="dialog" aria-labelledby="model-add-accountTitle" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-scrollable modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title modal-title fw-bolder text-primary" id="model-add-accountTitle">ADD
                    ACCOUNT
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">
                </button>
            </div>
            <div class="modal-body">
                <form role="form" action="{{ route('admin.store-student-account') }}" method="POST" id="modal-form-add">
                    @csrf
                    <input type="hidden" name="student" value="{{ base64_encode($_student->id) }}">
                    <div class="form-group">
                        <div class="row">
                            <div class="col-md">
                                <small class="fw-bolder">
                                    STUDENT NUMBER
                                </small> <br>
                                <input type="text" class="form-control" name="student_number">
                            </div>
                            <div class="col-md">
                                <small class="fw-bolder">
                                    PERSONAL EMAIL
                                </small> <br>
                                <input type="text" class="form-control" name="personal_email">
                            </div>
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-primary btn-sm btn-modal-form" data-form="modal-form-add">ADD</button>
            </div>
        </div>
    </div>
</div>