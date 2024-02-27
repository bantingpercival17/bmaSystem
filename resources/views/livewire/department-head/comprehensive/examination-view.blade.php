@php
    $pageTitle = 'Comprehensive Examination';
@endphp
@section('page-title', $pageTitle)
<div>
    <div class="row">
        <div class="col-lg-8">
            <p class="display-6 fw-bolder text-primary">{{ strtoupper($pageTitle) }}</p>
            <div class="content-body">
                @foreach ($courses as $course)
                    <div class="card">
                        <div class="card-header">
                            <label for="" class="fw-bolder text-primary h4">{{ $course->course_name }}</label>
                        </div>
                        <div class="card-body">
                            @if ($course->comprehensive_examination)
                                <table class="table">
                                    <thead>
                                        <tr>
                                            <th>COMPETENCE CODE</th>
                                            <th>COMPETENCE NAME</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($course->comprehensive_examination as $compentence)
                                            <tr>
                                                <td>{{ $compentence->competence_code }}</td>
                                                <td>{{ $compentence->competence_name }}</td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            @endif
                        </div>
                    </div>
                @endforeach
            </div>
        </div>

        <div class="col-lg-4">
            <div class="card">
                <div class="card-body">
                    <lable class="fw-bolder text-primary">ADD COMPENTENCE</lable>
                    <form action="{{ route('department-head.store-compre') }}" method="post"
                        enctype="multipart/form-data">
                        @csrf
                        <div class="form-group">
                            <small class="text-primary fw-bolder">COURSE</small>
                            <select name="course" id=""
                                class="form-select form-select-sm border border-primary">
                                @foreach ($courses as $item)
                                    <option value="{{ $item->id }}">{{ $item->course_name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group">
                            <small class="text-primary fw-bolder">COMPETENCE CODE</small>
                            <input type="text" name="code"
                                class="form-control form-control-sm border border-primary">
                            </select>
                        </div>
                        <div class="form-group">
                            <small class="text-primary fw-bolder">COMPETENCE NAME</small>
                            <input type="text" name="name"
                                class="form-control form-control-sm border border-primary">
                            </select>
                        </div>
                        <div class="form-group">
                            <small class="text-primary fw-bolder">ATTACH FILES</small>
                            <input type="file" name="upload-file"
                                class="form-control form-control-sm border border-primary">
                            </select>
                        </div>
                        <div class="form-group">
                            <button type="submit" class="btn btn-primary">CREATE</button>
                        </div>
                    </form>
                </div>
            </div>

        </div>
    </div>
    {{-- Care about people's approval and you will be their prisoner. --}}
</div>
