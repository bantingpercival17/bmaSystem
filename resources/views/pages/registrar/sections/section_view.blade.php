@extends('layouts.app-main')
@section('page-title', 'Sections')
@section('beardcrumb-content')
    <li class="breadcrumb-item">
        <a href="{{ route('registrar.section-view') }}">
            <svg width="14" height="14" class="me-2" viewBox="0 0 22 22" fill="none"
                xmlns="http://www.w3.org/2000/svg">
                <path
                    d="M8.15722 19.7714V16.7047C8.1572 15.9246 8.79312 15.2908 9.58101 15.2856H12.4671C13.2587 15.2856 13.9005 15.9209 13.9005 16.7047V16.7047V19.7809C13.9003 20.4432 14.4343 20.9845 15.103 21H17.0271C18.9451 21 20.5 19.4607 20.5 17.5618V17.5618V8.83784C20.4898 8.09083 20.1355 7.38935 19.538 6.93303L12.9577 1.6853C11.8049 0.771566 10.1662 0.771566 9.01342 1.6853L2.46203 6.94256C1.86226 7.39702 1.50739 8.09967 1.5 8.84736V17.5618C1.5 19.4607 3.05488 21 4.97291 21H6.89696C7.58235 21 8.13797 20.4499 8.13797 19.7714V19.7714"
                    stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"></path>
            </svg>Sections
        </a>
    </li>
    <li class="breadcrumb-item active" aria-current="page">
        {{ $_section->section_name }}
    </li>
@endsection
@section('page-content')
    <div class="card">
        <div class="card-header d-flex justify-content-between">
            <div class="header-title">
                <h4 class="card-title">{{ $_section->section_name }}</h4>
                <label for="" class="h6 text-muted">TOTAL STUDENTS: {{ $_student_list->count() }}</label>
            </div>
            <div class="card-tool">

                <div class="">
                    <form action="{{ route('registrar.section-import') }}" method="post" enctype="multipart/form-data">
                        @csrf
                        <div class="row">
                            <div class="col-md">
                                <div class="form-group">
                                    <input class="form-control form-control-sm" type="file" id="customFile1"
                                        name="upload-file" required>
                                    <input type="hidden" name="section" value="{{ request()->input('_section') }}">
                                </div>
                            </div>
                            <div class="col-md-3">
                                <button class="btn btn-info text-white btn-sm" type="submit">UPLOAD</button>
                            </div>
                            <div class="col-md">
                                <a href="{{ route('registrar.section-add-student') . '?_section=' . base64_encode($_section->id) }}{{ request()->input('_academic') ? '&_academic=' . request()->input('_academic') : '' }}"
                                    class=" btn btn-primary btn-sm">
                                    <svg width="16" viewBox="0 0 24 24" fill="none"
                                        xmlns="http://www.w3.org/2000/svg">
                                        <path fill-rule="evenodd" clip-rule="evenodd"
                                            d="M9.5 12.5537C12.2546 12.5537 14.4626 10.3171 14.4626 7.52684C14.4626 4.73663 12.2546 2.5 9.5 2.5C6.74543 2.5 4.53737 4.73663 4.53737 7.52684C4.53737 10.3171 6.74543 12.5537 9.5 12.5537ZM9.5 15.0152C5.45422 15.0152 2 15.6621 2 18.2464C2 20.8298 5.4332 21.5 9.5 21.5C13.5448 21.5 17 20.8531 17 18.2687C17 15.6844 13.5668 15.0152 9.5 15.0152ZM19.8979 9.58786H21.101C21.5962 9.58786 22 9.99731 22 10.4995C22 11.0016 21.5962 11.4111 21.101 11.4111H19.8979V12.5884C19.8979 13.0906 19.4952 13.5 18.999 13.5C18.5038 13.5 18.1 13.0906 18.1 12.5884V11.4111H16.899C16.4027 11.4111 16 11.0016 16 10.4995C16 9.99731 16.4027 9.58786 16.899 9.58786H18.1V8.41162C18.1 7.90945 18.5038 7.5 18.999 7.5C19.4952 7.5 19.8979 7.90945 19.8979 8.41162V9.58786Z"
                                            fill="currentColor"></path>
                                    </svg> Add Students
                                </a>
                            </div>
                        </div>

                    </form>

                </div>
                {{-- <button type="button" class="mt-2 btn btn-info btn-sm text-white">
                    <svg width="16" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path
                            d="M11.2301 7.29052V3.2815C11.2301 2.85523 11.5701 2.5 12.0001 2.5C12.3851 2.5 12.7113 2.79849 12.763 3.17658L12.7701 3.2815V7.29052L17.55 7.29083C19.93 7.29083 21.8853 9.23978 21.9951 11.6704L22 11.8861V16.9254C22 19.373 20.1127 21.3822 17.768 21.495L17.56 21.5H6.44C4.06 21.5 2.11409 19.5608 2.00484 17.1213L2 16.9047L2 11.8758C2 9.4281 3.87791 7.40921 6.22199 7.29585L6.43 7.29083H11.23V13.6932L9.63 12.041C9.33 11.7312 8.84 11.7312 8.54 12.041C8.39 12.1959 8.32 12.4024 8.32 12.6089C8.32 12.7659 8.3648 12.9295 8.45952 13.0679L8.54 13.1666L11.45 16.1819C11.59 16.3368 11.79 16.4194 12 16.4194C12.1667 16.4194 12.3333 16.362 12.4653 16.2533L12.54 16.1819L15.45 13.1666C15.75 12.8568 15.75 12.3508 15.45 12.041C15.1773 11.7594 14.7475 11.7338 14.4462 11.9642L14.36 12.041L12.77 13.6932V7.29083L11.2301 7.29052Z"
                            fill="currentColor"></path>
                    </svg> Print</button> --}}
            </div>
        </div>
        <div class="card-body">
            <div class="table-responsive mt-4">

                <table id="basic-table" class="table table-striped mb-0" role="grid">
                    <thead>
                        <tr>
                            <th>STUDENT NUMBER</th>
                            <th>FULL NAME</th>
                            <th>ACTION</th>
                        </tr>
                    </thead>
                    <tbody>
                        @if (count($_student_list) > 0)
                            @foreach ($_student_list as $_data)
                                <tr>
                                    <td> {{ $_data->student->account ? $_data->student->account->student_number : '' }}
                                    </td>
                                    <td>{{ ucwords($_data->last_name . ', ' . $_data->first_name) }}
                                    </td>
                                    <td>
                                        @if ($_data->student)
                                            @if ($_student_section = $_data->student->section(Auth::user()->staff->current_academic()->id)->first())
                                                <label for="" class="text-primary btn-remove fw-bolder"
                                                    data-url="{{ route('registrar.student-section-remove') . '?_student_section=' . base64_encode($_student_section->id) }}"
                                                    data-title="Student Section">Remove </label>
                                            @endif
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        @else
                            <tr>
                                <td colspan="3">No Student </td>
                            </tr>
                        @endif

                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection
@section('js')
    <script>
        $('.btn-remove').click(function(event) {
            Swal.fire({
                title: $(this).data('title'),
                text: "Do you want to remove?",
                icon: 'info',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes'
            }).then((result) => {
                var _url = $(this).data('url');
                if (result.isConfirmed) {
                    window.location.href = _url
                }
            })
            event.preventDefault();
        })
    </script>
@endsection
