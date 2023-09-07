@extends('layouts.app-main')
@section('page-title', $_section->section_name)
@section('beardcrumb-content')
    <ol class="breadcrumb ">
        <li class="breadcrumb-item"><a href="/">Dashboard</a></li>
        <li class="breadcrumb-item"><a href="/administrator/classes">Section</a></li>
        @if (request()->input('_add'))
            <li class="breadcrumb-item"><a
                    href="/administrator/classes/section?_cs={{ Crypt::encrypt($_section->id) }}">{{ $_section->section_name }}</a>
            </li>
            <li class="breadcrumb-item active">Add Students</li>
        @else
            <li class="breadcrumb-item active">{{ $_section->section_name }}</li>
        @endif

    </ol>
@endsection
@section('page-content')
    @if (request()->input('_add') == 'true')
        <div class="card">
            <div class="card-header">
                <h3 class="card-title"><span class="text-muted"><b>ADD STUDENTS</b></span></h3>
                <div class="card-tools">
                    <form action="/administrator/classes/section" method="get">
                        <input type="hidden" name="_cs" value="{{ Crypt::encrypt($_section->id) }}">
                        <input type="hidden" name="_add" value="true">
                        <div class="input-group input-group-sm" style="width: 250px;">
                            <input type="text" name="_student" class="form-control float-right"
                                placeholder="e.i Last name, First name">

                            <div class="input-group-append">
                                <span type="submit" class="btn btn-default">
                                    <i class="fas fa-search"></i>
                                </span>
                            </div>
                        </div>
                    </form>

                </div>
            </div>
            <div class="card-body table-responsive p-0">
                <table class="table table-head-fixed ">
                    <thead>
                        <tr>
                            <th>STUDENT NO</th>
                            <th>FULL NAME</th>
                            <th>YEAR LEVEL</th>
                            <th>ACTION</th>
                        </tr>
                    </thead>
                    <tbody>
                        @if ($_add_students->count() > 0)
                            @foreach ($_add_students as $_data)
                                <tr>
                                    <td>{{ $_data->account->student_number }}</td>
                                    <td>{{ strtoupper($_data->last_name . ', ' . $_data->first_name) }}</td>
                                    <td>{{ $_data->year_level . '/C' }}</td>
                                    <td>
                                        @if ($_student_section = $_data->section($_section->academic_id)->first())
                                            <span
                                                class="badge badge-success">{{ $_student_section->section->section_name }}</span>
                                        @else
                                            <a href="/administrator/classes/section/add?_cs={{ Crypt::encrypt($_section->id) }}&_student={{ Crypt::encrypt($_data->id) }}"
                                                class="btn btn-info">ADD</a>
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        @else
                            <tr>
                                <td colspan="4">NO STUDENT ADDED</td>
                            </tr>
                        @endif

                    </tbody>
                    <tfoot>
                        <tr>
                            <td colspan="4"><b>TOTAL STUDENTS: </b> {{ $_add_students->count() }}</td>
                        </tr>
                    </tfoot>
                </table>
            </div>
        </div>
    @else
        <div class="card">
            <div class="card-header">
                <h3 class="card-title"><span class="text-muted"><b>STUDENT LISTS</b></span></h3>
                <div class="card-tools">
                    <div class="row">
                        <form action="/administrator/classes/section?" class="col-md" method="get">
                            <div class="form-group ">
                                <input type="text" class="form-control" placeholder="e.i. Juan, Carlos" name="_student">
                                <input type="hidden" name="_cs" value="{{ Crypt::encrypt($_section->id) }}">
                            </div>
                        </form>
                        <div class="col-md">
                            <a href="/administrator/classes/section?_cs={{ Crypt::encrypt($_section->id) }}&_add=true"
                                class="btn btn-info"><i class="fa fa-users"></i>
                                Add Student</a>
                        </div>
                    </div>
                </div>
            </div>
            <div class="card-body table-responsive p-0">
                <table class="table table-head-fixed text-nowrap">
                    <thead>
                        <tr class="text-center">
                            <th>STUDENT NO</th>
                            <th>FULL NAME</th>
                            <th>ACTION</th>
                        </tr>
                    </thead>
                    <tbody>
                        @if ($_students->count() > 0)
                            @foreach ($_students as $_data)
                                <tr>
                                    <td>{{ $_data->account->student_number }}</td>
                                    <td>{{ strtoupper($_data->last_name . ', ' . $_data->first_name) }}</td>
                                    <td>
                                        @if ($_student_section = $_data->section($_section->academic_id)->first())
                                            
                                            <a href="/administrator/classes/section/remove?_cs={{ Crypt::encrypt($_student_section->id) }}&_student={{ Crypt::encrypt($_data->id) }}"
                                                class="btn btn-danger">REMOVE</a>
                                        @else
                                            <a href="/administrator/classes/section/add?_cs={{ Crypt::encrypt($_section->id) }}&_student={{ Crypt::encrypt($_data->id) }}"
                                                class="btn btn-info">ADD</a>
                                        @endif

                                    </td>
                                </tr>
                            @endforeach
                        @else
                            <tr>
                                <td colspan="4">NO STUDENT ADDED</td>
                            </tr>
                        @endif

                    </tbody>
                    <tfoot>
                        <tr>
                            <td colspan="4"><b>TOTAL STUDENTS: </b> {{ $_students->count() }}</td>
                        </tr>
                    </tfoot>
                </table>
            </div>
        </div>
    @endif

@endsection
