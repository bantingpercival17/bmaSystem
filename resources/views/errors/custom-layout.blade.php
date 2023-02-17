<!doctype html>
<html lang="en" dir="ltr">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <link rel="apple-touch-icon" sizes="76x76" href="{{ asset('assets/image/bma-logo-1.png') }}">
    <link rel="icon" type="image/png" href="{{ asset('assets/image/bma-logo-1.png') }}">
    <title>@yield('title') | Baliwag Maritime Academy Inc.</title>


    <link rel="stylesheet" href="{{ asset('css/app-1.css') }}">
    <link rel="stylesheet" href="https://unpkg.com/swiper/swiper-bundle.min.css" />
    <link rel="stylesheet" href="{{ asset('resources/plugin/toastify/toastify.css') }}">


<body class=" ">
    <!-- loader Start -->
    <div id="loading">
        <div class="loader ">
            <div class="loader-body word-spacing">
                <h1 class="loader-title fw-bold">BMA PORTAL</h1>
            </div>
        </div>
    </div>
    <!-- loader END -->

    <div class="wrapper">
        <div class="res-hide  auth-400"
            style="background: url('@yield('image')') no-repeat; background-size: cover;height:100vh;">
            <div class="container">
                <div class="row">
                    <div class="error-shap position-relative d-block d-xl-none">
                        <svg class="rect1" width="19" viewBox="0 0 19 19" fill="none"
                            xmlns="http://www.w3.org/2000/svg">
                            <rect y="11.0215" width="14" height="14" rx="2"
                                transform="rotate(-51.9256 0 11.0215)" fill="#FDDA5F" />
                        </svg>
                        <svg class="rect2" width="11" viewBox="0 0 11 10" fill="none"
                            xmlns="http://www.w3.org/2000/svg">
                            <rect y="5.7207" width="8" height="8" rx="2"
                                transform="rotate(-51.9256 0 5.7207)" fill="#FDDA5F" />
                        </svg>
                        <svg class="rect3" width="14" viewBox="0 0 14 13" fill="none"
                            xmlns="http://www.w3.org/2000/svg">
                            <rect y="7.48828" width="10" height="10" rx="2"
                                transform="rotate(-51.9256 0 7.48828)" fill="#7B60E7" />
                        </svg>
                        <svg class="rect4" width="19" viewBox="0 0 19 19" fill="none"
                            xmlns="http://www.w3.org/2000/svg">
                            <rect y="11.0195" width="14" height="14" rx="2"
                                transform="rotate(-51.9256 0 11.0195)" fill="#FFE0E3" />
                        </svg>
                        <svg class="rect5" width="25" height="25" viewBox="0 0 25 25" fill="none"
                            xmlns="http://www.w3.org/2000/svg">
                            <rect y="14.377" width="18.2858" height="18.2858" rx="2"
                                transform="rotate(-51.9256 0 14.377)" fill="#FAD1D1" />
                        </svg>
                        <svg class="rect6" width="13" height="12" viewBox="0 0 13 12" fill="none"
                            xmlns="http://www.w3.org/2000/svg">
                            <rect y="6.78516" width="9.51587" height="9.51587" rx="2"
                                transform="rotate(-51.9256 0 6.78516)" fill="#7B60E7" />
                        </svg>
                        <svg class="rect7" width="19" height="19" viewBox="0 0 19 19" fill="none"
                            xmlns="http://www.w3.org/2000/svg">
                            <rect y="11.0195" width="14" height="14" rx="2"
                                transform="rotate(-51.9256 0 11.0195)" fill="#FDDA5F" />
                        </svg>
                        <svg class="rect8" width="17" height="16" viewBox="0 0 17 16" fill="none"
                            xmlns="http://www.w3.org/2000/svg">
                            <rect y="9.25195" width="12" height="12" rx="2"
                                transform="rotate(-51.9256 0 9.25195)" fill="#FDDA5F" />
                        </svg>
                    </div> <img src="@yield('image')" class="sm-img d-none img-fluid" alt="images">
                    <div class="col-lg-12 text-center pt-6">
                        <h2 class="mb-4 fw-bolder text-white">Oops! @yield('message')</h2>
                        <p class="mb-4 text-secondary"> @yield('code', __('Oh no'))</p>
                        <a class="btn btn-primary d-inline-flex align-items-center" href="./">Back to Content
                            Page</a>
                    </div>
                </div>
            </div>
        </div>
        <div class="res-hide1 d-none">
            <div style="background: url('@yield('image')') no-repeat; background-size: cover;height:50vh;">
            </div>
            <div class="container mt-5">
                <div class="row">
                    <div class="col-lg-12 text-center pt-6">
                        <div class="error-shap position-relative d-block d-xl-none">
                            <svg class="rect1" width="19" viewBox="0 0 19 19" fill="none"
                                xmlns="http://www.w3.org/2000/svg">
                                <rect y="11.0215" width="14" height="14" rx="2"
                                    transform="rotate(-51.9256 0 11.0215)" fill="#FDDA5F" />
                            </svg>
                            <svg class="rect2" width="11" viewBox="0 0 11 10" fill="none"
                                xmlns="http://www.w3.org/2000/svg">
                                <rect y="5.7207" width="8" height="8" rx="2"
                                    transform="rotate(-51.9256 0 5.7207)" fill="#FDDA5F" />
                            </svg>
                            <svg class="rect3" width="14" viewBox="0 0 14 13" fill="none"
                                xmlns="http://www.w3.org/2000/svg">
                                <rect y="7.48828" width="10" height="10" rx="2"
                                    transform="rotate(-51.9256 0 7.48828)" fill="#7B60E7" />
                            </svg>
                            <svg class="rect4" width="19" viewBox="0 0 19 19" fill="none"
                                xmlns="http://www.w3.org/2000/svg">
                                <rect y="11.0195" width="14" height="14" rx="2"
                                    transform="rotate(-51.9256 0 11.0195)" fill="#FFE0E3" />
                            </svg>
                            <svg class="rect5" width="25" height="25" viewBox="0 0 25 25" fill="none"
                                xmlns="http://www.w3.org/2000/svg">
                                <rect y="14.377" width="18.2858" height="18.2858" rx="2"
                                    transform="rotate(-51.9256 0 14.377)" fill="#FAD1D1" />
                            </svg>
                            <svg class="rect6" width="13" height="12" viewBox="0 0 13 12" fill="none"
                                xmlns="http://www.w3.org/2000/svg">
                                <rect y="6.78516" width="9.51587" height="9.51587" rx="2"
                                    transform="rotate(-51.9256 0 6.78516)" fill="#7B60E7" />
                            </svg>
                            <svg class="rect7" width="19" height="19" viewBox="0 0 19 19" fill="none"
                                xmlns="http://www.w3.org/2000/svg">
                                <rect y="11.0195" width="14" height="14" rx="2"
                                    transform="rotate(-51.9256 0 11.0195)" fill="#FDDA5F" />
                            </svg>
                            <svg class="rect8" width="17" height="16" viewBox="0 0 17 16" fill="none"
                                xmlns="http://www.w3.org/2000/svg">
                                <rect y="9.25195" width="12" height="12" rx="2"
                                    transform="rotate(-51.9256 0 9.25195)" fill="#FDDA5F" />
                            </svg>
                        </div>
                        <h2 class="mb-4 fw-bolder text-white">Oops! @yield('message')</h2>
                        <p class="mb-4 text-secondary"> @yield('code', __('Oh no'))</p>
                        <a class="btn btn-primary d-inline-flex align-items-center" href="./">Back to Content
                            Page</a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- <script src="{{ asset('js/app-1.js') }}"></script> --}}
    <script src="{{ asset('resources/js/core/libs.min.js') }}"></script><!-- Library Bundle Script -->
    <script src="{{ asset('resources/js/core/external.min.js') }}"></script> <!-- External Library Bundle Script -->
    <script src="{{ asset('resources/js/charts/widgetcharts.js') }}"></script><!-- Widgetchart Script -->
    <script src="{{ asset('resources/js/charts/vectore-chart.js') }}"></script><!-- mapchart Script -->
    <script src="{{ asset('resources/js/charts/dashboard.js') }}" defer></script>
    <script src="{{ asset('resources/js/plugins/fslightbox.js') }}"></script> <!-- fslightbox Script -->

    <!-- GSAP Animation -->
    <script src="{{ asset('resources/vendor/gsap/gsap.min.js') }}"></script>
    <script src="{{ asset('resources/vendor/gsap/ScrollTrigger.min.js') }}"></script>
    <script src="{{ asset('resources/js/gsap-init.js') }}"></script>

    <!-- Form Wizard Script -->
    <script src="{{ asset('resources/js/plugins/form-wizard.js') }}"></script>

    <!-- App Script -->
    <script src="{{ asset('resources/js/gigz.js') }}" defer></script>
    {{-- documents Viewr --}}
    <script src="{{ asset('resources/js/plugins/custom-document-viewer.js') }}"></script>
    <script src="{{ asset('resources/js/plugins/viewer.1.0.0.js') }}"></script>
    <script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="{{ asset('resources/plugin/select/js/select2.min.js') }}"></script>
    <script src="{{ asset('resources/plugin/toastify/toastify.js') }}"></script>
    <script src="{{ asset('resources/plugin/editor/editor.js') }}"></script>

</body>

</html>
