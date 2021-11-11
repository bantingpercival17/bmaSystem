@extends('app')
@section('page-title', 'Setup')
@section('css')
    <!-- Toastr -->
    <link rel="stylesheet" href="{{ asset('assets/plugins/toastr/toastr.min.css') }}">
@endsection
@section('js')
    <!-- Toastr -->
    <script src="{{ asset('assets/plugins/toastr/toastr.min.js') }}"></script>
    <script src="{{ asset('assets/plugins/bs-custom-file-input/bs-custom-file-input.min.js') }}"></script>
    <script>
        @if (Session::has('message'))
            toastr.success("{{ session('message') }}")
        @endif
        $(function() {
            bsCustomFileInput.init();
        });
    </script>

@endsection
@section('page-content')
    <div class="hold-transition login-page">
        <div class="container">
            <div class="text-center d-flex align-items-center justify-content-center">
                <h2 class="text-info"><b>BMA SYSTEM SETUP</b></h2>
            </div>
        </div>
        <div class="card">
            <div class="card-body">
                @if (!$_course->count() > 0)
                    <form action="/setup" method="post" enctype="multipart/form-data">
                        @csrf
                        <div class="form-group">
                            <label for="" class="text-success"><b>| COURSE</b></label>
                            {{-- <input type="file" class="form-control" name="_file_course"> --}}
                            <div class="custom-file">
                                <input type="file" class="custom-file-input" id="customFile" name="_file_course">
                                <label class="custom-file-label" for="customFile">Choose file</label>
                            </div>
                        </div>
                        <div class="form-group float-right">
                            <button type="submit" class="btn btn-success">NEXT</button>
                        </div>
                    </form>
                @endif
                @if ($_course->count() > 0 && !$_academic->count() > 0)
                    <form action="/setup" method="post" enctype="multipart/form-data">
                        @csrf
                        <div class="form-group">
                            <label for="" class="text-success"><b>| ACADEMIC YEARS</b></label>
                            <div class="custom-file">
                                <input type="file" class="custom-file-input" id="customFile" name="_file_academic">
                                <label class="custom-file-label" for="customFile">Choose file</label>
                            </div>
                        </div>
                        <div class="form-group float-right">
                            <button type="submit" class="btn btn-success">NEXT</button>
                        </div>
                    </form>
                @endif
                @if ($_course->count() > 0 && $_academic->count() > 0 && !$_curriculum->count() > 0)
                    <form action="/setup" method="post" enctype="multipart/form-data">
                        @csrf
                        <div class="form-group">
                            <label for="" class="text-success"><b>| CURRICULUM</b></label>
                            <div class="custom-file">
                                <input type="file" class="custom-file-input" id="customFile" name="_file_curriculum">
                                <label class="custom-file-label" for="customFile">Choose file</label>
                            </div>
                        </div>
                        <div class="form-group float-right">
                            <button type="submit" class="btn btn-success">NEXT</button>
                        </div>
                    </form>
                @endif
                @if ($_course->count() > 0 && $_academic->count() > 0 && $_curriculum->count() > 0 && !$_subject->count() > 0)
                    <form action="/setup" method="post" enctype="multipart/form-data">
                        @csrf
                        <div class="form-group">
                            <label for="" class="text-success"><b>| SUBJECTS</b></label>
                            <div class="custom-file">
                                <input type="file" class="custom-file-input" id="customFile" name="_file_subjects">
                                <label class="custom-file-label" for="customFile">Choose file</label>
                            </div>
                        </div>
                        <div class="form-group float-right">
                            <button type="submit" class="btn btn-success">NEXT</button>
                        </div>
                    </form>
                @endif
                @if ($_course->count() > 0 && $_academic->count() > 0 && $_curriculum->count() > 0 && $_subject->count() > 0 && !$_user->count() > 0)
                    <form action="/setup" method="post" enctype="multipart/form-data">
                        @csrf
                        <div class="form-group">
                            <label for="" class="text-success"><b>| USERS</b></label>
                            <div class="custom-file">
                                <input type="file" class="custom-file-input" id="customFile" name="_file_users">
                                <label class="custom-file-label" for="customFile">Choose file</label>
                            </div>
                        </div>
                        <div class="form-group float-right">
                            <button type="submit" class="btn btn-success">FINISH</button>
                        </div>
                    </form>
                @endif
            </div>
        </div>
    </div>
@endsection
