@extends('app')
{{-- @section('page-mode', 'dark-mode') --}}
@section('page-title', 'Attendances')
@section('css')
    <link rel="stylesheet" href="{{ asset('assets/plugins/bs-stepper/css/bs-stepper.min.css') }}">
@endsection
@section('js')
    <!-- BS-Stepper -->
    <script src="{{ asset('assets/plugins/bs-stepper/js/bs-stepper.min.js') }}"></script>
    <script>
        // BS-Stepper Init
        document.addEventListener('DOMContentLoaded', function() {
            window.stepper = new Stepper(document.querySelector('.bs-stepper'))
        })
    </script>
@endsection
@section('page-content')
    <div class="container">

        <div class="row">
            <div class="col-md-12 col-sm-12">
                <div class="card">
                    <div class="card-body text-center">
                        {!! QrCode::size(300)->generate($_data) !!}
                        <label for="">11-22-33</label>
                    </div>
                </div>

            </div>
            <div class="col-md-6">
            </div>
        </div>

    </div>
@endsection
