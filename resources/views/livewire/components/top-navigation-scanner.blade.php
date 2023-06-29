<nav class="nav navbar navbar-expand-lg navbar-light iq-navbar py-lg-0">
    <div class="container-fluid navbar-inner">

        <a href="{{-- {{ route('website.home') }} --}}" class="navbar-brand">
            <img src="{{ asset('assets/image/bma-logo-1.png') }}" alt="image"
                class="img-fluid rounded-circle avatar-70">
            <h2 class="logo-title me-3">BALIWAG MARITIME ACADEMY</h2>
            <span class="app badge-4 ">
            </span>
        </a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent"
            aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon">
                <span class="navbar-toggler-bar bar1 mt-2"></span>
                <span class="navbar-toggler-bar bar2"></span>
                <span class="navbar-toggler-bar bar3"></span>
            </span>
        </button>
        <div class="collapse navbar-collapse" id="navbarSupportedContent">
            <ul class="navbar-nav ms-auto top-menu navbar-nav align-items-center navbar-list mb-3 mb-lg-0">
                <li>
                <span class="text-info fw-bolder">
                    {{ $dateTime }} </span>
                </li>
            </ul>
        </div>
    </div>
</nav>