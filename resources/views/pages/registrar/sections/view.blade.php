@extends('layouts.app-main')
@section('page-title', 'Sections')
@section('beardcrumb-content')
    <li class="breadcrumb-item active" aria-current="page">
        <svg width="14" height="14" class="me-2" viewBox="0 0 22 22" fill="none"
            xmlns="http://www.w3.org/2000/svg">
            <path
                d="M8.15722 19.7714V16.7047C8.1572 15.9246 8.79312 15.2908 9.58101 15.2856H12.4671C13.2587 15.2856 13.9005 15.9209 13.9005 16.7047V16.7047V19.7809C13.9003 20.4432 14.4343 20.9845 15.103 21H17.0271C18.9451 21 20.5 19.4607 20.5 17.5618V17.5618V8.83784C20.4898 8.09083 20.1355 7.38935 19.538 6.93303L12.9577 1.6853C11.8049 0.771566 10.1662 0.771566 9.01342 1.6853L2.46203 6.94256C1.86226 7.39702 1.50739 8.09967 1.5 8.84736V17.5618C1.5 19.4607 3.05488 21 4.97291 21H6.89696C7.58235 21 8.13797 20.4499 8.13797 19.7714V19.7714"
                stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"></path>
        </svg>Sections
    </li>
@endsection
@section('page-content')

    @foreach ($_courses as $_course)
        @php
            $_year_level = $_course->id == 3 ? ['GRADE 11', 'GRADE 12'] : ['4/C', '3/C', '2/C', '1/C'];
            $academic = Auth::user()->staff->current_academic();
        @endphp
        <div class="card">
            <div class="card-header d-flex justify-content-between">
                <div class="header-title">
                    <h4 class="card-title">{{ $_course->course_name }}</h4>
                </div>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive mt-4">
                    <table id="basic-table" class="table table-striped mb-0" role="grid">
                        <thead>
                            <tr>
                                <th>Year Level</th>
                                <th>Sections</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($_year_level as $level)
                                <tr>
                                    <td>{{ $level }}</td>
                                    <td>
                                        if
                                        {{ $_course->section([Auth::user()->staff->current_academic()->id,$level])->get()}}
                                      {{--   @if (count(
                                        $_course->section([Auth::user()->staff->current_academic()->id,$level])->get()) > 0)
                                        {{ $_course->section([Auth::user()->staff->current_academic()->get()]) }}
                                    @else
                                        <span class="badge  bg-info">No Section</span>
                            @endif
 --}}
                            </td>
                            </tr>
    @endforeach

    </tbody>
    </table>
    </div>
    </div>
    </div>
    @endforeach
    {{-- @foreach ($_academic as $academic)
        <p>
            <label for="" class="{{ $academic->is_active == 1 ? 'text-success' : 'text-muted' }} h4">|
                {{ $academic->school_year . ' - ' . strtoupper($academic->semester) }}</label>
        </p>
        @foreach ($_course as $_course)
            @php
                $_year_level = $_course->id == 3 ? ['GRADE 11', 'GRADE 12'] : ['4/C', '3/C', '2/C', '1/C'];
            @endphp
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title"><label for="" class="text-info">{{ $_course->course_name }}</label>
                    </h3>
                    <br>
                </div>
                <!-- ./card-header -->
                <div class="card-body p-0">
                    <table class="table table-hover">
                        <tbody>
                            @foreach ($_year_level as $_level)
                                <tr data-widget="expandable-table" aria-expanded="false">
                                    <td class="text-muted">
                                        <b>{{ $_course->id == 3 ? 'GRADE ' . $_level : $_level . ' CLASS' }}</b>
                                        <div class="float-right">
                                            <a class="btn btn-success" target="_blank"
                                                href="/administrator/classes/section/report?_c={{ Crypt::encrypt($_course->id) }}&_a={{ Crypt::encrypt($academic->id) }}&_l={{ Crypt::encrypt($_level) }}">
                                                <i class="fa fa-print"></i>
                                            </a>
                                        </div>
                                    </td>
                                </tr>
                                <tr class="expandable-body">
                                    <td>
                                        <div class="row">
                                            @if ($_course->section([$academic->id, $_level])->count())
                                                @foreach ($_course->section([$academic->id, $_level])->get() as $section)
                                                    <div class="col-md-3">

                                                        <div class="callout callout-success">
                                                            <div class="float-right">
                                                                <a href="/registrar/sections/removed?s={{ base64_encode($section->id) }}"
                                                                    class="btn btn-xs"><i
                                                                        class="fa fa-minus text-danger"></i></a>
                                                            </div>
                                                            <a
                                                                href="/registrar/sections/view?c={{ base64_encode($section->id) }}">
                                                                <p class="text-info">
                                                                    <b>{{ $section->section_name }}</b>
                                                                </p>
                                                            </a>
                                                        </div>


                                                    </div>
                                                @endforeach
                                            @endif
                                            <div class="col-md-4">
                                                <form action="/registrar/sections" method="post"
                                                    class="callout callout-info">
                                                    @csrf
                                                    <label for="" class="text-muted">Section Name</label>
                                                    <div class="form-group row">
                                                        <input type="hidden" name="_level" value="{{ $_level }}">
                                                        <input type="hidden" name="_course" value="{{ $_course->id }}">
                                                        <input type="hidden" name="_academic"
                                                            value="{{ $academic->id }}">
                                                        <div class="col-md-9">
                                                            <select name="_section" class="form-control">
                                                                <option value="ALPHA">APLHA</option>
                                                                <option value="BRAVO">BRAVO</option>
                                                                <option value="CHARLIE">CHARLIE</option>
                                                                <option value="CURR 2006">CURR 2006
                                                                </option>
                                                                <option value="CURR 2013">CURR 2013
                                                                </option>
                                                                <option value="CURR 2015">CURR 2015
                                                                </option>
                                                                <option value="CURR 2018">CURR 2018
                                                                </option>
                                                            </select>
                                                        </div>
                                                        <div class="col-md">
                                                            <button type="submit" class="btn btn-success xs"> <i
                                                                    class="fa fa-plus"></i>
                                                            </button>
                                                        </div>
                                                    </div>
                                                </form>
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>

        @endforeach
    @endforeach --}}

@endsection
