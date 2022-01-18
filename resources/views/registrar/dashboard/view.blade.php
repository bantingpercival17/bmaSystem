@extends('layouts.app-main')
@section('page-title', 'Dashboard')
@section('beardcrumb-content')
    <li class="breadcrumb-item active" aria-current="page">
        <svg width="14" height="14" class="me-2" viewBox="0 0 22 22" fill="none"
            xmlns="http://www.w3.org/2000/svg">
            <path
                d="M8.15722 19.7714V16.7047C8.1572 15.9246 8.79312 15.2908 9.58101 15.2856H12.4671C13.2587 15.2856 13.9005 15.9209 13.9005 16.7047V16.7047V19.7809C13.9003 20.4432 14.4343 20.9845 15.103 21H17.0271C18.9451 21 20.5 19.4607 20.5 17.5618V17.5618V8.83784C20.4898 8.09083 20.1355 7.38935 19.538 6.93303L12.9577 1.6853C11.8049 0.771566 10.1662 0.771566 9.01342 1.6853L2.46203 6.94256C1.86226 7.39702 1.50739 8.09967 1.5 8.84736V17.5618C1.5 19.4607 3.05488 21 4.97291 21H6.89696C7.58235 21 8.13797 20.4499 8.13797 19.7714V19.7714"
                stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"></path>
        </svg>Dashboard
    </li>
@endsection
@section('page-content')
    <div class="row">
        <div class="col-lg-12 col-xl-8">
            <div class="row">
                <div class="col-lg-4 col-md-6">
                    <div class="card" data-iq-gsap="onStart" data-iq-position-y="50" data-iq-rotate="0"
                        data-iq-trigger="scroll" data-iq-ease="power.out" data-iq-opacity="0">
                        <div class="card-body">
                            <div id="admin-chart-01" class="admin-chart-01 mb-2">
                            </div>
                            <div class="d-flex justify-content-between">
                                <div class="">
                                    <h3>2%</h3>
                                    <p class="mb-0">Service used</p>
                                </div>
                                <div class="">
                                    <small class="text-success"> <svg width="10" class="me-2" height="13"
                                            viewBox="0 0 10 13" fill="none" xmlns="http://www.w3.org/2000/svg">
                                            <path d="M4.81706 1.33325L4.81706 11.3333" stroke="#60E7A8" stroke-width="1.5"
                                                stroke-linecap="round" stroke-linejoin="round" />
                                            <path d="M0.801317 5.36646L4.81732 1.33312L8.83398 5.36646" stroke="#60E7A8"
                                                stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
                                        </svg>10%</small><br>
                                    <small>This Week</small>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-4 col-md-6">
                    <div class="card" data-iq-gsap="onStart" data-iq-position-y="150" data-iq-rotate="0"
                        data-iq-trigger="scroll" data-iq-ease="power.out" data-iq-opacity="0">
                        <div class="card-body">
                            <div id="admin-chart-02" class="admin-chart-02 mb-2">
                            </div>
                            <div class="d-flex justify-content-between align-items-center">
                                <div class="">
                                    <h3>2.5</h3>
                                    <p class="mb-0">Sales</p>
                                </div>
                                <div class="">
                                    <small class="text-danger"> <svg width="16" class="me-2" height="17"
                                            viewBox="0 0 16 17" fill="none" xmlns="http://www.w3.org/2000/svg">
                                            <path d="M8.18294 13.6667L8.18294 3.66667" stroke="#E93535" stroke-width="1.5"
                                                stroke-linecap="round" stroke-linejoin="round" />
                                            <path d="M12.1987 9.63347L8.18268 13.6668L4.16602 9.63347" stroke="#E93535"
                                                stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
                                        </svg>2% </small><br>
                                    <small> Week</small>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-4">
                    <div class="card" data-iq-gsap="onStart" data-iq-position-y="250" data-iq-rotate="0"
                        data-iq-trigger="scroll" data-iq-ease="power.out" data-iq-opacity="0">
                        <div class="card-body">
                            <div id="admin-chart-03" class="admin-chart-03 mb-2">
                            </div>
                            <div class="d-flex justify-content-between">
                                <div>
                                    <h3>351</h3>
                                    <p class="mb-0">User Collect</p>
                                </div>
                                <div class="">
                                    <small class="text-success"> <svg width="10" class="me-2" height="13"
                                            viewBox="0 0 10 13" fill="none" xmlns="http://www.w3.org/2000/svg">
                                            <path d="M4.81706 1.33325L4.81706 11.3333" stroke="#60E7A8" stroke-width="1.5"
                                                stroke-linecap="round" stroke-linejoin="round" />
                                            <path d="M0.801317 5.36646L4.81732 1.33312L8.83398 5.36646" stroke="#60E7A8"
                                                stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
                                        </svg>4%</small><br>
                                    <small>This Week</small>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-12 col-xl-4">
            <div class="card" data-iq-gsap="onStart" data-iq-position-y="70" data-iq-rotate="0"
                data-iq-trigger="scroll" data-iq-ease="power.out" data-iq-opacity="0">
                <div class="card-header d-flex justify-content-between flex-wrap  border-bottom-0">
                    <div class="header-title">
                        <h4 class="card-title fw-bold">Users</h4>
                    </div>
                </div>
                <div class="card-body pt-2">
                    <div class="d-flex  align-items-center justify-content-between" style="position: relative;">
                        <div>
                            <h6 class="mb-3">Subscribes</h6>
                            <span>57 m</span>
                            <br>
                            <span class="text-primary">21.77%</span>
                        </div>
                        <div id="admin-chart-05" class="rounded-bar-chart"></div>
                    </div>
                    <hr>
                    <div class="d-flex  align-items-center justify-content-between">
                        <div>
                            <h6 class="mb-3">Unsubscribe</h6>
                            <span>36 k</span><br />
                            <span class="text-danger">95.77%</span>
                        </div>
                        <div id="admin-chart-06" class="rounded-bar-chart"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
