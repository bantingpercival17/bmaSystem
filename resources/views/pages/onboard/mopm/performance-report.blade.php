@extends('layouts.app-main')
@php
    $_title = 'Monthly Onboard Performance Monitoring';
@endphp
@section('page-title', $_title)
@section('page-navigation')
    <ol class="breadcrumb ">
        <li class="breadcrumb-item"><a href="/">Dashboard</a></li>
        <li class="breadcrumb-item active"> Midshipman</li>

    </ol>
@endsection
@section('beardcrumb-content')
    <li class="breadcrumb-item active" aria-current="page">
        <svg width="14" height="14" class="me-2" viewBox="0 0 22 22" fill="none" xmlns="http://www.w3.org/2000/svg">
            <path
                d="M8.15722 19.7714V16.7047C8.1572 15.9246 8.79312 15.2908 9.58101 15.2856H12.4671C13.2587 15.2856 13.9005 15.9209 13.9005 16.7047V16.7047V19.7809C13.9003 20.4432 14.4343 20.9845 15.103 21H17.0271C18.9451 21 20.5 19.4607 20.5 17.5618V17.5618V8.83784C20.4898 8.09083 20.1355 7.38935 19.538 6.93303L12.9577 1.6853C11.8049 0.771566 10.1662 0.771566 9.01342 1.6853L2.46203 6.94256C1.86226 7.39702 1.50739 8.09967 1.5 8.84736V17.5618C1.5 19.4607 3.05488 21 4.97291 21H6.89696C7.58235 21 8.13797 20.4499 8.13797 19.7714V19.7714"
                stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"></path>
        </svg>{{ $_title }}
    </li>
@endsection
@section('page-content')


    <div class="row">
        <div class="col-md-9">
            <div class="card mb-2">
                <div class="row no-gutters">
                    <div class="col-md col-lg-2">
                        <img src="{{ $midshipman ? $midshipman->profile_pic($midshipman->account) : 'http://bma.edu.ph/img/student-picture/midship-man.jpg' }}"
                            class="card-img " alt="#">
                    </div>
                    <div class="col-md-8 col-lg-8">
                        <div class="card-body">
                            <h4 class="card-title text-primary">
                                <b>{{ $midshipman ? strtoupper($midshipman->last_name . ', ' . $midshipman->first_name) : 'MIDSHIPMAN NAME' }}</b>
                            </h4>
                            <p class="card-text">
                                <span>STUDENT NUMBER: <b>
                                        {{ $midshipman ? $midshipman->account->student_number : '-' }}</b></span>
                                <br>
                                <span>COURE: <b>
                                        {{ $midshipman ? $midshipman->enrollment_assessment->course->course_name : '-' }}</b></span>

                            </p>
                        </div>
                    </div>
                </div>
            </div>
            <div class="card">
                <div class="card-header">
                    <label for="" class="fw-bolder h4 text-primary">{{ $performance_report->month }}</label>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-12 form-group">
                            <small class="fw-bolder text-muted">TASK AS PER TRB</small>
                            <label for="" class="form-control">{{ $performance_report->task_trb }}</label>
                        </div>
                        <div class="col-md-6 form-group">
                            <small class="fw-bolder text-muted">CODE</small>
                            <label for="" class="form-control">{{ $performance_report->trb_code }}</label>
                        </div>
                        <div class="col-md-6 form-group">
                            <small class="fw-bolder text-muted">DATE PREFERRED</small>
                            <label for="" class="form-control">{{ $performance_report->date_preferred }}</label>
                        </div>
                        <div class="col-md-6 form-group">
                            <small class="fw-bolder text-muted">INPUTTED TO DAILY JOURNAL</small>
                            <label for=""
                                class="form-control">{{ $performance_report->daily_journal == 1 ? 'Yes' : 'No' }}</label>
                        </div>
                        <div class="col-md-6 form-group">
                            <small class="fw-bolder text-muted">SIGNED BY OFFICER / MASTER</small>
                            <label for=""
                                class="form-control">{{ $performance_report->have_signature == 1 ? 'Yes' : 'No' }}</label>
                        </div>
                        <div class="col-md-12 form-group">
                            <small class="fw-bolder text-muted">REMARKS IS LEARNING ACQUIRED</small>
                            <label for="" class="form-control">{{ $performance_report->input }}</label>
                        </div>
                    </div>
                </div>
            </div>
            <div class="card">
                <div class="card-header">
                    <label for="" class="fw-bolder text-muted">DOCUMENTS</label>
                </div>
                <div class="card-body">
                    @if (count($performance_report->document_attachments) > 0)
                        @include('layouts.icon-main')
                        @foreach ($performance_report->document_attachments as $data)
                            <div class="form-group">

                                <p class="h6 text-primary">
                                    <b>{{ $data->journal_type }}</b>
                                </p>
                                @if ($data->remark != null)
                                    <small class="form-label"><b>REMARKS<sup class="text-danger">*</sup></b></small>
                                    <label for="" class="form-control">{{ $data->remark }}</label>
                                @endif
                                <div class="form-group">
                                    <small class="form-label"> <b> ATTACH FILES <sup class="text-danger">*</sup></b></small>
                                    <table>
                                        <tbody>
                                            <tr>
                                                @foreach (json_decode($data->file_links) as $links)
                                                    <td>
                                                        <a class="btn-form-document col" data-bs-toggle="modal"
                                                            data-bs-target=".document-view-modal"
                                                            data-document-url="{{ str_replace(':1000', '', $links) }}">
                                                            @php
                                                                $myFile = pathinfo($links);
                                                                $_ext = $myFile['extension'];
                                                                $_file = $myFile['basename'];
                                                                $_file = str_replace('[' . str_replace('@bma.edu.ph', '', $midshipman->account->email) . ']', '', $_file);
                                                            @endphp
                                                            <i>
                                                                @if ($_ext == 'docx' || $_ext == 'doc')
                                                                    @yield('icon-doc')
                                                                @elseif ($_ext == 'pdf')
                                                                    @yield('icon-pdf')
                                                                @elseif ($_ext == 'png')
                                                                    @yield('icon-png')
                                                                @elseif ($_ext == 'jpg' || $_ext == 'jpeg')
                                                                    @yield('icon-jpg')
                                                                @else
                                                                @endif
                                                            </i>
                                                            <br>
                                                            <small>{{ mb_strimwidth($_file, 0, 10, '...') }}</small>

                                                        </a>
                                                    </td>
                                                @endforeach
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                                {{--  <div v-if="task.length > 2">
                                    <small class="form-label"><b>REMARKS<sup class="text-danger">*</sup></b></small>
                                    <label for="" class="form-control">{{ report . remark }}</label>
                                </div>
                                <div class="form-group">
                                    <small class="form-label"> <b> ATTACH FILES <sup class="text-danger">*</sup></b></small>
                                    <table>
                                        <tbody>
                                            <tr>
                                                <td v-for="(document, index) in JSON.parse(report.file_links)"
                                                    :key="index">
                                                    <label for="" data-bs-toggle="modal"
                                                        data-bs-target="#exampleModal" @click="documentViewer(document)">
                                                        <iconComponent :fileType="getFileType(document)" />
                                                    </label>
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                                <div class="document-status">
                                    <div class="mt-2">
                                        <div v-if="report.is_approved">
                                            <div class="row">
                                                <div class="col-lg-4 col-md-12">
                                                    <small class="fw-bolder text-muted">DOCUMENT STATUS</small><br>
                                                    <div v-if="report.is_approved === 1">
                                                        <p class="badge bg-primary h5">APPROVED DOCUMENTS</p>
                                                    </div>
                                                    <div v-else>
                                                        <p class="badge bg-danger h5">DISAPPROVED DOCUMENTS</p>
                                                    </div>
                                                </div>
                                                <div class="col-lg-4 col-md-12">
                                                    <small class="fw-bolder text-muted">DOCUMENT VERIFIER:</small><br>
                                                    <p class="badge bg-info h5">{{ staffName(report . staff) }}</p>
                                                </div>
                                                <div class="col-lg-4 col-md-12">
                                                    <small class="fw-bolder text-muted">DATE VERIFIED:</small><br>
                                                    <p class="badge bg-info h5">{{ getFormatDate(report . updated_at) }}
                                                    </p>
                                                </div>
                                                <div v-if="report.feedback != null" class="col-lg-12 col-md-12">
                                                    <small class="fw-bolder text-muted">REMARKS:</small><br>
                                                    <label for=""
                                                        class="form-control">{{ report . feedback }}</label>
                                                </div>


                                                <div v-if="report.is_approved === 2">
                                                    <form @submit.prevent="submitForm(item, task[0])" method="post"
                                                        id="form_{{ task[1] }}" enctype="multipart/form-data">
                                                        <div class="form-group">
                                                            <p class="h6">
                                                                <b>{{ task[0] . toUpperCase() }}</b>
                                                            </p>

                                                            <div class="form-group">
                                                                <small class="form-label"><b>ATTACH FILES<sup
                                                                            class="text-danger">*</sup></b></small>
                                                                <div class="form-group">
                                                                    <input type="file" class="form-control"
                                                                        ref="fileInput" multiple
                                                                        v-on:change="handleFileUpload($event, item)">
                                                                    <div v-if="forms.errors[item]">
                                                                        <span class="badge bg-danger mt-2"
                                                                            v-if="forms.errors[item].files">{{ forms . errors[item] . files[0] }}</span>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <div v-if="task.length > 2">
                                                                <div class="form-group">
                                                                    <small class="form-label"><b>REMARKS<sup
                                                                                class="text-danger">*</sup></b></small>
                                                                    <textarea class="form-control" v-model="forms.remarks[item]" cols="30" rows="3"></textarea>
                                                                    <div v-if="forms.errors[item]">
                                                                        <span class="badge bg-danger mt-2"
                                                                            v-if="forms.errors[item].remarks">{{ forms . errors[item] . remarks[0] }}</span>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <div class="float-end">
                                                                <button class="btn btn-primary"
                                                                    type="submit">SUBMIT</button>
                                                            </div>
                                                        </div>
                                                    </form>
                                                    <div v-if="forms.errors[item].message">
                                                        <span class="badge bg-danger mt-2"
                                                            v-if="forms.errors[item].remarks">{{ forms . errors[item] . message }}</span>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div v-else>
                                            <p class="badge bg-info h5">DOCUMENTS IS UNDER VERIFICATION OF OBT OFFICER
                                            </p>
                                        </div>

                                    </div>
                                </div> --}}
                            </div>
                            @if ($data->is_approved != null)
                                <div class="row">
                                    <div class="col-lg-4 col-md-12">
                                        <small class="fw-bolder text-muted">DOCUMENT STATUS</small><br>
                                        @if ($data->is_approved === 1)
                                            <p class="badge bg-primary h5">APPROVED DOCUMENTS</p>
                                        @else
                                            <p class="badge bg-danger h5">DISAPPROVED DOCUMENTS</p>
                                        @endif

                                    </div>
                                    <div class="col-lg-4 col-md-12">
                                        <small class="fw-bolder text-muted">DOCUMENT VERIFIER:</small><br>
                                        <p class="badge bg-info h5">
                                            {{ $data->staff->first_name . ' ' . $data->staff->last_name }}</p>
                                    </div>
                                    <div class="col-lg-4 col-md-12">
                                        <small class="fw-bolder text-muted">DATE VERIFIED:</small><br>
                                        <p class="badge bg-info h5">{{ $data->updated_at->format('F m,Y') }}
                                        </p>
                                    </div>
                                    @if ($data->is_approved === 2)
                                        <div class="col-lg-12 col-md-12">
                                            <small class="fw-bolder text-muted">FEEDBACK:</small><br>
                                            <label for="" class="form-control">{{ $data->feedback }}</label>
                                        </div>
                                    @endif

                                </div>
                            @else
                                <form action="{{ route('onboard.narative-report-disapproved') }}" method="post"
                                    class="needs-validation" novalidate>
                                    @csrf
                                    <input type="hidden" name="_narative" value="{{ base64_encode($data->id) }}">
                                    <div class="d-flex justify-content-between align-items-center mb-2  flex-wrap">
                                        <small class="text-muted"><b>FEEDBACK</b></small>
                                        <textarea name="_feedback" class="form-control" cols="30" rows="3" required></textarea>
                                    </div>
                                    <div class="row">
                                        <div class="col-md">
                                            <a href="{{ route('onboard.narative-report-approved') }}?_n={{ base64_encode($data->id) }}"
                                                class="btn btn-primary btn-sm w-100">APPROVED</a>
                                        </div>
                                        <div class="col-md">
                                            <button type="submit"
                                                class="btn btn-danger btn-sm w-100">DISAPPROVED</button>
                                        </div>
                                    </div>
                                </form>
                            @endif
                            <hr>
                        @endforeach
                    @else
                        <div class="form-group">
                            NO UPLOAD DOCUMENTS
                        </div>
                    @endif

                </div>
            </div>
        </div>


    </div>
    <div class="modal fade document-view-modal" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-xl">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel1">Document Review</h5>

                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">
                    </button>
                </div>
                <div class="modal-body">
                    <div class="btn-group " role="group" aria-label="Basic example">
                        <button type="button" class="btn btn-primary btn-sm" onclick="rotateImg(Math.PI/2)">Rotate
                            Left</button>
                        {{-- <button type="button" class="btn btn-primary btn-sm" onclick="initDraw()">Reset</button> --}}
                        <button type="button" class="btn btn-primary btn-sm" onclick="view.rotate(Math.PI/2)">Rotate
                            Right</button>
                    </div>
                </div>
                <iframe class="iframe-container form-view iframe-placeholder" width="100%" height="600px">
                </iframe>
            </div>
        </div>
    </div>
@endsection
