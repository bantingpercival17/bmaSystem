@extends('layouts.app-main')
@php
    $_title = 'Examination Category Question ';
@endphp
@section('page-title', $_title)
@section('beardcrumb-content')
    <li class="breadcrumb-item">
        <a href="{{ route('admin.examination') }}">
            <svg width="14" height="14" class="me-2" viewBox="0 0 22 22" fill="none"
                xmlns="http://www.w3.org/2000/svg">
                <path
                    d="M8.15722 19.7714V16.7047C8.1572 15.9246 8.79312 15.2908 9.58101 15.2856H12.4671C13.2587 15.2856 13.9005 15.9209 13.9005 16.7047V16.7047V19.7809C13.9003 20.4432 14.4343 20.9845 15.103 21H17.0271C18.9451 21 20.5 19.4607 20.5 17.5618V17.5618V8.83784C20.4898 8.09083 20.1355 7.38935 19.538 6.93303L12.9577 1.6853C11.8049 0.771566 10.1662 0.771566 9.01342 1.6853L2.46203 6.94256C1.86226 7.39702 1.50739 8.09967 1.5 8.84736V17.5618C1.5 19.4607 3.05488 21 4.97291 21H6.89696C7.58235 21 8.13797 20.4499 8.13797 19.7714V19.7714"
                    stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"></path>
            </svg>{{ $_title }}
        </a>
    </li>
    <li class="breadcrumb-item active" aria-current="page">
        {{ $category->category_name }}
    </li>
@endsection
@section('page-content')
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <span class="fw-bolder text-primary">{{ $category->category_name }}</span>
                    <br>
                    <small class="fw-bolder">{{ $category->instruction }}</small>
                </div>
                <div class="card-body">
                    <table {{-- id="datatable" --}} class="table table-striped" {{-- data-toggle="data-table" --}}>
                        <thead>
                            <tr>
                                <th>QUESTION</th>
                                <th>CHOICES</th>
                                <th>ACTION</th>
                            </tr>
                        </thead>

                        <tbody>

                        </tbody>
                    </table>
                </div>
            </div>
            <div class="mt-3">
                @forelse ($category->question as $key=> $item)
                    <div class="card m-3">
                        <div class="card-header">
                            <span class="badge bg-primary">QUESTION {{ $key + 1 }}</span>
                        </div>
                        <div class="card-body">
                            <label for="" class="text-primary fw-bolder">{{ $item->question }}</label>
                            {{ $item->image_path }}
                            <img src="{{ $item->image_path }}" alt="" width="100">
                            <table class="table mt-3">
                                <thead>
                                    <tr>
                                        <th>CHOICES</th>
                                        <th>ACTIONS</th>
                                    </tr>
                                </thead>
                                <tbody>

                                    @if ($item->choices)
                                        @forelse ($item->choices as $data)
                                            <tr>
                                                <td>
                                                    @if ($data->is_answer)
                                                        <label for="" class="text-primary fw-bolder">
                                                            {{ $data->choice_name }}</label>
                                                    @else
                                                        {{ $data->choice_name }}
                                                    @endif

                                                </td>
                                                <td></td>
                                            </tr>
                                        @empty
                                        @endforelse
                                    @endif
                                </tbody>
                            </table>
                        </div>
                    </div>
                    {{--  <tr>
                        <td>{{ $item->question }}</td>
                        <td>
                            @if ($item->choices)
                                @forelse ($item->choices as $data)
                                    {{ $data }}
                                    <br>
                                @empty
                                @endforelse
                            @endif

                        </td>
                        <td></td>
                    </tr> --}}
                @empty
                @endforelse
            </div>
        </div>
    </div>


@endsection
