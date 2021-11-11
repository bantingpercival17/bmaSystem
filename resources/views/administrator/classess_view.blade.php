@extends('app')
@section('page-title', 'Section')
@section('page-navigation')
    <ol class="breadcrumb ">
        <li class="breadcrumb-item"><a href="/">Dashboard</a></li>
        <li class="breadcrumb-item active">Section</li>
    </ol>
@endsection
@section('page-content')
    @foreach ($_academic as $academic)
        <p>
            <label for="" class="text-muted h4">|
                {{ $academic->school_year . ' - ' . strtoupper($academic->semester) }}</label>
        </p>
        <div class="row">
            @foreach ($_course as $course)
                @php
                    $_year_level = $course->id == 3 ? ['GRADE 11', 'GRADE 12'] : ['4/C', '3/C', '2/C', '1/C'];
                @endphp
                <div class="col-md">
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title"><label for=""
                                    class="text-info">{{ $course->course_name }}</label></h3>
                            <br>
                            <form action="/administrator/classes/student-section-import" method="post"
                                enctype="multipart/form-data">
                                @csrf
                                <input type="hidden" name="_data_1" value="{{ Crypt::encrypt($academic->id) }}">
                                <input type="hidden" name="_data_2" value="{{ Crypt::encrypt($course->id) }}">
                                <div class="form-group">
                                    <label for="" class="text-success"><b>| IMPORT SECTIONS</b></label>
                                    <div class="row">
                                        <div class="col-md">
                                            <div class="custom-file form-control-xs">
                                                <input type="file" class="custom-file-input" id="customFile" name="_file"
                                                    required>
                                                <label class="custom-file-label" for="customFile">Choose file</label>
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <button type="submit" class="btn btn-info btn-xs btn-block">IMPORT</button>
                                        </div>
                                    </div>

                                </div>
                            </form>
                        </div>
                        <!-- ./card-header -->
                        <div class="card-body p-0">
                            <table class="table table-hover">
                                <tbody>
                                    @foreach ($_year_level as $_level)
                                        <tr data-widget="expandable-table" aria-expanded="false">
                                            <td class="text-muted">
                                                <b>{{ $course->id == 3 ? 'GRADE ' . $_level : $_level . ' CLASS' }}</b>
                                                <div class="float-right">
                                                    <a class="btn btn-success" target="_blank"
                                                        href="/administrator/classes/section/report?_c={{ Crypt::encrypt($course->id) }}&_a={{ Crypt::encrypt($academic->id) }}&_l={{ Crypt::encrypt($_level) }}">
                                                        <i class="fa fa-print"></i>
                                                    </a>
                                                </div>
                                            </td>
                                        </tr>
                                        <tr class="expandable-body">
                                            <td>
                                                <div class="p-0">
                                                    <table class="table table-hover">
                                                        <tbody>
                                                            @if ($course->section([$academic->id, $_level])->count())
                                                                @foreach ($course->section([$academic->id, $_level])->get() as $section)
                                                                    <tr>
                                                                        <td>
                                                                            <a
                                                                                href="/administrator/classes/section?_cs={{ Crypt::encrypt($section->id) }}">
                                                                                {{ $section->section_name }}
                                                                            </a>

                                                                        </td>
                                                                    </tr>
                                                                @endforeach
                                                            @endif
                                                            <tr>
                                                                <td>
                                                                    <form action="/administrator/classes" method="post">
                                                                        @csrf
                                                                        <div class="form-group row">
                                                                            <input type="hidden" name="_level"
                                                                                value="{{ $_level }}">
                                                                            <input type="hidden" name="_course"
                                                                                value="{{ $course->id }}">
                                                                            <input type="hidden" name="_academic"
                                                                                value="{{ $academic->id }}">
                                                                            <div class="col-md-9">
                                                                                <select name="_section"
                                                                                    class="form-control">
                                                                                    <option value="ALPHA">APLHA</option>
                                                                                    <option value="BRAVO">BRAVO</option>
                                                                                    <option value="CHARLIE">CHARLIE</option>
                                                                                    <option value="CURR 2006">CURR 2006</option>
                                                                                    <option value="CURR 2013">CURR 2013</option>
                                                                                    <option value="CURR 2015">CURR 2015</option>
                                                                                    <option value="CURR 2018">CURR 2018</option>
                                                                                </select>
                                                                            </div>
                                                                            <div class="col-md">
                                                                                <button type="submit"
                                                                                    class="btn btn-success xs"> <i
                                                                                        class="fa fa-plus"></i>
                                                                                </button>
                                                                            </div>
                                                                        </div>
                                                                    </form>
                                                                </td>

                                                            </tr>

                                                        </tbody>
                                                    </table>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

            @endforeach
        </div>
    @endforeach

@endsection
