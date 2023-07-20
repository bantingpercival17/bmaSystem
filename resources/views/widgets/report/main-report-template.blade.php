<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>@yield('title-report')</title>
    <link rel="stylesheet" href="{{ public_path('css/main-pdf.css') }}">
    @yield('style')
</head>

<body>
    <header>
        <label for="" class="form-code">BMA FORM </label>
        <div class="text-center">
            <img src="{{ public_path() . '/assets/image/report-header.png' }}" alt="page-header">
        </div>
    </header>
    <div class="page-content">
        @yield('content')
    </div>
    <div id="footer">
        <div class="page-number"></div>
    </div>
</body>

</html>